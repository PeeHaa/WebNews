<?php declare(strict_types=1);

namespace WebNews\Nntp;

class Group
{
    private $name;

    private $highWaterMark;

    private $lowWaterMark;

    private $status;

    public function __construct(string $groupData)
    {
        $groupFields = explode(' ', $groupData);

        if (count($groupFields) !== 4) {
            throw new InvalidDataException();
        }

        $this->name          = $groupFields[0];
        $this->highWaterMark = $groupFields[1];
        $this->lowWaterMark  = $groupFields[2];
        $this->status        = new GroupStatus($groupFields[3]);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHighWaterMark(): int
    {
        return $this->highWaterMark;
    }

    public function getLowWaterMark(): int
    {
        return $this->lowWaterMark;
    }

    public function allowsPosting(): bool
    {
        return $this->status->isPostingPermitted() || $this->status->isModerated();
    }

    public function isModerated(): bool
    {

    }
}
