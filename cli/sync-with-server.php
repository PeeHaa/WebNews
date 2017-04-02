#!/usr/bin/env php
<?php declare(strict_types=1);

namespace WebNews\Cli;

use PeeHaa\Nntp\Connection\Connection;
use PeeHaa\Nntp\Endpoint\Plain;
use PeeHaa\Nntp\Client;
use WebNews\Import\Article as ArticleImport;
use WebNews\Import\Group as GroupImport;
use WebNews\Storage\Postgres\Group as GroupStorage;
use WebNews\Storage\Postgres\Thread;
use WebNews\Storage\Postgres\Article;

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
