<?php

namespace App\Models;

class Country extends Model
{
    protected string $table = 'countries';

    public function getAllSorted(): array
    {
        return $this->all([
            'order' => 'sort_order ASC, name ASC'
        ]);
    }

    public function findBySlug(string $slug): ?array
    {
        $result = $this->where('slug', $slug);
        return $result[0] ?? null;
    }

    public function prepareData(array $data): array
    {
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }

        if (empty($data['heading'])) {
            $data['heading'] = $data['name'];
        }
        
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = isset($_POST['is_active']) ? 1 : 0;
        
        return $data;
    }
}