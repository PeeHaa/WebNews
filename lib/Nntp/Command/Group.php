<?php declare(strict_types=1);

namespace PeeHaa\Nntp\Command;

class Group implements Command
{
    const COMMAND = 'GROUP';

    const SUCCESS_CODE = 211;

    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getCommand(): string
    {
        return self::COMMAND . ' ' . $this->name;
    }

    public function getSuccessCode(): int
    {
        return self::SUCCESS_CODE;
    }
}
