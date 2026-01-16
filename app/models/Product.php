<?php

class Product extends Model
{
    protected $table = 'products';

    public function getAllWithWarehouse($warehouseId = null)
    {
        $sql = "SELECT p.*, w.name as warehouse_name
                FROM {$this->table} p
                LEFT JOIN warehouses w ON p.warehouse_id = w.id";
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
        $sql = "SELECT * FROM {$this->table} WHERE status = 'active'";
        $params = [];

        if ($warehouseId) {
            $sql .= " AND warehouse_id = ?";
            $params[] = $warehouseId;
        }

        $sql .= " ORDER BY name";
        return $this->query($sql, $params);
    }

    public function getByWarehouse($warehouseId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE warehouse_id = ? AND status = 'active' ORDER BY name";
        return $this->query($sql, [$warehouseId]);
    }

    public function search($keyword, $warehouseId = null)
    {
        $sql = "SELECT p.*, w.name as warehouse_name
                FROM {$this->table} p
                LEFT JOIN warehouses w ON p.warehouse_id = w.id
                WHERE (p.code LIKE ? OR p.name LIKE ?)";
        
        $params = ["%{$keyword}%", "%{$keyword}%"];
        
        if ($warehouseId) {
            $sql .= " AND p.warehouse_id = ?";
            $params[] = $warehouseId;
        }
        
        $sql .= " ORDER BY p.id DESC";
        return $this->query($sql, $params);
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
