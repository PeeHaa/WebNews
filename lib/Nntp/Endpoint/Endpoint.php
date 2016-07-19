<?php declare(strict_types=1);

namespace PeeHaa\Nntp\Endpoint;

abstract class Endpoint
{
    protected $hostname;

    protected $port;

    public function __construct(string $hostname, int $port)
    {
        $this->hostname = $hostname;
        $this->port     = $port;
    }

    abstract public function getUri(): string;
}
