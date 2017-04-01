<?php declare(strict_types=1);

namespace WebNews\Presentation\Controller;

use CodeCollab\Http\Response\Response;
use WebNews\Presentation\Template\Html;

class Design
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function thread(Html $template): Response
    {
        $this->response->setContent($template->renderPage('/design/thread.phtml'));

        return $this->response;
    }
}
