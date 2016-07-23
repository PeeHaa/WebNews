<?php declare(strict_types=1);

namespace PeeHaa\Nntp;

use PeeHaa\Nntp\Connection\Connection;
use PeeHaa\Nntp\Command\Command;
use PeeHaa\Nntp\Response\InvalidResponseException;
use PeeHaa\Nntp\Response\StatusLine;
use PeeHaa\Nntp\Response\Response;

class Client
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function sendCommand(Command $command): Response
    {
        $this->connection->send($command->getCommand() . "\r\n");

        $statusLine = new StatusLine($this->connection->readLine());

        if ($statusLine->getStatusCode() !== $command->getSuccessCode()) {
            throw new InvalidResponseException(sprintf(
                'Expected status code %s, but got %s instead. Textual status: %s',
                $command->getSuccessCode(),
                $statusLine->getStatusCode(),
                $statusLine->getMessage()
            ));
        }

        if ($statusLine->getStatusCode() === 211) {
            return new Response($statusLine);
        }

        return new Response($statusLine, $this->getData());
    }

    private function getData(): array
    {
        $lines = [];

        while ($line = $this->connection->readLine()) {
            if ($line === ".\r\n") {
                break;
            }

            $lines[] = trim($line);
        }

        return $lines;
    }
}
