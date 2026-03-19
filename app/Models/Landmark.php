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

    public function getFilteredWithCountry(array $options = [])
    {
        $sql = "SELECT l.* FROM {$this->table} l 
            JOIN countries c ON l.country_id = c.id 
            WHERE l.country_id = :country_id AND l.is_active = 1";

        $params = [
            'country_id' => $options['where']['country_id']
        ];

        if (!empty($options['search'])) {
            $sql .= " AND (l.name LIKE :search1 OR c.name LIKE :search2 OR l.content LIKE :search3)";

            $searchTerm = "%{$options['search']}%";

            $params['search1'] = $searchTerm;
            $params['search2'] = $searchTerm;
            $params['search3'] = $searchTerm;
        }

        $orderBy = $options['order'] ?? 'l.sort_order ASC';
        $sql .= " ORDER BY {$orderBy}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function prepareData(array $data): array
    {
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        } elseif (!empty($data['slug'])) {
            $data['slug'] = $this->generateSlug($data['slug']);
        }

        unset($data['latitude'], $data['longitude']);

        if (empty($data['heading']) && !empty($data['name'])) {
            $data['heading'] = $data['name'];
        }

        $data['sort_order'] = (int)($data['sort_order'] ?? 0);
        $data['country_id'] = !empty($data['country_id']) ? (int)$data['country_id'] : null;
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        return $data;
    }
}
