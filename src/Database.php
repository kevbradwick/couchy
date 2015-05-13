<?php

namespace Couchy;

class Database
{
    /**
     * @var Curl
     */
    private $curl;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param string $name
     * @param Client $client
     */
    public function __construct($name, Client $client)
    {
        $this->name = $name;
        $this->client = $client;
        $this->curl = $client->getCurlClient();
        $this->baseUrl = $client->getBaseUrl();
    }

    public function exists()
    {
        return in_array($this->name, $this->client->listDatabases());
    }

    public function create()
    {
        $curl = $this->client->getCurlClient();
        return $curl->put($this->baseUrl . '/' . $this->name);
    }
}
