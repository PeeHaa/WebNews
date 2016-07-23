<?php declare(strict_types=1);

namespace PeeHaa\Nntp\Result;

class Author
{
    private $name;

    private $emailAddress;

    public function __construct(string $author)
    {
        $authorParts = explode(' ', $author, 2);

        $this->name         = isset($authorParts[1]) ? trim($authorParts[1], '()') : $authorParts[0];
        $this->emailAddress = $authorParts[0];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }
}
