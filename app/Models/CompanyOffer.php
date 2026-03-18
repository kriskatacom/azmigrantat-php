<?php

namespace App\Models;

class CompanyOffer extends Model
{
    protected string $table = 'offers';

    public function getAllWithRelations(array $options = [], array $searchColumns = ['title']): array
    {
        $sql = "SELECT o.*, c.name as company_name 
            FROM {$this->table} o
            LEFT JOIN companies c ON o.company_id = c.id";

        $params = [];
        $whereClauses = [];

        if (!empty($options['search']) && !empty($searchColumns)) {
            $searchTerms = [];
            foreach ($searchColumns as $index => $column) {
                $paramName = "search_" . $index;
                $searchTerms[] = "o.$column LIKE :$paramName";
                $params[$paramName] = "%{$options['search']}%";
            }
            $whereClauses[] = "(" . implode(' OR ', $searchTerms) . ")";
        }

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }

        $sql .= " ORDER BY o.created_at DESC";

        if (isset($options['limit'])) {
            $sql .= " LIMIT " . (int)$options['limit'] . " OFFSET " . (int)($options['offset'] ?? 0);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
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