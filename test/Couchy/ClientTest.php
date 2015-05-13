<?php


namespace Couchy;

class ClientTest extends \PHPUnit_Framework_TestCase
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

    public function testGetServerInfoCallsBaseUrl()
    {
        $expectedUrl = 'http://127.0.0.1:5984';

        $this->curl->expects($this->once())
            ->method('get')
            ->with($expectedUrl)
            ->willReturn(new \stdClass());

        $this->client->getServerInfo();
    }

    public function testListDatabasesCallsCorrectUrl()
    {
        $expectedUrl = 'http://127.0.0.1:5984/_all_dbs';

        $this->curl->expects($this->once())
            ->method('get')
            ->with($expectedUrl)
            ->willReturn(new \stdClass());

        $this->client->listDatabases();
    }

    public function testCreateDatabaseIfNotExistsCallsListDatabases()
    {
        /** @var Client|\PHPUnit_Framework_MockObject_MockObject $stub */
        $stub = $this->getMock('Couchy\Client', ['listDatabases']);
        $stub->expects($this->once())->method('listDatabases')->willReturn([]);
        $stub->setCurlClient($this->curl);

        $stub->createDatabaseIfNotExists('test');
    }

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
            [null, null, null, 'http://127.0.0.1:5984'],
            ['localhost/', null, null, 'http://localhost:5984'],
        ];
    }
}
