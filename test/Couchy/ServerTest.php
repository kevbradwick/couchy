<?php

namespace Couchy;

class ServerTest extends \PHPUnit_Framework_TestCase
{
    public function testToArray()
    {
        $expected = [
            'version' => '1.0.0',
            'uuid' => 'foobar',
            'vendor' => [
                'version' => 1.2,
                'name' => 'foobar',
            ],
        ];

        $obj = json_decode(json_encode($expected));

        $server = new Server($obj);

        $this->assertSame($expected, $server->toArray());
    }
}
