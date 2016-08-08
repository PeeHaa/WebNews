<?php declare(strict_types=1);

namespace PeeHaa\Nntp\Command;

class Article implements Command
{
    const COMMAND = 'ARTICLE';

    const SUCCESS_CODE = 220;

    private $articleId;

    public function __construct(int $articleId)
    {
        $this->articleId = $articleId;
    }

    public function getCommand(): string
    {
        return sprintf('%s %s', self::COMMAND, $this->articleId);
    }

    public function getSuccessCode(): int
    {
        return self::SUCCESS_CODE;
    }
}
