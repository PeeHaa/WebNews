<?php declare(strict_types=1);

namespace PeeHaa\Nntp\Result;

use PeeHaa\Nntp\Encoding\Converter;

class XOverArticleCollection implements \Iterator
{
    private $articles = [];

    public function __construct(Converter $converter, array $xOverArticles)
    {
        foreach ($xOverArticles as $article) {
            $this->articles[] = new XOverArticle($converter, $article);
        }
    }

    public function current(): XOverArticle
    {
        return current($this->articles);
    }

    public function next()
    {
        next($this->articles);
    }

    public function key()
    {
        return key($this->articles);
    }

    public function valid(): bool
    {
        return array_key_exists($this->key(), $this->articles);
    }

    public function rewind()
    {
        reset($this->articles);
    }
}
