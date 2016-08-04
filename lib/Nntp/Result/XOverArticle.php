<?php declare(strict_types=1);

namespace PeeHaa\Nntp\Result;

class XOverArticle
{
    private $watermark;

    private $subject;

    private $author;

    private $timestamp;

    private $messageId;

    private $references;

    private $bytes;

    private $lines;

    private $extra;

    public function __construct(string $xOverLine)
    {
        $xOverInformation = explode("\t", $xOverLine);

        if (count($xOverInformation) !== 9 || !$xOverInformation[3]) {
            throw new InvalidResultException('Invalid message');
        }

        $this->watermark  = (int) $xOverInformation[0];
        $this->subject    = $this->replaceBrokenCharacters($xOverInformation[1]);
        $this->author     = new Author($this->replaceBrokenCharacters($xOverInformation[2]));
        $this->timestamp  = $this->buildTimestamp($xOverInformation[3]);
        $this->messageId  = trim($xOverInformation[4], '<>');
        $this->references = trim($xOverInformation[5]) ? explode('><', trim($xOverInformation[5], '<>')) : [];
        $this->bytes      = (int) $xOverInformation[6];
        $this->lines      = (int) $xOverInformation[7];
        $this->extra      = $xOverInformation[8];
    }

    private function buildTimestamp(string $timestamp): \DateTimeImmutable
    {
        preg_match('~(\d{1,2}) ([a-z]{3}) (\d{2,4}) (\d{1,2}:\d{2}:\d{2})(?: ([\-+]?\d{4}))?~i', $timestamp, $matches);

        $year     = strlen($matches[3]) === 4 ? $matches[3] : '19' . $matches[3];
        $timezone = $matches[5] ?? '+0000';

        $formattedTimestamp = sprintf('%s %s %s %s %s', $matches[1], $matches[2], $year, $matches[4], $timezone);

        return \DateTimeImmutable::createFromFormat('j M Y H:i:s O', $formattedTimestamp);
    }

    private function replaceBrokenCharacters(string $data): string
    {
        $convertedData = iconv('utf-8', 'utf-8', $data);

        if ($convertedData !== false) {
            return $convertedData;
        }

        $data = preg_replace('~\x{FFFD}~u', ' ', $data);

        $data = preg_replace_callback('~[\\xA1-\\xFF](?![\\x80-\\xBF]{2,})~', function($matches) {
            return utf8_encode($matches[0]);
        }, $data);

        return $data;
    }

    public function getWatermark(): int
    {
        return $this->watermark;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getAuthorName(): string
    {
        return $this->author->getName();
    }

    public function getAuthorEmailAddress(): string
    {
        return $this->author->getEmailAddress();
    }

    public function getTimestamp(): \DateTimeImmutable
    {
        return $this->timestamp;
    }

    public function getMessageId(): string
    {
        return $this->messageId;
    }

    public function getReferences(): array
    {
        return $this->references;
    }

    public function startsNewThread(): bool
    {
        return empty($this->getReferences());
    }

    public function getBytes(): int
    {
        return $this->bytes;
    }

    public function getLines(): int
    {
        return $this->lines;
    }

    public function getExtraData(): string
    {
        return $this->extra;
    }
}
