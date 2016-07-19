<?php declare(strict_types=1);

namespace PeeHaa\Nntp\Endpoint;

class Tls extends Endpoint
{
    public function __construct(string $hostname, int $port = 563)
    {
        parent::__construct($hostname, $port);
    }

    public function getUri(): string
    {
        return sprintf('tls://%s:%s', $this->hostname, $this->port);
    }
}
