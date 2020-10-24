<?php

use Ds\Set;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\MessageComponentInterface;
use Ratchet\WebSocket\WsServer;

require_once 'vendor/autoload.php';

$chatComponent = new class implements MessageComponentInterface {
    private Set $connections;

    public function __construct()
    {
        $this->connections = new Set();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        echo "Nova conexão aceita" . PHP_EOL;
        $this->connections->add($conn);
    }

    public function onClose(ConnectionInterface $conn)
    {
        echo "Conexão encerrada" . PHP_EOL;
        $this->connections->remove($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Erro: " . $e->getTraceAsString();
    }

    public function onMessage(ConnectionInterface $from, MessageInterface $msg)
    {
        /** @var ConnectionInterface $connection */
        foreach ($this->connections as $connection) {
            if ($connection !== $from) {
                $connection->send((string) $msg);
            }
        }
    }
};

$server = IoServer::factory(
    new HttpServer(
        new WsServer($chatComponent)
    ),
    8002
);

$server->run();
