<?php declare(strict_types=1);

namespace WebNews\Presentation\Controller;

use CodeCollab\Http\Response\Response;
use WebNews\Presentation\Template\Html;

class Index
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function index(Html $template)
    {
        $this->response->setContent($template->renderPage('/index.phtml'));

        return $this->response;
    }
}
