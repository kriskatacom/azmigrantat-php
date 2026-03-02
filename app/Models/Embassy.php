<?php

namespace App\Models;

class Embassy extends Model
{
    protected string $table = 'embassies';

    public function getAllWithCountries(array $options = []): array
    {
        $orderBy = $options['order'] ?? 'c.name ASC, e.name ASC';

        $sql = "SELECT e.*, c.name as country_name 
                FROM {$this->table} e 
                LEFT JOIN countries c ON e.country_id = c.id 
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

    public function countByCountry(int $countryId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE country_id = ?");
        $stmt->execute([$countryId]);
        return (int)$stmt->fetchColumn();
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