<?php declare(strict_types=1);

namespace WebNews\Nntp;

class Groups implements \Iterator
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

    public function next()
    {
        next($this->groups);
    }

    public function key()
    {
        return key($this->groups);
    }

    public function valid(): bool
    {
        return array_key_exists($this->key(), $this->groups);
    }

    public function rewind()
    {
        reset($this->groups);
    }
}
