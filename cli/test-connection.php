<?php declare(strict_types=1);

namespace WebNews\Cli;

use PeeHaa\Nntp\Connection\Connection;
use PeeHaa\Nntp\Endpoint\Plain;
use PeeHaa\Nntp\Client;
use PeeHaa\Nntp\Command\ListCommand;

require_once __DIR__ . '/../bootstrap.php';

$endpoint   = new Plain('news.php.net');
$connection = new Connection($endpoint);
$client     = new Client($connection);

$client->sendCommand(new ListCommand());
