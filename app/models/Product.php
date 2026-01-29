<?php

class Product extends Model
{
    protected $table = 'products';

    private function getLatestTypeJoinSql()
    {
        return "LEFT JOIN (
                    SELECT t.product_id, pt.name as latest_product_type_name
                    FROM (
                        SELECT product_id, product_type_id, event_date, event_id,
                               ROW_NUMBER() OVER (PARTITION BY product_id ORDER BY event_date DESC, event_id DESC) as rn
                        FROM (
                            SELECT id.product_id, id.product_type_id, i.import_date as event_date, id.id as event_id
                            FROM import_details id
                            INNER JOIN imports i ON id.import_id = i.id
                            WHERE id.product_type_id IS NOT NULL
                            UNION ALL
                            SELECT ed.product_id, ed.product_type_id, e.export_date as event_date, ed.id as event_id
                            FROM export_details ed
                            INNER JOIN exports e ON ed.export_id = e.id
                            WHERE ed.product_type_id IS NOT NULL
                        ) x
                    ) t
                    LEFT JOIN product_types pt ON t.product_type_id = pt.id
                    WHERE t.rn = 1
                ) lt ON p.id = lt.product_id";
    }

    public function getAllWithWarehouse($warehouseId = null)
    {
        $sql = "SELECT p.*, w.name as warehouse_name, lt.latest_product_type_name
                FROM {$this->table} p
                LEFT JOIN warehouses w ON p.warehouse_id = w.id
                " . $this->getLatestTypeJoinSql();
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
        $sql = "SELECT p.*, lt.latest_product_type_name
                FROM {$this->table} p
                " . $this->getLatestTypeJoinSql() . "
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
        $sql = "SELECT p.*, lt.latest_product_type_name
                FROM {$this->table} p
                " . $this->getLatestTypeJoinSql() . "
                WHERE p.warehouse_id = ? AND p.status = 'active'
                ORDER BY p.name";
        return $this->query($sql, [$warehouseId]);
    }

    public function search($keyword, $warehouseId = null)
    {
        $sql = "SELECT p.*, w.name as warehouse_name, lt.latest_product_type_name
                FROM {$this->table} p
                LEFT JOIN warehouses w ON p.warehouse_id = w.id
                " . $this->getLatestTypeJoinSql() . "
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
