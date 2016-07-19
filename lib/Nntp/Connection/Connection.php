<?php declare(strict_types=1);

namespace PeeHaa\Nntp\Connection;

use PeeHaa\Nntp\Endpoint\Endpoint;
use PeeHaa\Nntp\Command\Command;

class Connection
{
    private $stream;

    public function __construct(Endpoint $endpoint, int $timeout = 15)
    {
        $this->stream = stream_socket_client($endpoint->getUri(), $errorNumber, $errorString, $timeout);

        if (!$this->stream) {
            throw new ConnectionFailedException($errorString, $errorNumber);
        }

        $greetingMessage = trim($this->readLine());

        if (preg_match('~^(20\d) (.*)$~', $greetingMessage, $errorInformation) !== 1) {
            preg_match('~^(\d{3}) (.*)$~', $greetingMessage, $errorInformation);

            throw new ConnectionFailedException($errorInformation[2], (int) $errorInformation[1]);
        }
    }

    public function readLine(): string
    {
        if (!isset($this->stream)) {
            throw new NotConnectedException();
        }

        if (feof($this->stream)) {
            throw new ConnectionClosedException();
        }

        return fgets($this->stream);
    }

    public function sendCommand(Command $command)
    {
        if (fwrite($this->stream, $command->getCommand()) !== $command->getLength()) {
            throw new TransferFailedException(sprintf('Unable to send command %s.'));
        }
    }

    public function getResponse(): array
    {
        $response = trim($this->readLine());

        if ($response === false) {
            throw new TransferFailedException(sprintf('Unable to retrieve response.'));
        }

        return preg_split('/\s+/', $response, 2);
    }
}
