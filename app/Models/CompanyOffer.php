<?php

namespace App\Models;

class CompanyOffer extends Model
{
    protected string $table = 'offers';

    public function getAllWithRelations(int $limit = 10, int $offset = 0): array
    {
        $sql = "SELECT co.*, 
                       c.name as company_name, 
                       u.name as user_name
                FROM {$this->table} co
                LEFT JOIN companies c ON co.company_id = c.id
                LEFT JOIN users u ON co.user_id = u.id
                ORDER BY co.sort_order ASC, co.created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getByCompany(int $companyId): array
    {
        return $this->where('company_id', $companyId, 'sort_order ASC');
    }

    public function prepareData(array $data): array
    {
        $data['company_id'] = (int)$data['company_id'];
        $data['user_id']    = $data['user_id'] ?? null;
        $data['name']       = htmlspecialchars(trim($data['name']));
        $data['sort_order'] = (int)($data['sort_order'] ?? 0);

        $allowedStatuses = ['active', 'draft', 'pending', 'canceled'];
        if (!isset($data['status']) || !in_array($data['status'], $allowedStatuses)) {
            $data['status'] = 'draft';
        }

        unset($data['image_url'], $data['auto_user_id']);

        return $data;
    }

    public function getNextSortOrder(): int
    {
        $max = $this->max('sort_order');
        return $max ? (int)$max + 1 : 1;
    }
}
