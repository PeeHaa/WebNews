<?php declare(strict_types=1);

namespace WebNews\Domain;

class Author
{
    private $name;

    private $emailAddress;

    public function __construct(string $name, string $emailAddress)
    {
        $this->name         = $name;
        $this->emailAddress = $emailAddress;
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
