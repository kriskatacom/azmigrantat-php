<?php

namespace App\Models;

use App\Models\Model as ModelsModel;
use App\Services\HelperService;

class Airport extends ModelsModel
{
    protected string $table = 'airports';

    public function prepareData(array $data): array
    {
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }

        $data['sort_order'] = (int)($data['sort_order'] ?? 0);
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        return $data;
    }

    public function getActive(): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY sort_order ASC, name ASC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getAllWithCountries(array $params = []): array
    {
        $limit  = $params['limit'] ?? 10;
        $offset = $params['offset'] ?? 0;
        $order  = $params['order'] ?? 'airports.sort_order ASC, airports.name ASC';

        $sql = "SELECT airports.*, countries.name as country_name 
                FROM {$this->table} 
                LEFT JOIN countries ON airports.country_id = countries.id 
                ORDER BY {$order} 
                LIMIT {$limit} OFFSET {$offset}";

        return $this->db->query($sql)->fetchAll();
    }
}