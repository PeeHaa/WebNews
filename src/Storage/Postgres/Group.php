<?php declare(strict_types=1);

namespace WebNews\Storage\Postgres;

use PeeHaa\Nntp\Result\ListGroup;
use WebNews\Domain\GroupCollection;

class Group
{
    private $dbConnection;

    public function __construct(\PDO $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    // lazy upsert
    public function upsert(ListGroup $group)
    {
        $query = 'SELECT COUNT(name)';
        $query.= ' FROM groups';
        $query.= ' WHERE name = :name';

        $stmt = $this->dbConnection->prepare($query);
        $stmt->execute([
            'name' => $group->getName(),
        ]);

        if ($stmt->fetchColumn()) {
            $this->update($group);
        } else {
            $this->create($group);
        }
    }

    private function create(ListGroup $group)
    {
        $query = 'INSERT INTO groups';
        $query.= ' (name, low_watermark, high_watermark, is_writable, is_moderated)';
        $query.= ' VALUES';
        $query.= ' (:name, :lowWatermark, :highWatermark, :isWritable, :isModerated)';

        $stmt = $this->dbConnection->prepare($query);
        $stmt->execute([
            'name'          => $group->getName(),
            'lowWatermark'  => $group->getLowWatermark(),
            'highWatermark' => $group->getHighWatermark(),
            'isWritable'    => (int) $group->isWritable(),
            'isModerated'   => (int) $group->isModerated(),
        ]);
    }

    private function update(ListGroup $group)
    {
        $query = 'UPDATE groups';
        $query.= ' SET low_watermark = :lowWatermark, high_watermark = :highWatermark, is_writable = :isWritable';
        $query.= ', is_moderated = :isModerated';
        $query.= ' WHERE name = :name';

        $stmt = $this->dbConnection->prepare($query);
        $stmt->execute([
            'name'          => $group->getName(),
            'lowWatermark'  => $group->getLowWatermark(),
            'highWatermark' => $group->getHighWatermark(),
            'isWritable'    => (int) $group->isWritable(),
            'isModerated'   => (int) $group->isModerated(),
        ]);
    }

    public function getAll(): GroupCollection
    {
        $query = 'SELECT name, low_watermark, high_watermark, is_writable, is_moderated';
        $query.= ' FROM groups';
        $query.= ' ORDER BY name ASC';

        $stmt = $this->dbConnection->query($query);

        $groups = array_column($stmt->fetchAll(), null, 'name');
        $groups = $this->addNumberOfThreads($groups);
        $groups = $this->addTimestampOfLastMessage($groups);

        return new GroupCollection($groups);
    }

    private function addNumberOfThreads(array $groups): array
    {
        $query = 'SELECT "group", COUNT("group")';
        $query.= ' FROM threads';
        $query.= ' GROUP BY "group"';

        $stmt = $this->dbConnection->query($query);

        foreach ($stmt->fetchAll() as $tally) {
            $groups[$tally['group']]['numberOfThreads'] = $tally['count'];
        }

        return $groups;
    }

    private function addTimestampOfLastMessage(array $groups): array
    {
        $query = 'SELECT groups.name, MAX(messages.timestamp) as timestamp';
        $query.= ' FROM messages';
        $query.= ' JOIN threads ON threads.id = messages.thread';
        $query.= ' JOIN groups ON groups.name = threads.group';
        $query.= ' GROUP BY groups.name';

        $stmt = $this->dbConnection->query($query);

        foreach ($stmt->fetchAll() as $tally) {
            $groups[$tally['name']]['timestamp'] = $tally['timestamp'];
        }

        return $groups;
    }

    public function exists(string $name): bool
    {
        $query = 'SELECT COUNT(name)';
        $query.= ' FROM groups';
        $query.= ' WHERE name = :name';

        $stmt = $this->dbConnection->prepare($query);
        $stmt->execute([
            'name' => $name,
        ]);

        return (bool) $stmt->fetchColumn(0);
    }
}
