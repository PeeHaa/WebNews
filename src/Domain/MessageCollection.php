<?php declare(strict_types=1);

namespace WebNews\Domain;

class MessageCollection implements \Iterator
{
    private $messages = [];

    public function __construct(array $messages)
    {
        foreach ($messages as $message) {
            $this->messages[] = new Thread($message);
        }
    }

    public function current(): Thread
    {
        return current($this->messages);
    }

    public function next()
    {
        next($this->messages);
    }

    public function key()
    {
        return key($this->messages);
    }

    public function valid(): bool
    {
        return array_key_exists($this->key(), $this->messages);
    }

    public function rewind()
    {
        reset($this->messages);
    }
}
