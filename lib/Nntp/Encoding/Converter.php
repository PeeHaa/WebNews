<?php declare(strict_types=1);

namespace PeeHaa\Nntp\Encoding;

interface Converter
{
    public function convert(string $data): string;
}
