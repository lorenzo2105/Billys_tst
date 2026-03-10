<?php
declare(strict_types=1);

namespace App\Core;

abstract class Model
{
    protected Database $db;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll(string $orderBy = 'id ASC'): array
    {
        return $this->db->fetchAll("SELECT * FROM {$this->table} ORDER BY {$orderBy}");
    }

    public function findById(int $id): ?array
    {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id",
            ['id' => $id]
        );
    }

    public function findBy(string $column, mixed $value): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE {$column} = :value",
            ['value' => $value]
        );
    }

    public function findOneBy(string $column, mixed $value): ?array
    {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE {$column} = :value LIMIT 1",
            ['value' => $value]
        );
    }

    public function create(array $data): string
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        return $this->db->insert(
            "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})",
            $data
        );
    }

    public function update(int $id, array $data): int
    {
        $setParts = [];
        foreach (array_keys($data) as $col) {
            $setParts[] = "{$col} = :{$col}";
        }
        $setClause = implode(', ', $setParts);
        $data['id'] = $id;

        return $this->db->execute(
            "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id",
            $data
        );
    }

    public function delete(int $id): int
    {
        return $this->db->execute(
            "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id",
            ['id' => $id]
        );
    }

    public function count(string $where = '1=1', array $params = []): int
    {
        $result = $this->db->fetch(
            "SELECT COUNT(*) as total FROM {$this->table} WHERE {$where}",
            $params
        );
        return (int)($result['total'] ?? 0);
    }
}
