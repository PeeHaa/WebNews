<?php declare(strict_types=1);

namespace PeeHaa\Nntp\Result;

class ListGroup
{
    private $name;

    private $lowWatermark;

    private $highWatermark;

    private $writable;

    private $moderated;

    public function __construct(string $groupLine)
    {
        $groupInformation = explode(' ', $groupLine);

        $this->name          = $groupInformation[0];
        $this->lowWatermark  = (int) $groupInformation[2];
        $this->highWatermark = (int) $groupInformation[1];
        $this->writable      = in_array($groupInformation[3], ['y', 'm'], true);
        $this->moderated     = $groupInformation[3] === 'm';
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLowWatermark(): int
    {
        return $this->lowWatermark;
    }

    public function getHighWatermark(): int
    {
        return $this->highWatermark;
    }

    public function isWritable(): bool
    {
        return $this->writable;
    }

    public function isModerated(): bool
    {
        return $this->moderated;
    }
}
