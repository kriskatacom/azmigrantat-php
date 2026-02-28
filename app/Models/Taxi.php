<?php

namespace App\Models;

class Taxi extends Model
{
    protected string $table = 'taxis';

    public function __construct()
    {
        parent::__construct();
    }

    public function allWithDetails(): array
    {
        $sql = "SELECT t.*, c.name as country_name, ci.name as city_name 
                FROM {$this->table} t
                LEFT JOIN countries c ON t.country_id = c.id
                LEFT JOIN cities ci ON t.city_id = ci.id
                ORDER BY t.sort_order ASC";

        return $this->db->query($sql)->fetchAll();
    }

    public function prepareData(array $data): array
    {
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        } elseif (!empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['slug']);
        }

        $data['sort_order'] = (int)($data['sort_order'] ?? 0);
        $data['country_id'] = !empty($data['country_id']) ? (int)$data['country_id'] : null;
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        return $data;
    }
}
