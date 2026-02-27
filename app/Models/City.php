<?php

namespace App\Models;

class City extends Model
{
    protected string $table = 'cities';

    public function getWithCountry(int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT c.*, co.name as country_name 
                FROM {$this->table} c
                LEFT JOIN countries co ON c.country_id = co.id
                ORDER BY c.sort_order ASC, c.name ASC 
                LIMIT {$limit} OFFSET {$offset}";

        return $this->db->query($sql)->fetchAll();
    }

    public function getByCountry(int $countryId): array
    {
        $stmt = $this->db->prepare("SELECT id, name FROM {$this->table} WHERE country_id = ? ORDER BY name ASC");
        $stmt->execute([$countryId]);
        return $stmt->fetchAll();
    }

    public function prepareData(array $data): array
    {
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }
        
        if (empty($data['heading'])) {
            $data['heading'] = $data['name'];
        }

        $data['sort_order'] = (int)($data['sort_order'] ?? 0);
        
        return $data;
    }

    private function generateSlug(string $text): string
    {
        $text = mb_strtolower($text);
        $text = str_replace([' ', '/', '\\'], '-', $text);
        return preg_replace('/[^a-z0-9\-]/', '', $text);
    }
}
