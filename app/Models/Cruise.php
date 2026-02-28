<?php

namespace App\Models;

class Cruise extends Model
{
    protected string $table = 'cruises';

    public function prepareData(array $data): array
    {
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }

        $data['sort_order'] = (int)($data['sort_order'] ?? 0);
        $data['is_active'] = isset($_POST['is_active']) ? 1 : 0;
        
        return $data;
    }
}