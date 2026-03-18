<?php

namespace App\Models;

use App\Models\Model as ModelsModel;

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

    public function getAllWithCountries(array $options = [], array $searchColumns = ['name']): array
    {
        $sql = "SELECT a.*, c.name as country_name 
            FROM {$this->table} a
            LEFT JOIN countries c ON a.country_id = c.id";

        $params = [];
        $whereClauses = [];

        if (!empty($options['search']) && !empty($searchColumns)) {
            $searchTerms = [];
            foreach ($searchColumns as $index => $column) {
                $paramName = "search_" . $index;
                $searchTerms[] = "a.$column LIKE :$paramName";
                $params[$paramName] = "%{$options['search']}%";
            }
            $whereClauses[] = "(" . implode(' OR ', $searchTerms) . ")";
        }

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }

        $sql .= " ORDER BY " . ($options['order'] ?? 'a.sort_order ASC');

        if (isset($options['limit'])) {
            $sql .= " LIMIT " . (int)$options['limit'] . " OFFSET " . (int)($options['offset'] ?? 0);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}