<?php

require_once(__DIR__ . "/../../vendor/autoload.php");
//use Database\Config;
//use Firebase\JWT\{JWT,Key};
use RabbitMQ\RabbitMQClient;

class DMZRequests
{
    protected $rabbitMQClient;

    public function __construct()
    {
        // TODO: Make this a DMZ Specific Rabbitserver @jmpearson135
        $this->rabbitMQClient = new RabbitMQClient("rabbitMQ.ini", "jwtServer");
    }
    public function send($request)
    {
        $response = json_decode($this->rabbitMQClient->send_request($request), true);

        // Close the connection
        $this->rabbitMQClient->close();
        return $response;
    }
    public function search($search, $format = null, $genre = null, $page = null)
    {
        $request = [
            'type' => 'search',
            'searchTerm' => $search,
            'message' => 'Sending search request',
        ];
        if($format != null)
            $request['format'] = $format;
        if($genre != null)
            $request['genre'] = $genre;
        if($page != null)
            $request['page'] = $page;

        return $this->send($request);

    }

}
