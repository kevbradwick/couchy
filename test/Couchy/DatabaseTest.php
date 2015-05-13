<?php

namespace Couchy;

class DatabaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Curl|\PHPUnit_Framework_MockObject_MockObject
     */
    private $curl;

    public function setUp()
    {
        $mock = $this->getMockBuilder('Couchy\Curl');
        $mock->setMethods(['get', 'put', 'post', 'delete']);
        $this->curl = $mock->getMock();

        $this->client = new Client();
        $this->client->setCurlClient($this->curl);
    }

    public function testCreateUsesCorrectUrl()
    {
        $expectedUrl = 'http://127.0.0.1:5984/test';

        $this->curl->expects($this->once())->method('put')->with($expectedUrl);

        $db = new Database('test', $this->client);
        $db->create();
    }

    public function testExistsRetrnsFalse()
    {
        $client = $this->getMock('Couchy\Client', ['listDatabases']);
        $client->expects($this->once())->method('listDatabases')->willReturn([]);

        $database = new Database('test', $client);
        $this->assertFalse($database->exists());
    }

    public function testExistsRetrnsTrue()
    {
        $client = $this->getMock('Couchy\Client', ['listDatabases']);
        $client->expects($this->once())->method('listDatabases')->willReturn(['test']);

        $database = new Database('test', $client);
        $this->assertTrue($database->exists());
    }
}
