<?php declare(strict_types=1);

namespace WebNews\Storage\Postgres;

use PeeHaa\Nntp\Result\ListGroup;

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
}
