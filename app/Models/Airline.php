<?php

namespace App\Models;

class Airline extends Model
{
    protected string $table = 'airlines';

    public function prepareData(array $data): array
    {
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }

        $data['sort_order'] = (int)($data['sort_order'] ?? 0);
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        return $data;
    }

    private function generateSlug(string $text): string
    {
        $text = mb_strtolower($text);
        $text = str_replace([' ', '/', '\\'], '-', $text);
        $text = preg_replace('/[^a-z0-9\-]/', '', $text);
        $text = preg_replace('/-+/', '-', $text);
        return trim($text, '-');
    }

    public function getActive(): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY sort_order ASC, name ASC";
        return $this->db->query($sql)->fetchAll();
    }
}
