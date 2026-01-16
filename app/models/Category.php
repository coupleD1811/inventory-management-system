<?php

class Category extends Model
{
    protected $table = 'categories';

    public function getAllActive()
    {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY name";
        return $this->query($sql);
    }

    public function search($keyword)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE code LIKE ? OR name LIKE ? 
                ORDER BY name";
        $param = "%{$keyword}%";
        return $this->query($sql, [$param, $param]);
    }

    public function findByCode($code)
    {
        $sql = "SELECT * FROM {$this->table} WHERE code = ?";
        $result = $this->query($sql, [$code]);
        return $result ? $result[0] : null;
    }

    public function hasProducts($categoryId)
    {
        $sql = "SELECT COUNT(*) as count FROM products WHERE category_id = ?";
        $result = $this->query($sql, [$categoryId]);
        return $result && $result[0]['count'] > 0;
    }
}
