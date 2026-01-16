<?php

class Warehouse extends Model
{
    protected $table = 'warehouses';
    private $primaryCodes = ['KHO-NNL', 'KHO-NL', 'KHO-PT', 'KHO-TP'];

    public function getAll()
    {
        $sql = "SELECT w.*, u.full_name as manager_full_name 
                FROM {$this->table} w
                LEFT JOIN users u ON w.manager_id = u.id
                ORDER BY w.name";
        return $this->query($sql);
    }

    public function getAllActive()
    {
        $sql = "SELECT w.*, u.full_name as manager_full_name 
                FROM {$this->table} w
                LEFT JOIN users u ON w.manager_id = u.id
                WHERE w.status = 'active' 
                ORDER BY w.name";
        return $this->query($sql);
    }

    public function getPrimaryWarehouses()
    {
        $placeholders = implode(',', array_fill(0, count($this->primaryCodes), '?'));
        $sql = "SELECT w.*, u.full_name as manager_full_name
                FROM {$this->table} w
                LEFT JOIN users u ON w.manager_id = u.id
                WHERE w.status = 'active'
                AND w.code IN ({$placeholders})
                ORDER BY w.name";
        return $this->query($sql, $this->primaryCodes);
    }

    public function search($keyword)
    {
        $sql = "SELECT w.*, u.full_name as manager_full_name 
                FROM {$this->table} w
                LEFT JOIN users u ON w.manager_id = u.id
                WHERE w.code LIKE ? OR w.name LIKE ? OR u.full_name LIKE ?
                ORDER BY w.name";
        $param = "%{$keyword}%";
        return $this->query($sql, [$param, $param, $param]);
    }

    public function findByCode($code)
    {
        $sql = "SELECT * FROM {$this->table} WHERE code = ?";
        $result = $this->query($sql, [$code]);
        return $result ? $result[0] : null;
    }

    public function hasInventory($warehouseId)
    {
        $sql = "SELECT COUNT(*) as count FROM inventory WHERE warehouse_id = ? AND quantity > 0";
        $result = $this->query($sql, [$warehouseId]);
        return $result && $result[0]['count'] > 0;
    }
}
