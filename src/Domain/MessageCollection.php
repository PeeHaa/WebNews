<?php declare(strict_types=1);

namespace WebNews\Domain;

class MessageCollection implements \Iterator, \Countable
{
    private $messages = [];

    public function __construct(array $messages)
    {
        foreach ($messages as $message) {
            $this->messages[] = new Message($message);
        }
    }

    public function count(): int
    {
        return count($this->messages);
    }

    public function current(): Message
    {
        return current($this->messages);
    }

    public function next(): void
    {
        next($this->messages);
    }

    public function key(): ?int
    {
        return key($this->messages);
    }

    public function valid(): bool
    {
        return array_key_exists($this->key(), $this->messages);
    }

    public function rewind(): void
    {
        reset($this->messages);
    }
}
