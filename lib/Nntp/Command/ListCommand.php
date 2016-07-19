<?php declare(strict_types=1);

namespace PeeHaa\Nntp\Command;

class ListCommand extends Command
{
    const COMMAND = 'LIST';

    const SUCCESS_CODE = 215;

    public function getCommand(): string
    {
        return self::COMMAND;
    }
}
