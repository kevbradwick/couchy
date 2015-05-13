<?php

namespace Couchy;

class Client
{
    /**
     * @var bool
     */
    private $useSsl = false;

    /**
     * @var string
     */
    private $host = '127.0.0.1';

    /**
     * @var string
     */
    private $port = '5984';

    /**
     * @var Curl
     */
    private $curl;

    /**
     * @param string $host
     * @param string $port
     * @param bool $useSsl
     */
    public function __construct($host = null, $port = null, $useSsl = null)
    {
        if ($host) {
            $this->setHost($host);
        }

        if ($port) {
            $this->setPort($port);
        }

        if ($useSsl) {
            $this->useSsl($useSsl);
        }
    }

    /**
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = (string) rtrim($host, '/');
    }

    /**
     * @param string|int $port
     */
    public function setPort($port)
    {
        $this->port = (string) $port;
    }

    /**
     * @param bool $use
     */
    public function useSsl($use)
    {
        $this->useSsl = (bool) $use;
    }

    /**
     * @param Curl $curl
     */
    public function setCurlClient(Curl $curl)
    {
        $this->curl = $curl;
    }

    /**
     * @return Curl
     */
    public function getCurlClient()
    {
        if ($this->curl instanceof Curl) {
            return $this->curl;
        }

        $this->curl = new Curl();
        return $this->curl;
    }

    /**
     * @return string the base url that all request will be made to
     */
    public function getBaseUrl()
    {
        $protocol = $this->useSsl ? 'https' : 'http';

        return sprintf('%s://%s:%s', $protocol, $this->host, $this->port);
    }

    /**
     * @return Server
     */
    public function getServerInfo()
    {
        $response = $this->getCurlClient()->get(
            $this->getBaseUrl()
        );

        return new Server($response);
    }

    /**
     * @return array
     */
    public function listDatabases()
    {
        $url = $this->getBaseUrl() . '/_all_dbs';
        $dbs = $this->getCurlClient()->get($url);

        return $dbs;
    }

    /**
     * @param string $name
     * @return Database
     */
    public function createDatabase($name)
    {
        $db = new Database($name, $this);
        $db->create();

        return $db;
    }

    /**
     * @param string $name
     * @return Database
     */
    public function createDatabaseIfNotExists($name)
    {
        if (!in_array($name, $this->listDatabases())) {
            return $this->createDatabase($name);
        }

        return new Database($name, $this);
    }
}
