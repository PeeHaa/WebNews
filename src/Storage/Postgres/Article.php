<?php declare(strict_types=1);

namespace WebNews\Storage\Postgres;

use PeeHaa\Nntp\Result\XOverArticle;

class Article
{
    private $dbConnection;

    public function __construct(\PDO $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function create(int $threadId, XOverArticle $article)
    {
        $query = 'INSERT INTO messages';
        $query.= ' (id, thread, watermark, author_name, author_emailaddress, timestamp, bytes, lines, extra)';
        $query.= ' VALUES';
        $query.= ' (:id, :thread, :watermark, :authorName, :authorEmailAddress, :timestamp, :bytes, :lines, :extra)';

        $stmt = $this->dbConnection->prepare($query);
        $stmt->execute([
            'id'                 => $article->getMessageId(),
            'thread'             => $threadId,
            'watermark'          => $article->getWatermark(),
            'authorName'         => $article->getAuthorName(),
            'authorEmailAddress' => $article->getAuthorEmailAddress(),
            'timestamp'          => $article->getTimestamp()->format('Y-m-d H:i:s'),
            'bytes'              => $article->getBytes(),
            'lines'              => $article->getLines(),
            'extra'              => $article->getExtraData(),
        ]);
    }
}
