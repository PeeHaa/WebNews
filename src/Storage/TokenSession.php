<?php declare(strict_types=1);

namespace WebNews\Storage;

use CodeCollab\CsrfToken\Storage\Storage;
use CodeCollab\Http\Session\Session;

class TokenSession implements Storage
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function exists(string $key): bool
    {
        return $this->session->exists($key);
    }

    public function get(string $key): string
    {
        return $this->session->get($key);
    }

    public function set(string $key, string $token): void
    {
        $this->session->set($key, $token);
    }
}
