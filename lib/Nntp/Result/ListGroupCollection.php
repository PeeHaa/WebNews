<?php declare(strict_types=1);

namespace PeeHaa\Nntp\Result;

class ListGroupCollection implements \Iterator
{
    private $listGroups = [];

    public function __construct(array $listGroups)
    {
        foreach ($listGroups as $listGroup) {
            $this->listGroups[] = new ListGroup($listGroup);
        }
    }

    public function current(): ListGroup
    {
        return current($this->listGroups);
    }

    public function next()
    {
        next($this->listGroups);
    }

    public function key()
    {
        return key($this->listGroups);
    }

    public function valid(): bool
    {
        return array_key_exists($this->key(), $this->listGroups);
    }

    public function rewind()
    {
        reset($this->listGroups);
    }
}
