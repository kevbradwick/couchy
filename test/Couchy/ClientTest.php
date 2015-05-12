<?php


namespace Couchy;


class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getBaseUrlDataProvider
     */
    public function testGetBaseUrl($host, $port, $ssl, $expectedBaseUrl)
    {
        $client = new Client($host, $port, $ssl);
        $this->assertEquals($expectedBaseUrl, $client->getBaseUrl());
    }

    public function getBaseUrlDataProvider()
    {
        return [
            ['localhost', '5434', false, 'http://localhost:5434'],
            ['127.0.0.1', null, true, 'https://127.0.0.1:5984'],
        ];
    }
}