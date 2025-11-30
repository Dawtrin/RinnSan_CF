<?php

namespace Rinnsan\RinnSanWeb\Models;

use Rinnsan\RinnSanWeb\Core\Database;

class Model
{
    protected $table;

    public static function all()
    {
        $instance = new static;
        $sql = "SELECT * FROM {$instance->table}";
        return Database::fetchAll($sql);
    }

    public static function find($id)
    {
        $instance = new static;
        $sql = "SELECT * FROM {$instance->table} WHERE id = ?";
        return Database::fetch($sql, [$id]);
    }

    public static function where($column, $operator, $value)
    {
        $instance = new static;
        $sql = "SELECT * FROM {$instance->table} WHERE {$column} {$operator} ?";
        return Database::fetchAll($sql, [$value]);
    }

    public static function create($data)
    {
        $instance = new static;
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO {$instance->table} ({$columns}) VALUES ({$placeholders})";
        return Database::query($sql, array_values($data));
    }

    public static function update($id, $data)
    {
        $instance = new static;
        $sets = implode(', ', array_map(fn($k) => "{$k} = ?", array_keys($data)));
        $sql = "UPDATE {$instance->table} SET {$sets} WHERE id = ?";
        $values = array_merge(array_values($data), [$id]);
        return Database::query($sql, $values);
    }

    public static function delete($id)
    {
        $instance = new static;
        $sql = "DELETE FROM {$instance->table} WHERE id = ?";
        return Database::query($sql, [$id]);
    }

    /**
     * Pagination
     */
    public static function paginate($page = 1, $perPage = 20, $conditions = [], $orderBy = 'id DESC')
    {
        $instance = new static;
        $offset = ($page - 1) * $perPage;
        
        $where = '';
        $params = [];
        
        if (!empty($conditions)) {
            $wheres = [];
            foreach ($conditions as $key => $value) {
                if (is_array($value)) {
                    $wheres[] = "{$key} {$value[0]} ?";
                    $params[] = $value[1];
                } else {
                    $wheres[] = "{$key} = ?";
                    $params[] = $value;
                }
            }
            $where = 'WHERE ' . implode(' AND ', $wheres);
        }
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$instance->table} {$where}";
        $totalResult = Database::fetch($countSql, $params);
        $total = $totalResult['total'] ?? 0;
        
        // Get data
        $sql = "SELECT * FROM {$instance->table} {$where} ORDER BY {$orderBy} 
                OFFSET {$offset} ROWS FETCH NEXT {$perPage} ROWS ONLY";
        $data = Database::fetchAll($sql, $params);
        
        return [
            'data' => $data,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => ceil($total / $perPage),
                'from' => $offset + 1,
                'to' => min($offset + $perPage, $total)
            ]
        ];
    }

    /**
     * Count records
     */
    public static function count($conditions = [])
    {
        $instance = new static;
        $where = '';
        $params = [];
        
        if (!empty($conditions)) {
            $wheres = [];
            foreach ($conditions as $key => $value) {
                if (is_array($value)) {
                    $wheres[] = "{$key} {$value[0]} ?";
                    $params[] = $value[1];
                } else {
                    $wheres[] = "{$key} = ?";
                    $params[] = $value;
                }
            }
            $where = 'WHERE ' . implode(' AND ', $wheres);
        }
        
        $sql = "SELECT COUNT(*) as total FROM {$instance->table} {$where}";
        $result = Database::fetch($sql, $params);
        return $result['total'] ?? 0;
    }

    /**
     * Find by multiple conditions
     */
    public static function findBy($conditions)
    {
        $instance = new static;
        $wheres = [];
        $params = [];
        
        foreach ($conditions as $key => $value) {
            if (is_array($value)) {
                $wheres[] = "{$key} {$value[0]} ?";
                $params[] = $value[1];
            } else {
                $wheres[] = "{$key} = ?";
                $params[] = $value;
            }
        }
        
        $where = implode(' AND ', $wheres);
        $sql = "SELECT * FROM {$instance->table} WHERE {$where}";
        return Database::fetch($sql, $params);
    }

    /**
     * Find all by multiple conditions
     */
    public static function findAllBy($conditions, $orderBy = 'id DESC', $limit = null)
    {
        $instance = new static;
        $wheres = [];
        $params = [];
        
        foreach ($conditions as $key => $value) {
            if (is_array($value)) {
                $wheres[] = "{$key} {$value[0]} ?";
                $params[] = $value[1];
            } else {
                $wheres[] = "{$key} = ?";
                $params[] = $value;
            }
        }
        
        $where = implode(' AND ', $wheres);
        $sql = "SELECT * FROM {$instance->table} WHERE {$where} ORDER BY {$orderBy}";
        
        if ($limit) {
            $sql .= " OFFSET 0 ROWS FETCH NEXT {$limit} ROWS ONLY";
        }
        
        return Database::fetchAll($sql, $params);
    }
}
