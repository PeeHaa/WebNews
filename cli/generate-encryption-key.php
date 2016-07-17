<?php declare(strict_types=1);

namespace WebNews\Cli;

use CodeCollab\Encryption\Defusev2\Key;

require_once __DIR__ . '/../bootstrap.php';

$keyPath = realpath(__DIR__ . '/../data') . '/encryption.key';

if (file_exists($keyPath)) {
    echo 'Encryption key already exists in `' . $keyPath . '`.' . PHP_EOL;
    exit;
}

try {
    file_put_contents($keyPath, (new Key())->generate());
} catch (\Throwable $e) {
    echo 'Could not generate an encryption key. ' . $e->getMessage() . PHP_EOL;
    exit;
}

echo 'A new encryption key has been generated and stored in `' . $keyPath . '`.' . PHP_EOL;
