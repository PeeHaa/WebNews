<?php declare(strict_types=1);

namespace WebNews\Presentation\Controller;

use CodeCollab\Http\Response\Response;
use WebNews\Presentation\Template\Html;
use WebNews\Storage\Postgres\Group as GroupStorage;
use WebNews\Storage\Postgres\Thread as ThreadStorage;
use CodeCollab\Http\Response\StatusCode;

class Thread
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function showMessages(
        Html $template,
        GroupStorage $group,
        ThreadStorage $thread,
        string $selectedGroup,
        string $threadId
    ): Response
    {
        if (!$group->exists($selectedGroup) || !$thread->exists((int) $threadId)) {
            $this->response->setContent($template->renderPage('/error/not-found.phtml'));
            $this->response->setStatusCode(StatusCode::NOT_FOUND);

            return $this->response;
        }

        $this->response->setContent($template->renderPage('/messages.phtml', [
            'group'    => $selectedGroup,
            'thread'   => $thread->getInfo((int) $threadId),
            'messages' => $thread->getMessages((int) $threadId),
        ]));

        return $this->response;
    }
}
