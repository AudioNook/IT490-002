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
require_once(__DIR__ . "/get_host_info.php");
use function RabbitMQ\get_host_info;
use PhpAmqpLib\Connection\AMQPStreamConnection;
//use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Exception\AMQPProtocolChannelException;

/**
 *  RabbitMQ Server class for processing messages and sending responses
 */
class RabbitMQServer
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
		$this->machine = get_host_info(array($machine));
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