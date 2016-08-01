<?php declare(strict_types=1);

namespace WebNews\Domain;

class ThreadCollection implements \Iterator
{
    private $threads = [];

    public function __construct(array $threads)
    {
        foreach ($threads as $thread) {
            $this->threads[] = new Thread($thread);
        }
    }

    public function current(): Thread
    {
        return current($this->threads);
    }

    public function next()
    {
        next($this->threads);
    }

    public function key()
    {
        return key($this->threads);
    }

    public function valid(): bool
    {
        return array_key_exists($this->key(), $this->threads);
    }

    public function rewind()
    {
        reset($this->threads);
    }
}
