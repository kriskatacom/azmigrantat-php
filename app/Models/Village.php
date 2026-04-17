<?php

namespace App\Models;

class Village extends Model
{
    protected string $table = 'villages';

    public function save(array $data)
    {
        if (isset($data['description_sections']) && is_array($data['description_sections'])) {
            $data['description_sections'] = json_encode($data['description_sections'], JSON_UNESCAPED_UNICODE);
        }

        if (isset($data['gallery_urls']) && is_array($data['gallery_urls'])) {
            $data['gallery_urls'] = json_encode($data['gallery_urls'], JSON_UNESCAPED_UNICODE);
        }

        return $this->create($data);
    }

    public function find($id): ?array
    {
        $result = parent::find($id);

        if ($result) {
            return $this->decodeJsonFields($result);
        }

        return null;
    }

    private function decodeJsonFields(array $data): array
    {
        if (!empty($data['description_sections'])) {
            $data['description_sections'] = json_decode($data['description_sections'], true);
        }

        if (!empty($data['gallery_urls'])) {
            $data['gallery_urls'] = json_decode($data['gallery_urls'], true);
        }

        return $data;
    }

    public function getByTitle(string $title): ?array
    {
        $result = $this->findByColumn('title', $title);
        return $result ? $this->decodeJsonFields($result) : null;
    }
}
