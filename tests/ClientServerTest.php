<?php

namespace Eschrade\AsyncPack\Test;

use Eschrade\AsyncPack\Codec;
use Eschrade\AsyncPack\NeedsResponseQueueInterface;
use Eschrade\AsyncPack\QueueServer;

class ClientServerTest extends \PHPUnit_Framework_TestCase
{

    public function testHandle()
    {
        $codec = new Codec();
        $data = [
            'callback'  => 'Eschrade\AsyncPack\Test\ExecuteObject::execute',
            'payload'    => [
                'param' => 1
            ]
        ];
        $mockStream = $this->getMock('Psr\Http\Message\StreamInterface');
        $mockStream->method('getContents')->willReturn($codec->encode($data));

        $mockRequest = $this->getMock('Psr\Http\Message\RequestInterface');
        $mockRequest->method('getBody')->willReturn($mockStream);

        $server = new QueueServer($mockRequest);
        $server->handle();
        $object = $server->getContainer()->get('Eschrade\AsyncPack\Test\ExecuteObject');
        $this->assertEquals('executed', $object->param);
    }

    public function testHandleMissingParamThrowsException()
    {
        $this->setExpectedException('Eschrade\AsyncPack\MissingParameterException');
        $codec = new Codec();
        $data = [
            'callback'  => 'Eschrade\AsyncPack\Test\ExecuteObject::execute',
            'payload'    => [
            ]
        ];
        $mockStream = $this->getMock('Psr\Http\Message\StreamInterface');
        $mockStream->method('getContents')->willReturn($codec->encode($data));

        $mockRequest = $this->getMock('Psr\Http\Message\RequestInterface');
        $mockRequest->method('getBody')->willReturn($mockStream);

        $server = new QueueServer($mockRequest);
        $server->handle();
    }


}


class ExecuteObject {

    public $param;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function execute()
    {
        if ($this->param == 1) {
            $this->param = 'executed';
        }
    }

}
