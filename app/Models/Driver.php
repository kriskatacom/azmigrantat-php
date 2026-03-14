<?php

namespace App\Models;

class Driver extends Model
{
    protected string $table = 'drivers';

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

    public function searchByCityInDetails($fromSlug = null, $toSlug = null)
    {
        $sql = "SELECT d.*, 
                   u.username AS username,
                   c1.name AS from_city_name,
                   c2.name AS to_city_name
            FROM {$this->table} d
            INNER JOIN users u ON d.user_id = u.id
            LEFT JOIN cities c1 ON d.from_city_id = c1.id
            LEFT JOIN cities c2 ON d.to_city_id = c2.id
            WHERE d.driver_travel_status != 'not_traveling' 
              AND d.is_active = '1'";

        $params = [];

        if ($fromSlug) {
            $sql .= " AND (c1.slug = :from_slug 
                    OR d.travel_departure_details LIKE :from_slug_text 
                    OR d.travel_return_details LIKE :from_slug_text_ret)";

            $params['from_slug'] = $fromSlug;
            $params['from_slug_text'] = '%' . $fromSlug . '%';
            $params['from_slug_text_ret'] = '%' . $fromSlug . '%';
        }

        if ($toSlug) {
            $sql .= " AND (c2.slug = :to_slug 
                    OR d.travel_departure_details LIKE :to_slug_text 
                    OR d.travel_return_details LIKE :to_slug_text_ret)";

            $params['to_slug'] = $toSlug;
            $params['to_slug_text'] = '%' . $toSlug . '%';
            $params['to_slug_text_ret'] = '%' . $toSlug . '%';
        }

        $sql .= " ORDER BY d.travel_starts_at ASC";

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

    public function prepareData(array $data): array
    {
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }

        if (isset($data['contact_methods'])) {
            $json = trim($data['contact_methods']);
            if (empty($json)) {
                $data['contact_methods'] = null;
            } else {
                json_decode($json);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $data['contact_methods'] = json_encode(['text' => $json], JSON_UNESCAPED_UNICODE);
                }
            }
        }

        if (!empty($data['travel_starts_at'])) {
            $data['travel_starts_at'] = date('Y-m-d H:i:s', strtotime($data['travel_starts_at']));
        }

        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        unset($data['country_id'], $data['city_id'], $data['remove_travel_departure_image'], $data['remove_travel_return_image'], $data['slug']);

        return $data;
    }

    public function getActiveDriversWithUsers()
    {
        $sql = "SELECT d.*, 
                   u.username as username, 
                   c1.name as from_city_name, 
                   c2.name as to_city_name 
            FROM {$this->table} d
            INNER JOIN users u ON d.user_id = u.id
            LEFT JOIN cities c1 ON d.from_city_id = c1.id
            LEFT JOIN cities c2 ON d.to_city_id = c2.id
            WHERE d.is_active = '1'
            ORDER BY d.travel_starts_at ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
