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
