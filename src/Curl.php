<?php

namespace Couchy;


class Curl
{
    const HTTP_GET = 'GET';
    const HTTP_POST = 'POST';
    const HTTP_PUT = 'PUT';
    const HTTP_DELETE = 'DELETE';

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var array
     */
    private $headers = [];

    /**
     * @var string
     */
    private $body = '';

    /**
     * @param string $url
     * @param array $params
     * @param array $headers
     * @return mixed
     */
    public function get($url, array $params = [], array $headers = [])
    {
        return $this->doRequest(self::HTTP_GET, $url, $params, $headers);
    }

    /**
     * @param string $url
     * @param array $params
     * @return mixed
     */
    public function put($url, array $params = [])
    {
        return $this->doRequest(self::HTTP_PUT, $url);
    }

    /**
     * @param string $url
     * @return resource
     */
    private function init($url)
    {
        return curl_init($url);
    }

    /**
     * Make a HTTP request.
     *
     * @param string $method
     * @param string $url
     * @param string $body
     * @param array $params
     * @param array $headers
     * @return mixed
     */
    private function doRequest($method, $url, $body = null, array $params = [],
                               array $headers = [])
    {
        $this->reset();

        if (count($params) > 0) {
            $url .= '?' . http_build_query($params);
        }

        $ch = $this->init($url);

        $headers = array_replace_recursive([
            'Content-Type: application/json',
            'Accept: */*',
            'Accept-Encoding: gzip, deflate, sdch',
        ], $headers);

        curl_setopt($ch, \CURLOPT_HEADER, true);
        curl_setopt($ch, \CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, \CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, \CURLOPT_HTTPHEADER, $headers);

        $output = $this->exec($ch);

        preg_match('/HTTP\/\d\.\d\s(?P<code>\d{3})/', $output, $matches);

        // this should never fail but if it does, it probably should do more
        // than throw an exception
        if (!isset($matches['code'])) {
            throw new \RuntimeException('Unable to parse response');
        }

        $this->statusCode = (int) $matches['code'];

        list($headers_, $body_) = preg_split('/\R\R/', $output, 2);

        foreach (preg_split('/\R/', $headers_) as $h) {
            preg_match('/^(?P<name>[a-z\-]+):\s+(?P<value>.*)$/i', $h, $matches);
            if (isset($matches['name']) && isset($matches['value'])) {
                $this->headers[$matches['name']] = $matches['value'];
            }
        }

        $this->body = trim($body_);

        if (strlen($this->body) > 0) {
            return json_decode($this->body);
        }

        return '';
    }

    /**
     * @param resource $ch
     * @return mixed
     */
    private function exec($ch)
    {
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    /**
     * Reset the response values.
     */
    public function reset()
    {
        $this->statusCode = null;
        $this->headers = [];
        $this->body = '';
    }
}