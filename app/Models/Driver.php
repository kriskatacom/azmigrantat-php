<?php

namespace App\Models;

use PDO;

class Driver extends Model
{
    protected string $table = 'drivers';

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function findByUsername(string $username): ?array
    {
        $sql = "SELECT d.*, u.username, u.name as user_name, u.email as user_email 
            FROM {$this->table} d
            JOIN users u ON d.user_id = u.id
            WHERE u.username = :username LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    public function searchByCitySlugs(?string $fromSlug, ?string $toSlug, int $limit = 20): array
    {
        $sql = "SELECT d.*, 
                       c1.name as from_city_name, c1.slug as from_city_slug,
                       c2.name as to_city_name, c2.slug as to_city_slug,
                       u.name as user_display_name
                FROM {$this->table} d
                LEFT JOIN cities c1 ON d.from_city_id = c1.id
                LEFT JOIN cities c2 ON d.to_city_id = c2.id
                LEFT JOIN users u ON d.user_id = u.id
                WHERE d.status = 'active'";

        $params = [];

        if ($fromSlug) {
            $sql .= " AND c1.slug = :from_slug";
            $params['from_slug'] = $fromSlug;
        }

        if ($toSlug) {
            $sql .= " AND c2.slug = :to_slug";
            $params['to_slug'] = $toSlug;
        }

        $sql .= " ORDER BY d.travel_starts_at ASC LIMIT " . (int)$limit;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function getByUserId(string $userId): array
    {
        return $this->all([
            'where' => ['user_id' => $userId],
            'order' => 'created_at DESC'
        ]);
    }

    public function formatJsonFields(array $driver): array
    {
        if (isset($driver['gallery_images'])) {
            $driver['gallery_images'] = json_decode($driver['gallery_images'], true);
        }
        if (isset($driver['contact_methods'])) {
            $driver['contact_methods'] = json_decode($driver['contact_methods'], true);
        }
        return $driver;
    }
}