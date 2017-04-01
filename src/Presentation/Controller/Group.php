<?php declare(strict_types=1);

namespace WebNews\Presentation\Controller;

use CodeCollab\Http\Response\Response;
use WebNews\Presentation\Template\Html;
use WebNews\Storage\Postgres\Group as GroupStorage;
use WebNews\Storage\Postgres\Thread;
use CodeCollab\Http\Response\StatusCode;

class Group
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function showThreads(Html $template, GroupStorage $group, Thread $thread, string $selectedGroup): Response
    {
        if (!$group->exists($selectedGroup)) {
            $this->response->setContent($template->renderPage('/error/not-found.phtml'));
            $this->response->setStatusCode(StatusCode::NOT_FOUND);

            return $this->response;
        }

        $this->response->setContent($template->renderPage('/threads.phtml', [
            'group'   => $selectedGroup,
            'threads' => $thread->getThreadsByGroup($selectedGroup),
        ]));

        return $this->response;
    }
}
