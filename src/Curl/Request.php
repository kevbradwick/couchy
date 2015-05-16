<?php

namespace Couchy\Curl;

class Request
{
    const HTTP_GET = 'GET';
    const HTTP_POST = 'POST';
    const HTTP_PUT = 'PUT';
    const HTTP_DELETE = 'DELETE';

    /**
     * @var string|\CURLFile
     */
    private $body = null;

    /**
     * @var string
     */
    private $method = self::HTTP_GET;

    /**
     * @var array
     */
    private $headers = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        'Accept-Encoding' => 'Accept-Encoding: gzip, deflate, sdch',
    ];

    /**
     * @var string
     */
    private $url = null;

    public function __construct($url, $method = null, $body = null)
    {
        $this->setUrl($url);
        $this->setMethod($method);
        $this->setBody($body);
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    /**
     * Merge an array of headers.
     *
     * @param array $headers
     */
    public function mergeHeaders(array $headers)
    {
        foreach ($headers as $name => $value) {
            $this->setHeader($name, $value);
        }
    }

    /**
     * Converts the associative headers array into one which curl needs for
     * sending.
     *
     * @return array
     */
    private function getHeaders()
    {
        $headers = [];

        foreach ($this->headers as $name => $value) {
            $headers[] = sprintf('%s: %s', $name, $value);
        }

        return $headers;
    }

    /**
     * @param array|\CURLFile $body
     *
     * @return Request
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @param string $method
     *
     * @return Request
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param string $url
     *
     * @return Request
     */
    public function setUrl($url)
    {
        $this->url = strval($url);

        return $this;
    }

    public function send()
    {
        $ch = curl_init($this->url);

        if ($this->body instanceof \CURLFile) {
            $this->setHeader('Content-Type', $this->body->mime);
            curl_setopt($ch, \CURLOPT_POSTFIELDS, ['file' => $this->body]);
        } elseif ($this->body !== null) {
            $body = json_encode($this->body);
            curl_setopt($ch, \CURLOPT_POSTFIELDS, $body);
        }

        curl_setopt($ch, \CURLOPT_HEADER, true);
        curl_setopt($ch, \CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($ch, \CURLOPT_HTTPHEADER, $this->getHeaders());
        curl_setopt($ch, \CURLOPT_HEADER, true);
        curl_setopt($ch, \CURLINFO_HEADER_OUT, true);

        $output = curl_exec($ch);

        $response = new Response($ch, $output);

        curl_close($ch);

        return $response;
    }
}
