<?php declare(strict_types=1);

namespace PeeHaa\Nntp\Encoding;

class Utf8 implements Converter
{
    // https://www.w3.org/International/questions/qa-forms-utf-8.en
    const UTF8_SEQUENCES = '%^(?:
        [\x09\x0A\x0D\x20-\x7E]            # ASCII
        | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
        | \xE0[\xA0-\xBF][\x80-\xBF]         # excluding overlongs
        | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
        | \xED[\x80-\x9F][\x80-\xBF]         # excluding surrogates
        | \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
        | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
        | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
        )*$%xs';

    public function convert(string $data): string
    {
        return $data;

        if ($this->isValidUtf8($data)) {
            return $data;
        }

        if ($this->isValidCp1252($data)) {
            return $this->convertFromCp1252($data);
        }

        if ($this->canDetectEncoding($data)) {
            return $this->convertFromDetectedEncoding($data);
        }

        $encodedData = $this->manuallyFixInvalidSequences($data);

        if ($this->isValidUtf8($encodedData)) {
            return $encodedData;
        }

        return '{ENCODING ERROR}';
    }

    private function isValidUtf8(string $data): bool
    {
        return (bool)preg_match(self::UTF8_SEQUENCES, $data);
    }

    private function isValidCp1252(string $data): bool
    {
        return @iconv('CP1252', 'UTF-8', $data) !== false;
    }

    private function convertFromCp1252(string $data): string
    {
        return iconv('CP1252', 'UTF-8', $data);
    }

    private function canDetectEncoding(string $data): bool
    {
        return mb_detect_encoding($data, 'auto', true) !== false;
    }

    private function convertFromDetectedEncoding(string $data): string
    {
        return iconv(mb_detect_encoding($data, 'auto', true), 'UTF-8', $data);
    }

    private function manuallyFixInvalidSequences(string $data): string
    {
        return preg_replace_callback('~[\\xA1-\\xFF](?![\\x80-\\xBF]{2,})~', function($matches) {
            return utf8_encode($matches[0]);
        }, $data);
    }
}
