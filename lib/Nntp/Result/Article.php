<?php declare(strict_types=1);

namespace PeeHaa\Nntp\Result;

use PeeHaa\Nntp\Encoding\Converter;

class Article
{
    private $converter;

    private $headers = [];

    private $body = '';

    public function __construct(Converter $converter, array $data)
    {
        $this->converter = $converter;

        while ($header = array_shift($data)) {
            if ($header === '') {
                break;
            }

            $this->addHeader($header);
        }

        $this->body = $this->converter->convert(implode("\n", $data));
    }

    private function addHeader(string $header)
    {
        $this->headers[] = $this->converter->convert($header);
    }

    public function getHeaders(): string
    {
        return implode("\n", $this->headers);
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
