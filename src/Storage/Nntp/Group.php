<?php declare(strict_types=1);

namespace WebNews\Storage\Nntp;

use PeeHaa\Nntp\Client;
use PeeHaa\Nntp\Command\ListCommand;
use WebNews\Nntp\Groups;

class Group
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getAll(): Groups
    {
        $response = $this->client->sendCommand(new ListCommand());

        return new Groups($response->getData());
    }
}
