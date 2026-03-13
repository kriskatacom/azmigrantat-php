<?php

namespace App\Models;

use App\Services\TranslateService;
use PDO;

class Translation extends Model
{
    protected string $table = 'translations';

    public function getAllByLanguage(string $langCode): array
    {
        $sql = "SELECT translation_key, translation_value FROM {$this->table} WHERE lang_code = :lang";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['lang' => $langCode]);

        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public function saveTranslation(string $lang, string $key, string $value): bool
    {
        $sql = "INSERT INTO {$this->table} (lang_code, translation_key, translation_value) 
                VALUES (:lang, :key, :value)
                ON DUPLICATE KEY UPDATE translation_value = :value_update";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'lang'         => $lang,
            'key'          => $key,
            'value'        => $value,
            'value_update' => $value
        ]);
    }

    public function addFullTranslation($key, $translations)
    {
        $sourceText = !empty($translations['bg']) ? $translations['bg'] : '';

        if (empty($sourceText)) {
            foreach ($translations as $val) {
                if (!empty($val)) {
                    $sourceText = $val;
                    break;
                }
            }
        }

        foreach ($translations as $langCode => $value) {
            $finalValue = trim($value ?? '');

            $sql = "INSERT INTO {$this->table} (translation_key, lang_code, translation_value) 
                VALUES (:key, :lang, :val)
                ON DUPLICATE KEY UPDATE translation_value = :val_update";

            $this->db->prepare($sql)->execute([
                'key'        => $key,
                'lang'       => $langCode,
                'val'        => $finalValue,
                'val_update' => $finalValue
            ]);
        }

        return true;
    }

    /**
     * Масов запис на преводи за конкретен обект (entity)
     */
    public function updateEntityTranslations(string $entity, int $id, array $data): bool
    {
        if (empty($data)) return false;

        $firstLangData = current($data) ?: [];
        $fields = array_keys($firstLangData);

        foreach ($fields as $field) {
            $key = "{$entity}_{$id}_{$field}";
            $fieldTranslations = [];

            foreach ($data as $langCode => $values) {
                if (isset($values[$field])) {
                    $fieldTranslations[$langCode] = $values[$field];
                }
            }

            $this->addFullTranslation($key, $fieldTranslations);
        }

        return true;
    }

    public function all(array $options = []): array
    {
        $columns = isset($options['columns']) ? implode(', ', $options['columns']) : '*';
        $sql = "SELECT {$columns} FROM {$this->table}";
        $params = [];
        $whereClauses = [];

        if (isset($options['where']) && is_array($options['where'])) {
            foreach ($options['where'] as $column => $value) {
                if ($value === null) {
                    $whereClauses[] = "{$column} IS NULL";
                } else {
                    $paramKey = "where_" . str_replace(['.', '-'], '_', $column);

                    // Проверка за LIKE оператор
                    if (isset($options['like']) && $options['like'] === true) {
                        $whereClauses[] = "{$column} LIKE :{$paramKey}";
                    } else {
                        $whereClauses[] = "{$column} = :{$paramKey}";
                    }

                    $params[$paramKey] = $value;
                }
            }
        }

        if (!empty($options['search'])) {
            $whereClauses[] = "(translation_key LIKE :search1 OR translation_value LIKE :search2)";
            $params['search1'] = "%{$options['search']}%";
            $params['search2'] = "%{$options['search']}%";
        }

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }

        $orderBy = $options['order'] ?? 'id DESC';
        $sql .= " ORDER BY {$orderBy}";

        if (isset($options['limit'])) {
            $sql .= " LIMIT " . (int)$options['limit'];
            if (isset($options['offset'])) {
                $sql .= " OFFSET " . (int)$options['offset'];
            }
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count(array $options = []): int
    {
        $totalLangsCount = count(\App\Services\HelperService::AVAILABLE_LANGUAGES);

        $sql = "SELECT COUNT(DISTINCT translation_key) FROM {$this->table}";
        $params = [];
        $whereClauses = [];

        if (isset($options['where']) && is_array($options['where'])) {
            foreach ($options['where'] as $column => $value) {
                if ($column === 'lang_code') continue;

                $paramKey = "where_" . str_replace('.', '_', $column);
                $whereClauses[] = "{$column} = :{$paramKey}";
                $params[$paramKey] = $value;
            }
        }

        if (!empty($options['search'])) {
            $whereClauses[] = "(translation_key LIKE :search1 OR translation_value LIKE :search2)";
            $params['search1'] = "%{$options['search']}%";
            $params['search2'] = "%{$options['search']}%";
        }

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getUniqueKeys(array $options = []): array
    {
        $currentLang = $options['where']['lang_code'] ?? 'bg';
        $totalLangsCount = count(\App\Services\HelperService::AVAILABLE_LANGUAGES);

        $sql = "SELECT 
                translation_key, 
                MAX(id) as id, 
                MAX(CASE WHEN lang_code = :target_lang THEN translation_value END) as translation_value,
                COUNT(CASE WHEN translation_value != '' AND translation_value IS NOT NULL THEN 1 END) as completed_count
            FROM {$this->table}";

        $params = ['target_lang' => $currentLang];
        $whereClauses = [];

        if (!empty($options['search'])) {
            $whereClauses[] = "(translation_key LIKE :search1 OR translation_value LIKE :search2)";
            $params['search1'] = "%{$options['search']}%";
            $params['search2'] = "%{$options['search']}%";
        }

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }

        $sql .= " GROUP BY translation_key";

        if (!empty($options['incomplete'])) {
            $sql .= " HAVING completed_count < :total_langs";
            $params['total_langs'] = $totalLangsCount;
        }

        $sql .= " ORDER BY " . ($options['order'] ?? 'translation_key ASC');

        if (isset($options['limit'])) {
            $sql .= " LIMIT " . (int)$options['limit'] . " OFFSET " . (int)($options['offset'] ?? 0);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getAvailableLanguages(): array
    {
        $sql = "SELECT DISTINCT lang_code FROM {$this->table} ORDER BY lang_code ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
