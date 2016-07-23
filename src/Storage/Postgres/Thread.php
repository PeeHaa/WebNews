<?php declare(strict_types=1);

namespace WebNews\Storage\Postgres;

use PeeHaa\Nntp\Result\ListGroup;
use PeeHaa\Nntp\Result\XOverArticle;

class Thread
{
    private $dbConnection;

    public function __construct(\PDO $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function create(ListGroup $group, XOverArticle $article): int
    {
        $query = 'INSERT INTO threads';
        $query.= ' ("group", subject)';
        $query.= ' VALUES';
        $query.= ' (:group, :subject)';

        $stmt = $this->dbConnection->prepare($query);
        $stmt->execute([
            'group'   => $group->getName(),
            'subject' => $article->getSubject(),
        ]);

        return (int) $this->dbConnection->lastInsertId('threads_id_seq');
    }

    public function findByReference(XOverArticle $article): int
    {
        $query = 'SELECT thread';
        $query.= ' FROM messages';
        $query.= ' WHERE id = :id';

        $stmt = $this->dbConnection->prepare($query);
        $stmt->execute([
            'id' => $article->getReferences()[0],
        ]);

        return (int) $stmt->fetchColumn(0);
    }
}
