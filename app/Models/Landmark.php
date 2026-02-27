<?php

namespace App\Models;

class Landmark extends Model
{
    protected string $table = 'landmarks';

    public function getAllWithCountries(array $options = []): array
    {
        $orderBy = $options['order'] ?? 'l.sort_order ASC, l.name ASC';

        $sql = "SELECT l.*, c.name as country_name 
            FROM {$this->table} l 
            LEFT JOIN countries c ON l.country_id = c.id 
            ORDER BY {$orderBy}";

        if (isset($options['limit'])) {
            $sql .= " LIMIT " . (int)$options['limit'];
            if (isset($options['offset'])) {
                $sql .= " OFFSET " . (int)$options['offset'];
            }
        }

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
