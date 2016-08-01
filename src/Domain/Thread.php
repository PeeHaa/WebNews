<?php declare(strict_types=1);

namespace WebNews\Domain;

class Thread
{
    private $id;

    private $subject;

    private $numberOfMessages;

    private $timestamp;

    public function __construct(array $thread)
    {
        $this->id               = (int) $thread['id'];
        $this->subject          = $thread['subject'];
        $this->numberOfMessages = isset($thread['numberOfMessages']) ? (int) $thread['numberOfMessages'] : 0;
        $this->timestamp        = isset($thread['timestamp']) ? new \DateTimeImmutable($thread['timestamp']) : null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getNumberOfMessages(): int
    {
        return $this->numberOfMessages;
    }

    public function getTimestamp(): \DateTimeImmutable
    {
        return $this->timestamp;
    }
}
