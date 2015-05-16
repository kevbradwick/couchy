<?php

namespace Couchy\Curl;

/**
 * Class Response.
 *
 * @package Couchy\Curl
 */
class Response
{
    /**
     * @var int
     */
    private $code;

    /**
     * @var int
     */
    private $totalTime;

    /**
     * @var string
     */
    private $body;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @param resource $ch the curl handle resource
     * @param string $response the raw response from curl exec
     */
    public function __construct($ch, $response)
    {
        $this->code = curl_getinfo($ch, \CURLINFO_HTTP_CODE);
        $this->totalTime = curl_getinfo($ch, \CURLINFO_TOTAL_TIME);

        // headers
        $headerSize = curl_getinfo($ch, \CURLINFO_HEADER_SIZE);
        $headers = trim(substr($response, 0, $headerSize));
        $headers = array_filter(preg_split('/\R/', $headers), 'strlen');

        foreach ($headers as $header) {
            preg_match('/^(?P<name>[\w\-]+):\s+(?P<value>.*)$/', $header, $matches);
            if (isset($matches['name']) && isset($matches['value'])) {
                $this->headers[$matches['name']] = $matches['value'];
            }
        }

        // response body
        $this->body = trim(substr($response, $headerSize));
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return mixed
     */
    public function getJsonBody()
    {
        return json_decode($this->body);
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->code;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Get a response header or return a default of not found.
     *
     * @param string $name
     * @param mixed $default
     * @return null
     */
    public function getHeader($name, $default = null)
    {
        if (isset($this->headers[$name])) {
            return $this->headers[$name];
        }

        return $default;
    }

    /**
     * @return int
     */
    public function getTotalTime()
    {
        return $this->totalTime;
    }
}
