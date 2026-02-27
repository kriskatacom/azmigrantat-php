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
}
