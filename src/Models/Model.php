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
}
