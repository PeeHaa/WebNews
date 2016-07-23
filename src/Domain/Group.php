<?php declare(strict_types=1);

namespace WebNews\Domain;

class Group
{
    private $name;

    private $lowWatermark;

    private $highWatermark;

    private $writable;

    private $moderated;

    private $numberOfThreads;

    private $timestamp;

    public function __construct(array $group)
    {
        $this->name            = $group['name'];
        $this->lowWatermark    = $group['low_watermark'];
        $this->highWatermark   = $group['high_watermark'];
        $this->writable        = $group['is_writable'];
        $this->moderated       = $group['is_moderated'];
        $this->numberOfThreads = isset($group['numberOfThreads']) ? (int) $group['numberOfThreads'] : 0;
        $this->timestamp       = isset($group['timestamp']) ? new \DateTimeImmutable($group['timestamp']) : null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNumberOfThreads(): int
    {
        return $this->numberOfThreads;
    }

    public function getTimestamp(): \DateTimeImmutable
    {
        return $this->timestamp;
    }
}
