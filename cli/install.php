#!/usr/bin/env php
<?php declare(strict_types=1);

namespace WebNews\Cli;

use League\CLImate\CLImate;
use WebNews\Install\Configuration;
use WebNews\Install\Writer;

require_once __DIR__ . '/../bootstrap.php';

$configuration = (new Configuration(new CLImate()))->run();

(new Writer(__DIR__ . '/../config'))->write($configuration);

require_once __DIR__ . '/generate-encryption-key.php';
