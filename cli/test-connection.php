<?php declare(strict_types=1);

namespace WebNews\Cli;

use PeeHaa\Nntp\Connection\Connection;
use PeeHaa\Nntp\Endpoint\Plain;
use PeeHaa\Nntp\Client;
use PeeHaa\Nntp\Command\ListCommand;
use PeeHaa\Nntp\Command\Group;
use PeeHaa\Nntp\Command\XOver;

require_once __DIR__ . '/../bootstrap.php';

$endpoint   = new Plain('news.php.net');
$connection = new Connection($endpoint);
$client     = new Client($connection);

$client->sendCommand(new ListCommand());
$client->sendCommand(new Group('php.announce'));

$threads = $client->sendCommand(new XOver(1, 200));

var_dump($threads->getData());
