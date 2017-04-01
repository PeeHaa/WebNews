<?php declare(strict_types=1);

namespace WebNews\Presentation\Controller;

use CodeCollab\Http\Response\Response;
use CodeCollab\Http\Response\StatusCode;
use WebNews\Presentation\Template\Html;

class Error
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function notFound(Html $template): Response
    {
        $this->response->setContent($template->renderPage('/error/not-found.phtml'));
        $this->response->setStatusCode(StatusCode::NOT_FOUND);

        return $this->response;
    }

    public function methodNotAllowed(Html $template): Response
    {
        $this->response->setContent($template->renderPage('/error/not-found.phtml'));
        $this->response->setStatusCode(StatusCode::METHOD_NOT_ALLOWED);

        return $this->response;
    }
}
