<?php
namespace DMZ;

require_once(__DIR__ . "/DMZConfig.php");

use DMZ\DMZConfig;

class Curl
{
    private $user_agent;
    private $key;
    private $secret;
    private $ch;

    /**
     * Curl constructor.
     *  pulls variables from the .env file
     */
    public function __construct()
    {
        $config = new DMZConfig();
        $this->user_agent = $config->user_agent;
        $this->key = $config->key;
        $this->secret = $config->secret;
        $this->ch = curl_init();
    }

    /**
     * Set the options for the curl request
     * @param string $url
     */
    public function set_options($url, $query_params)
    {
        $url .= '?' . http_build_query($query_params);

        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, [
            "User-Agent: {$this->user_agent}",
            "Authorization: Discogs key={$this->key}, secret={$this->secret}",
        ]);

        // Add the CA certificate bundle for SSL verification
        curl_setopt($this->ch, CURLOPT_CAINFO, __DIR__ . '/certs/cacert.pem');
    }

    /**
     * Execute the curl request
     * @return mixed
     * @throws \Exception
     */
    public function execute()
    {
        $response = curl_exec($this->ch);

        if (curl_errno($this->ch)) {
            throw new \Exception('Error: ' . curl_error($this->ch));
        }

        return json_decode($response, true);
    }

    public function close()
    {
        curl_close($this->ch);
    }

    /**
     * Perform a search request to Discogs API
     * @param string $searchTerm
     * @param string $format
     * @param string $genre
     * @param int $page
     * @return mixed
     * @throws \Exception
     */
    public function search($searchTerm, $format = "Vinyl", $genre = null, $page = 1)
    {
        $base_url = "https://api.discogs.com/database/search";

        // Initialize query parameters array
        $query_params = [
            'q' => $searchTerm,
            'type' => 'master',
            'format' => $format,
            'per_page' => 12,
            'page' => $page,
        ];

        // Add genre if set
        if (!empty($genre)) {
            $query_params['genre'] = $genre;
        }

        // Set options and execute the request
        $this->set_options($base_url, $query_params);
        return $this->execute();
    }
}
