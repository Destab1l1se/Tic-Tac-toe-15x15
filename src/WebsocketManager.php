<?php

namespace App;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebsocketManager implements MessageComponentInterface
{
    protected $playersManager;

    public function __construct()
    {
        $this->playersManager = new PlayersManager();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->playersManager->addPlayer($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $this->playersManager->sendData($from, $msg);
    }

    public function onClose(ConnectionInterface $conn)
    {
//        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
    }
}
