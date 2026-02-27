<?php

namespace App\Models;

class City extends Model
{
    protected string $table = 'cities';

    /**
     * Извлича градове с име на държавата
     */
    public function getWithCountry(int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT c.*, co.name as country_name 
                FROM {$this->table} c
                LEFT JOIN countries co ON c.country_id = co.id
                ORDER BY c.sort_order ASC, c.name ASC 
                LIMIT {$limit} OFFSET {$offset}";

        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Връща градовете за конкретна държава
     * Полезно за динамично зареждане при избор на държава в автобусите
     */
    public function getByCountry(int $countryId): array
    {
        $stmt = $this->db->prepare("SELECT id, name FROM {$this->table} WHERE country_id = ? ORDER BY name ASC");
        $stmt->execute([$countryId]);
        return $stmt->fetchAll();
    }

    /**
     * Подготовка на данните преди запис
     */
    public function prepareData(array $data): array
    {
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }
        
        // Ако няма заглавие, ползваме името
        if (empty($data['heading'])) {
            $data['heading'] = $data['name'];
        }

        $data['sort_order'] = (int)($data['sort_order'] ?? 0);
        
        return $data;
    }

    private function generateSlug(string $text): string
    {
        // Базова slug логика (може да ползваш и HelperService, ако имаш такъв)
        $text = mb_strtolower($text);
        $text = str_replace([' ', '/', '\\'], '-', $text);
        return preg_replace('/[^a-z0-9\-]/', '', $text);
    }
}