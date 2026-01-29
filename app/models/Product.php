<?php

class Product extends Model
{
    protected $table = 'products';

    public function getAllWithWarehouse($warehouseId = null)
    {
        $sql = "SELECT p.*, w.name as warehouse_name, pt.name as product_type_name
                FROM {$this->table} p
                LEFT JOIN warehouses w ON p.warehouse_id = w.id
                LEFT JOIN product_types pt ON p.product_type_id = pt.id";
        $params = [];

        if ($warehouseId) {
            $sql .= " WHERE p.warehouse_id = ?";
            $params[] = $warehouseId;
        }

        $sql .= " ORDER BY p.id DESC";
        return $this->query($sql, $params);
    }

    public function getAllActive($warehouseId = null)
    {
        $sql = "SELECT p.*, pt.name as product_type_name
                FROM {$this->table} p
                LEFT JOIN product_types pt ON p.product_type_id = pt.id
                WHERE p.status = 'active'";
        $params = [];

        if ($warehouseId) {
            $sql .= " AND p.warehouse_id = ?";
            $params[] = $warehouseId;
        }

        $sql .= " ORDER BY p.name";
        return $this->query($sql, $params);
    }

    public function getByWarehouse($warehouseId)
    {
        $sql = "SELECT p.*, pt.name as product_type_name
                FROM {$this->table} p
                LEFT JOIN product_types pt ON p.product_type_id = pt.id
                WHERE p.warehouse_id = ? AND p.status = 'active'
                ORDER BY p.name";
        return $this->query($sql, [$warehouseId]);
    }

    public function search($keyword, $warehouseId = null)
    {
        $sql = "SELECT p.*, w.name as warehouse_name, pt.name as product_type_name
                FROM {$this->table} p
                LEFT JOIN warehouses w ON p.warehouse_id = w.id
                LEFT JOIN product_types pt ON p.product_type_id = pt.id
                WHERE (p.code LIKE ? OR p.name LIKE ?)";
        
        $params = ["%{$keyword}%", "%{$keyword}%"];
        
        if ($warehouseId) {
            $sql .= " AND p.warehouse_id = ?";
            $params[] = $warehouseId;
        }
        
        $sql .= " ORDER BY p.id DESC";
        return $this->query($sql, $params);
    }

    public function findWithType($id)
    {
        $sql = "SELECT p.*, pt.name as product_type_name
                FROM {$this->table} p
                LEFT JOIN product_types pt ON p.product_type_id = pt.id
                WHERE p.id = ?";
        $result = $this->query($sql, [$id]);
        return $result ? $result[0] : null;
    }

    public function findByCode($code)
    {
        $sql = "SELECT * FROM {$this->table} WHERE code = ?";
        $result = $this->query($sql, [$code]);
        return $result ? $result[0] : null;
    }

    public function hasInventory($productId)
    {
        $sql = "SELECT COUNT(*) as count FROM inventory WHERE product_id = ? AND quantity > 0";
        $result = $this->query($sql, [$productId]);
        return $result && $result[0]['count'] > 0;
    }

    public function belongsToWarehouse($productId, $warehouseId)
    {
        $sql = "SELECT id FROM {$this->table} WHERE id = ? AND warehouse_id = ?";
        $result = $this->query($sql, [$productId, $warehouseId]);
        return !empty($result);
    }
}
