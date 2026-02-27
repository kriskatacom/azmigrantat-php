<?php

namespace App\Models;

class Banner extends Model
{
    protected string $table = 'banners';

    public function getFiltered(string $groupKey = null, int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];

        if ($groupKey) {
            $sql .= " WHERE group_key = ?";
            $params[] = $groupKey;
        }

        $sql .= " ORDER BY sort_order ASC LIMIT {$limit} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function countFiltered(string $groupKey = null): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        $params = [];

        if ($groupKey) {
            $sql .= " WHERE group_key = ?";
            $params[] = $groupKey;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getUniqueGroups(): array
    {
        return $this->db->query(
            "SELECT DISTINCT group_key FROM {$this->table} 
             WHERE group_key IS NOT NULL AND group_key != '' 
             ORDER BY group_key ASC"
        )->fetchAll(\PDO::FETCH_COLUMN);
    }
}
