<?php declare(strict_types=1);

namespace WebNews\Domain;

class GroupCollection implements \Iterator
{
    private $groups = [];

    public function __construct(array $groups)
    {
        foreach ($groups as $group) {
            $this->groups[] = new Group($group);
        }
    }

    public function current(): Group
    {
        return current($this->groups);
    }

    public function next(): void
    {
        next($this->groups);
    }

    public function key(): ?int
    {
        return key($this->groups);
    }

    public function valid(): bool
    {
        return array_key_exists($this->key(), $this->groups);
    }

    public function rewind(): void
    {
        reset($this->groups);
    }
}
