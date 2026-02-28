<?php

namespace App\Models;

use App\Core\Database;
use PDO;

abstract class Model
{
    protected \PDO $db;
    protected string $table;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create($data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute($data)) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    public function update(int $id, array $data): bool
    {
        $fields = "";
        foreach ($data as $key => $value) {
            $fields .= "{$key} = :{$key}, ";
        }
        $fields = rtrim($fields, ', ');

        $data['id'] = $id;
        $sql = "UPDATE {$this->table} SET {$fields} WHERE id = :id";

        return $stmt = $this->db->prepare($sql)->execute($data);
    }

    public function all(array $options = []): array
    {
        $columns = isset($options['columns']) ? implode(', ', $options['columns']) : '*';

        $orderBy = $options['order'] ?? 'id DESC';

        $sql = "SELECT {$columns} FROM {$this->table} ORDER BY {$orderBy}";

        if (isset($options['limit'])) {
            $sql .= " LIMIT " . (int)$options['limit'];
            if (isset($options['offset'])) {
                $sql .= " OFFSET " . (int)$options['offset'];
            }
        }

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function where(string $column, $value): array
    {
        $hasOperator = preg_match('/[<>=!]/', $column);

        $sql = "SELECT * FROM {$this->table} WHERE " . ($hasOperator ? $column : "{$column} =") . " :val";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['val' => $value]);
        return $stmt->fetchAll();
    }

    public function count(): int
    {
        return (int)$this->db->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();
    }

    public function max(string $column)
    {
        $sql = "SELECT MAX({$column}) AS max_val FROM {$this->table}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();

        return $result ? $result['max_val'] : null;
    }

    public function updateOrder(array $items): bool
    {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("UPDATE {$this->table} SET sort_order = :sort_order WHERE id = :id");

            foreach ($items as $item) {
                $stmt->execute([
                    'sort_order' => $item['sort_order'],
                    'id' => $item['id']
                ]);
            }

            return $this->db->commit();
        } catch (\Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return false;
        }
    }

    public function generateUuid(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    public function generateSlug(string $text): string
    {
        $text = mb_strtolower($text);
        $text = str_replace([' ', '/', '\\'], '-', $text);
        return preg_replace('/[^a-z0-9\-]/', '', $text);
    }
}
