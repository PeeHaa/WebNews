<?php declare(strict_types=1);

namespace PeeHaa\Nntp\Response;

class Response
{
    private $statusLine;

    private $data;

    public function __construct(StatusLine $statusLine, array $data)
    {
        $this->statusLine = $statusLine;
        $this->data       = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
