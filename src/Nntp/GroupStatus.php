<?php declare(strict_types=1);

namespace WebNews\Nntp;

class GroupStatus
{
    private $status;

    public function __construct(string $status)
    {
        $this->status = $status;
    }

    public function isPostingPermitted(): bool
    {
        return $this->status === 'y';
    }

    public function isModerated(): bool
    {
        return $this->status === 'm';
    }
}
