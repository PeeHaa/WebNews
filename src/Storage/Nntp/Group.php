<?php declare(strict_types=1);

namespace WebNews\Storage\Nntp;

use PeeHaa\Nntp\Client;
use PeeHaa\Nntp\Command\ListCommand;

class Group
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getAll(): array
    {
        $response = $this->client->sendCommand(new ListCommand());

        return $response->getData();
    }
}