<?php declare(strict_types=1);

namespace PeeHaa\Nntp\Command;

class XOver implements Command
{
    const COMMAND = 'XOVER';

    const SUCCESS_CODE = 224;

    private $start;

    private $end;

    public function __construct(int $start, int $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    public function getCommand(): string
    {
        return sprintf('%s %s-%s', self::COMMAND, $this->start, $this->end);
    }

    public function getSuccessCode(): int
    {
        return self::SUCCESS_CODE;
    }
}
