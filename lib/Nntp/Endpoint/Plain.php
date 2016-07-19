<?php declare(strict_types=1);

namespace PeeHaa\Nntp\Endpoint;

class Plain extends Endpoint
{
    public function __construct(string $hostname, int $port = 119)
    {
        parent::__construct($hostname, $port);
    }

    public function getUri(): string
    {
        return sprintf('tcp://%s:%s', $this->hostname, $this->port);
    }
}
