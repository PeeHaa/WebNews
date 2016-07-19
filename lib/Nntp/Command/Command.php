<?php declare(strict_types=1);

namespace PeeHaa\Nntp\Command;

abstract class Command
{
    const COMMAND = 'LIST';

    const SUCCESS_CODE = 215;

    abstract public function getCommand();

    public function getLength(): int
    {
        return strlen($this->getCommand());
    }

    public function isCommandCompletelySent(int $bytesSent): bool
    {
        return $bytesSent === $this->getLength();
    }
}
