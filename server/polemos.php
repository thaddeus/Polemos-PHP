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

    const MAP_REQUEST = 0;

    const MAP_TOPIC = 0;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);


    }

    public function onMessage(ConnectionInterface $from, $msg) {
        print("RECIEVE << " . $msg . "\n");
        $packet = json_decode($msg);
        switch ($packet->{'topic'}) {
            case self::MAP_REQUEST:
                $playerMap = new Mapper($packet->{'map'}, $packet->{'xloc'}, $packet->{'yloc'}, $packet->{'mapwidth'}, $packet->{'mapheight'});
                $message = array();
                $message['topic'] = self::MAP_TOPIC;
                $message['data'] = $playerMap->getMapObject();
                $from->send(json_encode($message));
                break;

            default:
                //Unknown packet
                break;
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
    $server = IoServer::factory(new WsServer(new PolemosServer), 47895, '0.0.0.0');
    $server->run();
?>