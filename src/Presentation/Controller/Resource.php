<?php declare(strict_types=1);

namespace WebNews\Presentation\Controller;

use CodeCollab\Http\Response\Response;
use WebNews\Presentation\Template\Stylesheet;
use WebNews\Presentation\Template\Javascript;
use WebNews\Presentation\Template\WebFont;

class Resource
{
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function renderStylesheet(Stylesheet $template, string $filename): Response
    {
        $this->response->setContent($template->render('/resources/css/' . $filename));
        $this->response->addHeader('Content-Type', 'text/css');

        return $this->response;
    }

    public function renderJavascript(Javascript $template, string $filename): Response
    {
        $this->response->setContent($template->render('/resources/js/' . $filename));
        $this->response->addHeader('Content-Type', 'application/javascript');

        return $this->response;
    }

    public function renderFont(WebFont $template, string $filename): Response
    {
        $this->response->setContent($template->render('/resources/fonts/' . $filename));
        $this->response->addHeader('Content-Type', $template->getMimeType());

        return $this->response;
    }
}
