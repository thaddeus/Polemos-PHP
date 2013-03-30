<?php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Polemos\Mapper As Mapper;

    require __DIR__ . '/vendor/autoload.php';
    require __DIR__ . '/PolemosClasses/Mapper.php';

/**
 * polemos.php
 * WebSocket message server for the Polemos engine
 */
class PolemosServer implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        

    }

    public function onMessage(ConnectionInterface $from, $msg) {
        foreach ($this->clients as $client) {
            $playerMap = new Mapper(0, 0, 0);
            $message = array();
            $message['topic'] = 0;
            $message['map'] = $playerMap->getMap();
            $client->send($message);
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}

    // Run the server application through the WebSocket protocol on port 47895
    $server = IoServer::factory(new WsServer(new PolemosServer), 47895, '108.59.10.218');
    $server->run();
?>