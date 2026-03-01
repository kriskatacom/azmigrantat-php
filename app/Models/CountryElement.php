<?php

namespace App\Models;

class CountryElement extends Model
{
    protected string $table = 'country_elements';

    public function __construct()
    {
        parent::__construct();
    }

    public function prepareData(array $data): array
    {
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }

        $data['sort_order'] = (int)($data['sort_order'] ?? 0);
        $data['is_active'] = isset($_POST['is_active']) ? 1 : 0;
        
        return $data;
    }

    public function getByCountry(int $countryId, bool $onlyActive = true): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE country_id = :country_id";

        if ($onlyActive) {
            $sql .= " AND is_active = 1";
        }

        $sql .= " ORDER BY sort_order ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['country_id' => $countryId]);

        return $stmt->fetchAll();
    }

    public function getNextOrder(int $countryId): int
    {
        $sql = "SELECT MAX(sort_order) as max_order FROM {$this->table} WHERE country_id = :country_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['country_id' => $countryId]);
        $result = $stmt->fetch();

        return ($result['max_order'] ?? 0) + 1;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $result = $stmt->fetch();

        return $result ?: null;
    }
}