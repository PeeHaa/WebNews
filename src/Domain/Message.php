<?php declare(strict_types=1);

namespace WebNews\Domain;

class Message
{
    private $id;

    private $threadId;

    private $watermark;

    private $author;

    private $timestamp;

    public function __construct(array $message)
    {
        $this->id        = (int) $message['id'];
        $this->threadId  = (int) $message['thread'];
        $this->watermark = (int) $message['watermark'];
        $this->author    = new Author($message['author_name'], $message['author_emailaddress']);
        $this->timestamp = isset($message['timestamp']) ? new \DateTimeImmutable($message['timestamp']) : null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getThreadId(): int
    {
        return $this->threadId;
    }

    public function getWatermark(): int
    {
        return $this->watermark;
    }

    public function getAuthorName(): string
    {
        return $this->author->getName();
    }

    public function getAuthorGravatarHash(): string
    {
        return $this->author->getGravatarHash();
    }

    public function getAuthorEmailAddress(): string
    {
        return $this->author->getEmailAddress();
    }

    public function getTimestamp(): \DateTimeImmutable
    {
        return $this->timestamp;
    }
}
