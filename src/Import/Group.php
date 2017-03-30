<?php declare(strict_types=1);

namespace WebNews\Import;

use PeeHaa\Nntp\Client;
use PeeHaa\Nntp\Command\ListCommand;
use PeeHaa\Nntp\Result\ListGroupCollection;
use WebNews\Storage\Postgres\Group as Storage;

class Group
{
    private $client;

    private $storage;

    public function __construct(Client $client, Storage $storage)
    {
        $this->client  = $client;
        $this->storage = $storage;
    }

    public function import()
    {
        $response = $this->client->sendCommand(new ListCommand());

        $results  = new ListGroupCollection($response->getData());

        foreach ($results as $result) {
            $this->storage->upsert($result);
        }
    }
}
