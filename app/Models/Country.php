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
}
