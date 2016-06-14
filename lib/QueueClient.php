<?php

namespace Eschrade\AsyncPack;


class QueueClient
{
    protected $client;
    protected $connection;
    protected $queue;
    protected $username;
    protected $password;
    protected $codec;

    public function __construct($connection = 'tcp://localhost:61613', $queue = 'worker', $username = '', $password = '', Codec $codec = null)
    {
        $this->connection = $connection;
        $this->queue = $queue;
        $this->username = $username;
        $this->password = $password;
        $this->codec = $codec;
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

    /**
     * Retrieve the Stomp client that the queue is using.
     *
     * @return Stomp
     * @throws \FuseSource\Stomp\Exception\StompException
     */

    public function getClient()
    {
        if (!$this->client instanceof Stomp) {
            $this->client = new Stomp($this->connection);
            $this->client->connect($this->username, $this->password);
        }
        return $this->client;
    }

    /**
     * @param $callback string The callback in the form of "class::method".  Functions are not allowed.  If you are using your own IoC container you can specify aliases for the class.
     * @param $payload mixed Any serializable content
     */

    public function enqueue($callback, $payload)
    {
        $data = [
            'callback'  => $callback,
            'payload'   => $payload
        ];
        $encoded = $this->getCodec()->encode($data);
        $this->getClient()->send('/queue/' . $this->queue, $encoded);
    }

}