<?php

/**
 * @brief RabbitMQ classes for client and server
 * Changes from original code:
 * 1. The routing key is no longer automatically set
 * 2. Added function to close the connection
 * 3. Queues and exchanges are retrieved from the INI file
 * 4. Implemented namespace RabbitMQ
 */
namespace RabbitMQ;
require_once(__DIR__ . "/../../vendor/autoload.php");
use function RabbitMQ\getHostInfo;
use PhpAmqpLib\Connection\AMQPStreamConnection;
//use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exception\AMQPProtocolChannelException;

/**
 *  RabbitMQ Server class for processing messages and sending responses
 */
class rabbitMQServer
{
	// class variables and methods
	private $machine = ""; // the machine name defaults to empty string
	private $host;
	private $port;
	private $user;
	private $password;
	private $vhost;
	private $exchange;
	private $queue;
	private $routing_key;
	private $exchange_type = "topic"; // default to topic
	private $auto_delete = false; // default to false
	/**
	 * Initializes RabbitMQ server by the getting the machine information and server configuration
	 * 
	 * @param machine the machine name to get the configuration from the INI file
	 * @param server the server name to get the configuration from the INI file
	 */
	function __construct($machine, $server = "rabbitMQ")
	{
		// set the machine information from the INI file
		$this->machine = getHostInfo(array($machine));
		$this->host = $this->machine[$server]["HOST"];
		$this->port = $this->machine[$server]["PORT"];
		$this->user = $this->machine[$server]["USER"];
		$this->password = $this->machine[$server]["PASSWORD"];
		$this->vhost = $this->machine[$server]["VHOST"];
		// if the exchange type is set, then set it
		if (isset($this->machine[$server]["EXCHANGE_TYPE"])) {
			$this->exchange_type = $this->machine[$server]["EXCHANGE_TYPE"];
		}
		// if the auto delete is set, then set it
		if (isset($this->machine[$server]["AUTO_DELETE"])) {
			$this->auto_delete = $this->machine[$server]["AUTO_DELETE"];
		}
		// if the routing key is set, then set it
		if (isset($this->machine[$server]["ROUTING_KEY"])) {
			$this->routing_key = $this->machine[$server]["ROUTING_KEY"];
		}
		// set the exchange and queue
		$this->exchange = $this->machine[$server]["EXCHANGE"];
		$this->queue = $this->machine[$server]["QUEUE"];
	}

