<?php

namespace App\Models;

class Banner extends Model
{
    protected string $table = 'banners';

    public function getFiltered(string|null $groupKey = null, int $limit = 20, int $offset = 0): array
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

    public function countFiltered(string|null $groupKey = null): int
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

    public function prepareData(array $input): array
    {
        $data = $input;

        $flags = ['show_name', 'show_description', 'show_overlay', 'show_button'];

        foreach ($flags as $flag) {
            $data[$flag] = isset($input[$flag]) ? 1 : 0;
        }

        $data['sort_order'] = !empty($input['sort_order']) ? (int)$input['sort_order'] : 0;
        $data['height'] = !empty($input['height']) ? (int)$input['height'] : 520;

        unset($data['remove_image'], $data['return_url']);

        return $data;
    }
}