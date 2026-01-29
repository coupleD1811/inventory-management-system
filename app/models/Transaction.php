<?php

class Transaction extends Model
{
    protected $table = 'transactions';

    /**
     * Get stock card (transaction history) for a product in a warehouse
     */
    public function getStockCard($warehouseId, $productId, $startDate = null, $endDate = null)
    {
        $sql = "SELECT t.*, w.name as warehouse_name, p.name as product_name, p.code as product_code,
                       pt.name as product_type_name,
                       u.full_name as created_by_name,
                       CASE 
                           WHEN t.reference_type = 'import' THEN (
                               SELECT id.unit_price 
                               FROM import_details id 
                               WHERE id.import_id = t.reference_id AND id.product_id = t.product_id 
                               LIMIT 1
                           )
                           WHEN t.reference_type = 'export' THEN (
                               SELECT ed.unit_price 
                               FROM export_details ed 
                               WHERE ed.export_id = t.reference_id AND ed.product_id = t.product_id 
                               LIMIT 1
                           )
                           ELSE NULL
                       END as unit_price
                FROM {$this->table} t
                INNER JOIN warehouses w ON t.warehouse_id = w.id
                INNER JOIN products p ON t.product_id = p.id
                LEFT JOIN product_types pt ON p.product_type_id = pt.id
                LEFT JOIN users u ON t.created_by = u.id
                WHERE t.warehouse_id = ? AND t.product_id = ?";
        
        $params = [$warehouseId, $productId];
        
        if ($startDate) {
            $sql .= " AND t.transaction_date >= ?";
            $params[] = $startDate . ' 00:00:00';
        }
        
        if ($endDate) {
            $sql .= " AND t.transaction_date <= ?";
            $params[] = $endDate . ' 23:59:59';
        }
        
        $sql .= " ORDER BY t.transaction_date ASC, t.id ASC";
        
        return $this->query($sql, $params);
    }

    /**
     * Log a transaction
     */
    public function logTransaction($data)
    {
        return $this->create($data);
    }

    /**
     * Get transactions by reference (import/export)
     */
    public function getByReference($referenceType, $referenceId)
    {
        $sql = "SELECT t.*, p.name as product_name, p.code as product_code, pt.name as product_type_name
                FROM {$this->table} t
                INNER JOIN products p ON t.product_id = p.id
                LEFT JOIN product_types pt ON p.product_type_id = pt.id
                WHERE t.reference_type = ? AND t.reference_id = ?
                ORDER BY t.id";
        return $this->query($sql, [$referenceType, $referenceId]);
    }

    /**
     * Get recent transactions
     */
    public function getRecent($limit = 50)
    {
        $sql = "SELECT t.*, w.name as warehouse_name, p.name as product_name, p.code as product_code, pt.name as product_type_name
                FROM {$this->table} t
                INNER JOIN warehouses w ON t.warehouse_id = w.id
                INNER JOIN products p ON t.product_id = p.id
                LEFT JOIN product_types pt ON p.product_type_id = pt.id
                ORDER BY t.transaction_date DESC, t.id DESC
                LIMIT ?";
        return $this->query($sql, [$limit]);
    }
}