	/**
	 * Processes the incoming messages, sends acknowledgement, 
	 * and sends a response based on the message's replyTo and correlationId
	 * 
	 * @param msg the message to process
	 * @throws Exception if an exception is caught
	 */
	function process_message($msg)
	{
		// acknowledge the message
		// basic_ack(): http://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Channel-AMQPChannel.html#method_basic_ack
		// delivery_info: http://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Message-AMQPMessage.html#method_get_delivery_info
		$this->channel->basic_ack($msg->delivery_info['delivery_tag']);
		try {
			// Get the message body and decode it
			$body = $msg->body;
			$payload = json_decode($body, true);

			// Process the message using the callback function
			$response = call_user_func($this->callback, $payload);
			// if the message has a "reply_to", then it is a request
			// get_properties(): http://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Message-AMQPMessage.html#method_get_properties
			if (isset($msg->get_properties()['reply_to'])) {
				// message wants a response
				// process request
				$replyKey = $this->routing_key . ".response";
				$correlationId = $msg->get_properties()['correlation_id'];
				$response = new AMQPMessage(
					json_encode($response),
					['correlation_id' => $correlationId]
				);
				$connection = new AMQPStreamConnection(
					$this->host,
					$this->port,
					$this->user,
					$this->password,
					$this->vhost
				);
		
				$channel = $connection->channel();
				// send the response
				// basic_publish(): http://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Channel-AMQPChannel.html#method_basic_publish
				$channel->basic_publish($response, $this->exchange, $replyKey);
				return;
			} else {
				// if the message does not have a "reply_to", then it is a one-way message
				echo "processed one-way message\n";
			}
		} catch (AMQPProtocolChannelException $e) {
			// if an exception is caught, then log it
			echo "error: rabbitMQServer: process_message: exception caught: " . $e;
		}
	}
	/**
	 * Starts the request processing loop, calls the callback function for each message received
	 * and sends a response based on the message's replyTo and correlationId
	 * Sets up AMQP connection, channel, exchange, queue, and binds the queue to the exchange and
	 * begins consuming messages from the queue
	 * 
	 * @param callback function to be called for each message received
	 */
	function process_requests($callback)
	{
		// try to process the requests
		try {

			$connection = new AMQPStreamConnection(
				$this->host,
				$this->port,
				$this->user,
				$this->password,
				$this->vhost
			);
			$channel = $connection->channel();
			$this->channel = $channel;

			$this->callback = $callback; // set the callback function
			// Declares a queue, creates it if it doesn't already exist
			// http://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Channel-AMQPChannel.html#method_queue_declare
			$channel->queue_declare($this->queue, false, true, false, $this->auto_delete);
			// Declares exchange, creates it if it doesn't already exist
			// http://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Channel-AMQPChannel.html#method_exchange_declare
			$channel->exchange_declare($this->exchange, $this->exchange_type, true, false, false); // not sure if we should auto delete the exchanges
			// Bind queues to exchanges
			// http://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Channel-AMQPChannel.html#method_queue_bind
			$channel->queue_bind($this->queue, $this->exchange, $this->routing_key);
			// Starts a queue consumer, the callback function will be called for each message received
			// http://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Channel-AMQPChannel.html#method_basic_consume
			$channel->basic_consume($this->queue, '', false, false, false, false, [$this, 'process_message']);

			// loop as long as the channel has callbacks registered
			while (count($channel->callbacks)) {
				// wait for messages
				$channel->wait();
			}
		} catch (AMQPProtocolChannelException $e) {
			// if an exception is caught, then log it
			trigger_error("Failed to start request processor: " . $e, E_USER_ERROR);
		}
	}
}
/**
 * RabbitMQ Client class for sending request, processing responses, and publishing messages
 */
class rabbitMQClient
{
	// class variables and methods
	private $machine = ""; // the machine name defaults to empty string
	private $host;
	private $port;
	private $user;
	private $password;
	private $vhost;
	private $exchange;
	private $queue;
	private $routing_key;
	private $exchange_type = "topic"; // default to topic
	private $auto_delete = false; // default to false
	private $response_queue = array();
	private $connection;
    private $channel;

