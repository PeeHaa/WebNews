<?php declare(strict_types=1);

namespace PeeHaa\Nntp\Result;

class Article
{
    private $headers = [];

    private $body = '';

    public function __construct(array $data)
    {
        while ($header = array_shift($data)) {
            if ($header === '') {
                break;
            }

            $this->addHeader($header);
        }

        $this->body = $this->replaceBrokenCharacters(implode("\n", $data));
    }

    private function addHeader(string $header)
    {
        $this->headers[] = $header;
    }

    private function replaceBrokenCharacters(string $data): string
    {
        $convertedData = iconv('utf-8', 'utf-8', $data);

        if ($convertedData !== false) {
            return $convertedData;
        }

        $data = preg_replace('~\x{FFFD}~u', ' ', $data);

        $data = preg_replace_callback('~[\\xA1-\\xFF](?![\\x80-\\xBF]{2,})~', function($matches) {
            return utf8_encode($matches[0]);
        }, $data);

        return $data;
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
