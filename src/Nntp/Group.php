<?php declare(strict_types=1);

namespace WebNews\Nntp;

class Group
{
    private $name;

    private $numberOfMessages;

    private $firstMessageId;

    private $status;

    public function __construct(string $groupData)
    {
        $groupFields = explode(' ', $groupData);

        if (count($groupFields) !== 4) {
            throw new InvalidDataException();
        }

        $this->name             = $groupFields[0];
        $this->numberOfMessages = (int) $groupFields[1];
        $this->firstMessageId   = (int) $groupFields[2];
        $this->status           = new GroupStatus($groupFields[3]);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNumberOfMessages(): int
    {
        return $this->numberOfMessages;
    }

    public function getFirstMessageId(): int
    {
        return $this->firstMessageId;
    }

    public function allowsPosting(): bool
    {
        return $this->status->isPostingPermitted() || $this->status->isModerated();
    }

    public function isModerated(): bool
    {

    }
}
