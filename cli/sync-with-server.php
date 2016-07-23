<?php declare(strict_types=1);

namespace WebNews\Cli;

use PeeHaa\Nntp\Connection\Connection;
use PeeHaa\Nntp\Endpoint\Plain;
use PeeHaa\Nntp\Client;
use PeeHaa\Nntp\Command\ListCommand;
use PeeHaa\Nntp\Result\ListGroupCollection;
use WebNews\Storage\Postgres\Group;
use PeeHaa\Nntp\Command\Group as GroupCommand;
use PeeHaa\Nntp\Command\XOver;
use PeeHaa\Nntp\Result\XOverArticleCollection;
use WebNews\Storage\Postgres\Thread;
use WebNews\Storage\Postgres\Article;

require_once __DIR__ . '/../bootstrap.php';

$endpoint   = new Plain('news.php.net');
$connection = new Connection($endpoint);
$client     = new Client($connection);

/**
 * Update groups
 */
$listResponse = $client->sendCommand(new ListCommand());
$listResults  = new ListGroupCollection($listResponse->getData());
/** @var \Auryn\Injector $auryn */
$groupStorage = $auryn->make(Group::class);

foreach ($listResults as $listResult) {
    $groupStorage->upsert($listResult);
}

/**
 * Do stuff
 */
$threadStorage  = $auryn->make(Thread::class);
$articleStorage = $auryn->make(Article::class);

foreach ($listResults as $groupIndex => $listResult) {
    if ($groupIndex > 0) {
        break;
    }

    $client->sendCommand(new GroupCommand($listResult->getName()));

    $xOverResult    = $client->sendCommand(new XOver(1, 200));
    $articleResults = new XOverArticleCollection($xOverResult->getData());

    foreach ($articleResults as $articleResult) {
        //var_dump($articleResult);
        if ($articleResult->startsNewThread()) {
            $threadId = $threadStorage->create($listResult, $articleResult);
        } else {
            $threadId = $threadStorage->findByReference($articleResult);

            // could not find the reference?
            if ($threadId === 0) {
                $threadId = $threadStorage->create($listResult, $articleResult);
            }
        }

        $articleStorage->create($threadId, $articleResult);
    }
}
