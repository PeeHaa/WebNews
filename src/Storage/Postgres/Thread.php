<?php declare(strict_types=1);

namespace WebNews\Storage\Postgres;

use PeeHaa\Nntp\Result\ListGroup;
use PeeHaa\Nntp\Result\XOverArticle;
use WebNews\Domain\ThreadCollection;
use WebNews\Domain\Thread as ThreadObject;
use WebNews\Domain\MessageCollection;

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

    public function articleExists(XOverArticle $article): bool
    {
        $query = 'SELECT COUNT(id)';
        $query.= ' FROM messages';
        $query.= ' WHERE id = :id';

        $stmt = $this->dbConnection->prepare($query);
        $stmt->execute([
            'id' => $article->getMessageId(),
        ]);

        return (bool) $stmt->fetchColumn(0);
    }

    public function getThreadsByGroup(string $group): ThreadCollection
    {
        $query = 'SELECT threads.id, threads.subject';
        $query.= ' FROM threads';
        $query.= ' WHERE threads.group = :group';
        $query.= ' ORDER BY id DESC';
        $query.= ' LIMIT 50 OFFSET 0';

        $stmt = $this->dbConnection->prepare($query);
        $stmt->execute([
            'group' => $group,
        ]);

        $threads = array_column($stmt->fetchAll(), null, 'id');
        $threads = $this->addNumberOfMessages($threads);
        $threads = $this->addTimestampOfLastMessage($threads);

        return new ThreadCollection($threads);
    }

    private function addNumberOfMessages(array $threads): array
    {
        $inQuery = implode(',', array_fill(0, count($threads), '?'));

        $query = 'SELECT thread, COUNT(thread)';
        $query.= ' FROM messages';
        $query.= ' WHERE thread IN (' . $inQuery . ')';
        $query.= ' GROUP BY thread';

        $stmt = $this->dbConnection->prepare($query);
        $stmt->execute(array_keys($threads));

        foreach ($stmt->fetchAll() as $tally) {
            $threads[$tally['thread']]['numberOfMessages'] = $tally['count'];
        }

        return $threads;
    }

    private function addTimestampOfLastMessage(array $threads): array
    {
        $inQuery = implode(',', array_fill(0, count($threads), '?'));

        $query = 'SELECT messages.thread, MAX(messages.timestamp) as timestamp';
        $query.= ' FROM messages';
        $query.= ' JOIN threads ON threads.id = messages.thread';
        $query.= ' JOIN groups ON groups.name = threads.group';
        $query.= ' WHERE thread IN (' . $inQuery . ')';
        $query.= ' GROUP BY messages.thread';

        $stmt = $this->dbConnection->prepare($query);
        $stmt->execute(array_keys($threads));

        foreach ($stmt->fetchAll() as $tally) {
            $threads[$tally['thread']]['timestamp'] = $tally['timestamp'];
        }

        return $threads;
    }

    public function exists(int $id): bool
    {
        $query = 'SELECT COUNT(id)';
        $query.= ' FROM threads';
        $query.= ' WHERE id = :id';

        $stmt = $this->dbConnection->prepare($query);
        $stmt->execute([
            'id' => $id,
        ]);

        return (bool) $stmt->fetchColumn(0);
    }

    public function getInfo(int $id): ThreadObject
    {
        $query = 'SELECT threads.id, threads.subject';
        $query.= ' FROM threads';
        $query.= ' WHERE threads.id = :id';

        $stmt = $this->dbConnection->prepare($query);
        $stmt->execute([
            'id' => $id,
        ]);

        $threads = array_column($stmt->fetchAll(), null, 'id');
        $threads = $this->addNumberOfMessages($threads);
        $threads = $this->addTimestampOfLastMessage($threads);

        return new ThreadObject(reset($threads));
    }

    public function getMessages(int $id): MessageCollection
    {
        $query = 'SELECT id, thread, watermark, author_name, author_emailaddress, timestamp, body';
        $query.= ' FROM messages';
        $query.= ' WHERE thread = :thread';
        $query.= ' ORDER BY watermark ASC';

        $stmt = $this->dbConnection->prepare($query);
        $stmt->execute([
            'thread' => $id,
        ]);

        return new MessageCollection($stmt->fetchAll());
    }
}
