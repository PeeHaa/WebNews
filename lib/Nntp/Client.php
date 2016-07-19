<?php declare(strict_types=1);

namespace PeeHaa\Nntp;

use PeeHaa\Nntp\Connection\Connection;
use PeeHaa\Nntp\Command\Command;

class Client
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function sendCommand(Command $command)
    {
        $this->connection->sendCommand($command);

        var_dump($this->connection->getResponse());
    }
}
