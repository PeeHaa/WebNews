<?php declare(strict_types=1);

namespace WebNews\Storage\Postgres;

use PeeHaa\Nntp\Result\XOverArticle;
use PeeHaa\Nntp\Result\Article as ArticleResult;
use WebNews\Domain\MessageCollection;

class Article
{
    private $dbConnection;

    public function __construct(\PDO $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function create(int $threadId, XOverArticle $article, ArticleResult $articleContent)
    {
        $query = 'INSERT INTO messages';
        $query.= ' (message_id, thread, watermark, author_name, author_emailaddress, timestamp, bytes, lines, extra';
        $query.= ', headers, body)';
        $query.= ' VALUES';
        $query.= ' (:messageId, :thread, :watermark, :authorName, :authorEmailAddress, :timestamp, :bytes, :lines';
        $query.= ', :extra, :headers, :body)';

        $stmt = $this->dbConnection->prepare($query);

        $data = [
            'messageId'          => $article->getMessageId(),
            'thread'             => $threadId,
            'watermark'          => $article->getWatermark(),
            'authorName'         => $article->getAuthorName(),
            'authorEmailAddress' => $article->getAuthorEmailAddress(),
            'timestamp'          => $article->getTimestamp()->format('Y-m-d H:i:s'),
            'bytes'              => $article->getBytes(),
            'lines'              => $article->getLines(),
            'extra'              => $article->getExtraData(),
            'headers'            => $articleContent->getHeaders(),
            'body'               => $articleContent->getBody(),
        ];

        $stmt->execute($data);
    }

    public function getLatest(int $amount = 10): MessageCollection
    {
        $query = 'SELECT id, message_id, thread, watermark, author_name, author_emailaddress, timestamp, bytes, lines';
        $query.= ', extra, headers, body';
        $query.= ' FROM messages';
        $query.= ' ORDER BY timestamp DESC';
        $query.= ' LIMIT ' . $amount . ' OFFSET 0';

        $stmt = $this->dbConnection->query($query);

        return new MessageCollection($stmt->fetchAll());
    }
}
