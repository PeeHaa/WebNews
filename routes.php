<?php declare(strict_types=1);

namespace WebNews;

use Auryn\Injector;
use CodeCollab\Router\Router;
use WebNews\Presentation\Controller\Resource;
use WebNews\Presentation\Controller\Error;
use WebNews\Presentation\Controller\Index;

/** @var Injector $auryn */
$router = $auryn->make(Router::class);

$router
    ->get('/js/{filename:.+}', [Resource::class, 'renderJavascript'])
    ->get('/css/{filename:.+}', [Resource::class, 'renderStylesheet'])
    ->get('/fonts/{filename:.+}', [Resource::class, 'renderFont'])
    ->get('/not-found', [Error::class, 'notFound'])
    ->get('/method-not-allowed', [Error::class, 'methodNotAllowed'])
    ->get('/', [Index::class, 'index'])
;
