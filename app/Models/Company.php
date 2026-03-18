<?php

namespace App\Models;

class Company extends Model
{
    protected string $table = 'companies';

    public function getAllWithRelations(array $options = [], array $searchColumns = ['name']): array
    {
        $sql = "SELECT c.* FROM {$this->table} c";

        $params = [];
        $whereClauses = [];

        if (!empty($options['search']) && !empty($searchColumns)) {
            $searchTerms = [];
            foreach ($searchColumns as $index => $column) {
                $paramName = "search_" . $index;
                $searchTerms[] = "c.$column LIKE :$paramName";
                $params[$paramName] = "%{$options['search']}%";
            }
            $whereClauses[] = "(" . implode(' OR ', $searchTerms) . ")";
        }

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }

        $sql .= " ORDER BY c.name ASC";

        if (isset($options['limit'])) {
            $sql .= " LIMIT " . (int)$options['limit'] . " OFFSET " . (int)($options['offset'] ?? 0);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function findWithRelations(int $id): ?array
    {
        $sql = "SELECT c.*, 
                       co.name as country_name, 
                       ci.name as city_name, 
                       cat.name as category_name
                FROM {$this->table} c
                LEFT JOIN countries co ON c.country_id = co.id
                LEFT JOIN cities ci ON c.city_id = ci.id
                LEFT JOIN categories cat ON c.category_id = cat.id
                WHERE c.id = :id 
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function getNextSortOrder(): int
    {
        $max = $this->max('sort_order');
        return $max ? (int)$max + 1 : 1;
    }

    public function prepareData(array $data): array
    {
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        } elseif (!empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['slug']);
        }

        unset($data['latitude'], $data['longitude']);

        $data['sort_order']  = (int)($data['sort_order'] ?? 0);
        $data['country_id']  = !empty($data['country_id']) ? (int)$data['country_id'] : null;
        $data['city_id']     = !empty($data['city_id']) ? (int)$data['city_id'] : null;
        $data['category_id'] = !empty($data['category_id']) ? (int)$data['category_id'] : null;
        $data['user_id'] = !empty($data['user_id']) ? $data['user_id'] : null;

        $data['is_active'] = isset($_POST['is_active']) ? 1 : 0;

        if (empty($data['image_url'])) unset($data['image_url']);

        return $data;
    }
}
