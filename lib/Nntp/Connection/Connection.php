<?php declare(strict_types=1);

namespace PeeHaa\Nntp\Connection;

use PeeHaa\Nntp\Endpoint\Endpoint;

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

    public function send(string $command)
    {
        while ($this->hasPendingData()) {
            $this->readLine();
        }

        if (fwrite($this->stream, $command) !== strlen($command)) {
            throw new TransferFailedException(sprintf('Unable to send command %s.'));
        }
    }

    private function hasPendingData(): bool
    {
        $r = array($this->stream);

        $w = $e = null;
        return (bool) stream_select($r, $w, $e, 0);
    }
}
