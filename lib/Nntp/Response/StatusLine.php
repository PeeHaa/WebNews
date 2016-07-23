<?php declare(strict_types=1);

namespace PeeHaa\Nntp\Response;

class StatusLine
{
    private $statusCode;

    private $message;

    public function __construct(string $response)
    {
        if (preg_match('~^(\d{3}) (.*)$~', $response, $matches) !== 1) {
            throw new InvalidResponseException('Could not parse the response');
        }

        $this->statusCode = (int) $matches[1];
        $this->message    = $matches[2];
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