	/**
	 * Initializes RabbitMQ client by the getting the 
	 * machine information and server configuration
	 * 
	 * @param machine the machine name
	 * @param server the server name
	 */
	function __construct($machine, $server = "rabbitMQ")
	{
		// set the machine information from the INI file
		$this->machine = getHostInfo(array($machine));
		$this->host = $this->machine[$server]["HOST"];
		$this->port = $this->machine[$server]["PORT"];
		$this->user = $this->machine[$server]["USER"];
		$this->password = $this->machine[$server]["PASSWORD"];
		$this->vhost = $this->machine[$server]["VHOST"];
		// if the exchange type is set, then set it
		if (isset($this->machine[$server]["EXCHANGE_TYPE"])) {
			$this->exchange_type = $this->machine[$server]["EXCHANGE_TYPE"];
		}
		// if the auto delete is set, then set it
		if (isset($this->machine[$server]["AUTO_DELETE"])) {
			$this->auto_delete = $this->machine[$server]["AUTO_DELETE"];
		}
		// if the routing key is set, then set it
		if (isset($this->machine[$server]["ROUTING_KEY"])) {
			$this->routing_key = $this->machine[$server]["ROUTING_KEY"];
		}
		// set the exchange and queue
		$this->exchange = $this->machine[$server]["EXCHANGE"];
		$this->queue = $this->machine[$server]["QUEUE"];
	}
	/**
	 *  Processes the response from the server
	 * 
	 * @param response the response from the server
	 * @return true if the response is processed, false otherwise
	 */
	function process_response($response)
	{
		$correlation_id = $response->get_properties()['correlation_id'];
		if (!isset($this->response_queue[$correlation_id])) {
			echo  "unknown correlation id\n";
			return true;
		} elseif ($this->response_queue[$correlation_id] === 'acknowledged') {
			echo  "response already acknowledged\n";
			return true;
		}

		$this->channel->basic_ack($response->delivery_info['delivery_tag']);
		$body = $response->body;
		$payload = json_decode($body, true);
		if (!(isset($payload))) {
			$payload = "[empty response]";
		}
		$this->response_queue[$correlation_id] = $payload;
		return false;
	}
	/** 
	 * Sends a request to the server, waits for a response, and returns the response payload
	 * 
	 * @param message array to be sent to the server
	 * @return response payload from the server
	 */
	function send_request($request)
	{
		$correlation_id = uniqid(); // create a unique id for the request

		$json_message = json_encode($request);

		try {

			$connection = new AMQPStreamConnection(
				$this->host,
				$this->port,
				$this->user,
				$this->password,
				$this->vhost
			);
	
			$channel = $connection->channel();

			$this->connection = $connection;
			$this->channel = $channel;

			// queue and exchange declarations
			$channel->queue_declare($this->queue, false, true, false, $this->auto_delete);
			$channel->exchange_declare(
				$this->exchange,
				$this->exchange_type,
				true,
				false,
				false
			);
			$channel->queue_bind($this->queue, $this->exchange, $this->routing_key);

			// callback queue
			$cbq_name = $this->queue . "_response"; // [c]all[b]ack [q]ueue name
			$channel->queue_declare($cbq_name, false, true, false, true);
			$channel->queue_bind($cbq_name, $this->exchange, 
				$this->routing_key . ".response");

			// create the message
			$msg = new AMQPMessage(
				$json_message,
				array(
					'correlation_id' => $correlation_id,
					'reply_to' => $cbq_name
				)
			);

			$channel->basic_publish($msg, $this->exchange, $this->routing_key);
			$this->response_queue[$correlation_id] = "waiting"; // set the response queue to waiting
			$channel->basic_consume(
				$cbq_name,
				'',
				false,
				false,
				false,
				false,
				[$this, 'process_response']
			);

			while ($this->response_queue[$correlation_id] === "waiting") {
				$channel->wait();
			}


			$response = $this->response_queue[$correlation_id];
			unset($this->response_queue[$correlation_id]);
			return $response;
		} catch (AMQPProtocolChannelException $e) {
			die("failed to send message to exchange: " . $e->getMessage() . "\n");
		}
	}
	/**
	 * Publishes a one-way message to the server.
	 * These are messages that do not require a response and are automatically deleted.
	 *
	 * @param array $message to be sent to the server
	 * @return bool true if the message was sent successfully, false otherwise
	 * @throws Exception if the message cannot be sent
	 **/
	function publish($message)
	{
		$json_message = json_encode($message);

		try {

			$connection = new AMQPStreamConnection(
				$this->host,
				$this->port,
				$this->user,
				$this->password,
				$this->vhost
			);
			$channel = $connection->channel();


			$this->connection = $connection;
			$this->channel = $channel;

			$msg = new AMQPMessage($json_message);
			// Declare the queue if it does not exist
			$channel->queue_declare($this->queue, false, true, false, $this->auto_delete);
			// Declare the exchange if it does not exist
			$channel->exchange_declare($this->exchange, $this->exchange_type, false, true, false);
			// 
			$channel->queue_bind($this->queue, $this->exchange, $this->routing_key);
			// publish the message to the exchange
			$channel->basic_publish($msg, $this->exchange, $this->routing_key);
		} catch (AMQPProtocolChannelException $e) {
			die("failed to publish message to exchange: " . $e->getMessage() . "\n");
		}
	}
	/**
	 * Closes the connection to the RabbitMQ server
	 */
	function close()
	{
		// close the channel and the connection
		// http://php-amqplib.github.io/php-amqplib/classes/PhpAmqpLib-Channel-AMQPChannel.html#method_close
		$this->channel->close();
		$this->connection->close();
	}
}