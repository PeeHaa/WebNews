<?php declare(strict_types=1);

namespace WebNews\Presentation\Controller;

use CodeCollab\Http\Response\Response;
use WebNews\Presentation\Template\Html;
use WebNews\Storage\Postgres\Group;
use WebNews\Storage\Postgres\Article;

class Index
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function index(Html $template, Group $group, Article $article): Response
    {
        $this->response->setContent($template->renderPage('/index.phtml', [
            'groups' => $group->getAll(),
            'latest' => $article->getLatest(),
        ]));

        return $this->response;
    }
}
