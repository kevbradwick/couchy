<?php

namespace Couchy;

use Couchy\Curl\Request;
use Couchy\Curl\Response;

class Curl
{
    const HTTP_GET = Curl\Request::HTTP_GET;
    const HTTP_POST = Curl\Request::HTTP_POST;
    const HTTP_PUT = Curl\Request::HTTP_PUT;
    const HTTP_DELETE = Curl\Request::HTTP_DELETE;

    /**
     * @param string $url
     * @param array $headers
     * @return Response
     */
    public function get($url, array $headers = [])
    {
        $request = new Request($url, self::HTTP_GET);
        $request->mergeHeaders($headers);

        return $request->send();
    }

    /**
     * @param string $url
     * @param array|\CURLFile $body
     * @param array $headers
     *
     * @return Response
     */
    public function put($url, $body = null, array $headers = [])
    {
        $request = new Request($url, self::HTTP_PUT, $body);
        $request->mergeHeaders($headers);

        return $request->send();
    }

    /**
     * @param string $url
     * @param null $body
     * @param array $headers
     *
     * @return Response
     */
    public function post($url, $body = null, array $headers = [])
    {
        $request = new Request($url, self::HTTP_POST, $body);
        $request->mergeHeaders($headers);

        return $request->send();
    }

    /**
     * @param string $url
     * @param null $body
     * @param array $headers
     *
     * @return Response
     */
    public function delete($url, $body = null, array $headers = [])
    {
        $request = new Request($url, self::HTTP_DELETE, $body);
        $request->mergeHeaders($headers);

        return $request->send();
    }
}
