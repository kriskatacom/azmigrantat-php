<?php

namespace App\Models;

class Category extends Model
{
    protected string $table = 'categories';
    private ?int $filterParentId = null;

    public function __construct()
    {
        parent::__construct();
    }

    public function prepareData(array $data): array
    {
        $allowed = [
            'name',
            'slug',
            'heading',
            'excerpt',
            'content',
            'image_url',
            'companies_background_url',
            'parent_id',
            'sort_order',
            'is_active'
        ];

        $prepared = array_intersect_key($data, array_flip($allowed));

        if (empty($prepared['slug']) && !empty($prepared['name'])) {
            $prepared['slug'] = $this->generateSlug($prepared['name']);
        }

        if (empty($prepared['heading']) && !empty($prepared['name'])) {
            $prepared['heading'] = $prepared['name'];
        }

        if (!isset($prepared['parent_id']) || $prepared['parent_id'] === '') {
            $prepared['parent_id'] = null;
        } else {
            $prepared['parent_id'] = (int)$prepared['parent_id'];
        }

        $prepared['sort_order'] = (int)($prepared['sort_order'] ?? 0);
        $prepared['is_active'] = isset($data['is_active']) ? 1 : 0;

        return $prepared;
    }

    public function getTree(): array
    {
        $all = $this->all(['order' => 'name ASC, parent_id ASC, sort_order ASC']);
        $tree = [];
        $references = [];

        foreach ($all as $category) {
            $category['children'] = [];
            $id = $category['id'];
            $references[$id] = $category;

            if ($category['parent_id'] === null) {
                $tree[] = &$references[$id];
            } else {
                if (isset($references[$category['parent_id']])) {
                    $references[$category['parent_id']]['children'][] = &$references[$id];
                }
            }
        }

        return $tree;
    }

    public function setFilterParent(?int $parentId): void
    {
        $this->filterParentId = $parentId;
    }

    public function count(): int
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE ";
        $sql .= ($this->filterParentId === null) ? "parent_id IS NULL" : "parent_id = :pid";

        $stmt = $this->db->prepare($sql);
        if ($this->filterParentId !== null) {
            $stmt->bindValue(':pid', $this->filterParentId, \PDO::PARAM_INT);
        }
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    public function getByParentPaginated(?int $parentId, int $limit, int $offset): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE ";
        $sql .= ($parentId === null) ? "parent_id IS NULL " : "parent_id = :pid ";
        $sql .= "ORDER BY sort_order ASC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        if ($parentId !== null) $stmt->bindValue(':pid', $parentId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function hasChildren(int $id): bool
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE parent_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return (int)$stmt->fetchColumn() > 0;
    }

    public function getBreadcrumbs(int $id): array
    {
        $crumbs = [];
        $currentId = $id;

        while ($currentId !== null) {
            $sql = "SELECT id, name, parent_id FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $currentId]);
            $category = $stmt->fetch();

            if ($category) {
                array_unshift($crumbs, [
                    'label' => $category['name'],
                    'url'   => '/admin/categories?parent_id=' . $category['id']
                ]);
                $currentId = $category['parent_id'];
            } else {
                break;
            }
        }

        return $crumbs;
    }
}
