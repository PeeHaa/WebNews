<?php declare(strict_types=1);

namespace PeeHaa\Nntp\Command;

interface Command
{
    public function getCommand(): string;

    public function getSuccessCode(): int;
}
