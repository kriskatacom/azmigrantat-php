<?php

namespace App\Models;

class BusCompany extends Model
{
    protected string $table = 'bus_companies';

    public function prepareData(array $data): array
    {
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = isset($data['is_active']) ? 1 : 0;

        return $data;
    }
}