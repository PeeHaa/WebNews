<?php declare(strict_types=1);

namespace WebNews\Presentation\Controller;

use CodeCollab\Http\Response\Response;
use WebNews\Presentation\Template\Html;
use WebNews\Storage\Nntp\Group as GroupStorage;

class Group
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function showThreads(Html $template, GroupStorage $group, string $selectedGroup)
    {
        $this->response->setContent($template->renderPage('/threads.phtml', [
            'group'  => $group->getGroup($selectedGroup),
            'groups' => [],
        ]));

        return $this->response;
    }
}
