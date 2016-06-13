<?php

namespace Eschrade\AsyncPack;

use Interop\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;

class QueueServer
{

    protected $container;
    protected $request;
    protected $codec;

    public function __construct(RequestInterface $request, ContainerInterface $container = null, Codec $codec = null)
    {
        $this->container = $container;
        $this->request = $request;
        $this->code = $codec;
    }


    public function setCodec(Codec $codec)
    {
        $this->codec = $codec;
    }

    /**
     * @return Codec
     */

    public function getCodec()
    {
        if (!$this->codec instanceof Codec) {
            $this->codec = new Codec();
        }
        return $this->codec;
    }

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return ReflectionDi|ContainerInterface
     */

    public function getContainer()
    {
        if (!$this->container instanceof ContainerInterface) {
            $this->container = new ReflectionDi();
        }
        return $this->container;
    }

    public function handle()
    {
        $contents = $this->request->getBody()->getContents();
        $data = $this->getCodec()->decode($contents);
        $params = [];

        if (isset($data['payload'])) {
            $params = $data['payload'];
        }
        if (isset($data['callback'])) {
            $callback = explode('::', $data['callback']);
            $object = $this->getContainer()->get($callback[0], $params);
            $object->{$callback[1]}();
        }
    }

}