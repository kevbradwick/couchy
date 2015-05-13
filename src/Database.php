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

    /**
     * Check to see if the database already exists.
     *
     * @return bool
     */
    public function exists()
    {
        return in_array($this->name, $this->client->listDatabases());
    }

    /**
     * Creates the database.
     *
     * @return \stdClass
     */
    public function create()
    {
        $curl = $this->client->getCurlClient();
        return $curl->put($this->getDatabaseUrl());
    }

    /**
     * Inserts a new document.
     *
     * @param array $data
     * @param bool $returnDocument if true, you will get an instance of Document
     *
     * @return Document|array
     */
    public function insert(array $data, $returnDocument = false)
    {
        $curl = $this->client->getCurlClient();
        $output = $curl->post($this->getDatabaseUrl(), [], $data);

        if ($returnDocument === true) {
            return $this->getDocumentById($output->id);
        }

        return [
            'id' => $output->id,
            'rev' => $output->rev,
        ];
    }

    /**
     * @param string $id
     */
    public function getDocumentById($id)
    {
        $url = sprintf('%s/%s', $this->getDatabaseUrl(), $id);
        $output = $this->client->getCurlClient()->get($url);

        return new Document($output);
    }

    /**
     * @return string
     */
    public function getDatabaseUrl()
    {
        return sprintf('%s/%s', $this->baseUrl, $this->name);
    }
}
