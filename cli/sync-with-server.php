<?php declare(strict_types=1);

namespace WebNews\Cli;

use PeeHaa\Nntp\Connection\Connection;
use PeeHaa\Nntp\Endpoint\Plain;
use PeeHaa\Nntp\Client;
use WebNews\Import\Article as ArticleImport;
use WebNews\Import\Group as GroupImport;
use WebNews\Storage\Postgres\Group as GroupStorage;
use PeeHaa\Nntp\Command\Group as GroupCommand;
use PeeHaa\Nntp\Command\XOver;
use PeeHaa\Nntp\Result\XOverArticleCollection;
use WebNews\Storage\Postgres\Thread;
use WebNews\Storage\Postgres\Article;
use PeeHaa\Nntp\Encoding\Utf8;
use PeeHaa\Nntp\Result\InvalidResultException;
use PeeHaa\Nntp\Command\Article as ArticleCommand;
use PeeHaa\Nntp\Result\Article as ArticleContentResult;

require_once __DIR__ . '/../bootstrap.php';

$endpoint   = new Plain('news.php.net');
$connection = new Connection($endpoint);
$client     = new Client($connection);

/**
 * Update groups
 */
/** @var \Auryn\Injector $auryn */
$groupStorage = $auryn->make(GroupStorage::class);

$groups = (new GroupImport($client, $groupStorage))->import();

/**
 * Import articles
 */
$threadStorage  = $auryn->make(Thread::class);
$articleStorage = $auryn->make(Article::class);

(new ArticleImport($client, $threadStorage, $articleStorage))->import($groups);

exit;

/**
 * Create threads
 */
function createThreads($articleResult, $articleContentResult, $threadStorage, $listResult, $articleStorage) {
    if ($articleResult->startsNewThread()) {
        $threadId = $threadStorage->create($listResult, $articleResult);
    } else {
        $threadId = $threadStorage->findByReference($articleResult);

        // could not find the reference?
        if ($threadId === 0) {
            $threadId = $threadStorage->create($listResult, $articleResult);
        }
    }

    $articleStorage->create($threadId, $articleResult, $articleContentResult);
}

/**
 * Do stuff
 */
$threadStorage  = $auryn->make(Thread::class);
$articleStorage = $auryn->make(Article::class);
$converter      = new Utf8();

foreach ($groups as $groupIndex => $listResult) {
    if ($groupIndex < 3) {
        //continue;
    }
//var_dump($listResult->getName());
    $client->sendCommand(new GroupCommand($listResult->getName()));

    $start = $listResult->getLowWatermark();

    do {
        if ($start < 34401) {
            //$start += 200;

            //continue;
        }

        sleep(10);

        echo "Retrieving article $start to " . ($start + 200) . " of group {$listResult->getName()} with index $groupIndex" . PHP_EOL;

        $xOverResult = $client->sendCommand(new XOver($start, $start + 200));

        try {
            $articleResults = new XOverArticleCollection($converter, $xOverResult->getData());
        } catch (InvalidResultException $e) {
            var_dump('ERROR: Message could not be parsed.');
        }

        foreach ($articleResults as $articleResult) {
            if ($threadStorage->articleExists($articleResult)) {
                continue;
            }

            try {
                var_dump($articleResult->getWatermark() . ' :: ' . $articleResult->getSubject());

                $article = $client->sendCommand(new ArticleCommand($articleResult->getWatermark()));
                $articleContentResult = new ArticleContentResult($converter, $article->getData());

                createThreads($articleResult, $articleContentResult, $threadStorage, $listResult, $articleStorage);
            } catch(\Throwable $e) {
                var_dump($articleResult);

                throw $e;
            }
        }

        $start += 200;
    } while($start <= $listResult->getHighWatermark());
}
