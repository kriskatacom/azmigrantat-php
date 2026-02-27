<?php

namespace App\Models;

class Autobus extends Model
{
    protected string $table = 'autobuses';

    public function getWithRelations(int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT a.*, c.name as country_name, ci.name as city_name 
                FROM {$this->table} a
                LEFT JOIN countries c ON a.country_id = c.id
                LEFT JOIN cities ci ON a.city_id = ci.id
                ORDER BY a.sort_order ASC 
                LIMIT {$limit} OFFSET {$offset}";

        return $this->db->query($sql)->fetchAll();
    }

    public function getByCountry(int $countryId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE country_id = ? ORDER BY sort_order ASC");
        $stmt->execute([$countryId]);
        return $stmt->fetchAll();
    }

    public function prepareData(array $data): array
    {
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }
        
        $data['sort_order'] = $data['sort_order'] ?? 0;
        
        return $data;
    }

    private function generateSlug(string $text): string
    {
        $text = mb_strtolower($text);
        $text = str_replace([' ', '/', '\\'], '-', $text);
        $text = preg_replace('/[^a-z0-9\-]/', '', $text);
        return trim($text, '-');
    }
}