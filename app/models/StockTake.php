<?php

class StockTake extends Model
{
    protected $table = 'stock_takes';

    public function getAllWithDetails()
    {
        $sql = "SELECT st.*, w.name as warehouse_name, u.full_name as created_by_name
                FROM {$this->table} st
                LEFT JOIN warehouses w ON st.warehouse_id = w.id
                LEFT JOIN users u ON st.created_by = u.id
                ORDER BY st.id DESC";
        return $this->query($sql);
    }

    public function getWithDetails($id)
    {
        $sql = "SELECT st.*, w.name as warehouse_name, 
                       u.full_name as created_by_name, a.full_name as approved_by_name
                FROM {$this->table} st
                LEFT JOIN warehouses w ON st.warehouse_id = w.id
                LEFT JOIN users u ON st.created_by = u.id
                LEFT JOIN users a ON st.approved_by = a.id
                WHERE st.id = ?";
        $result = $this->query($sql, [$id]);
        return $result ? $result[0] : null;
    }

    public function getByWarehouse($warehouseId)
    {
        $sql = "SELECT st.*, w.name as warehouse_name, u.full_name as created_by_name
                FROM {$this->table} st
                LEFT JOIN warehouses w ON st.warehouse_id = w.id
                LEFT JOIN users u ON st.created_by = u.id
                WHERE st.warehouse_id = ?
                ORDER BY st.id DESC";
        return $this->query($sql, [$warehouseId]);
    }

    public function approve($id, $userId)
    {
        $sql = "UPDATE {$this->table} 
                SET status = 'completed', approved_by = ?, approved_at = NOW() 
                WHERE id = ?";
        return $this->query($sql, [$userId, $id]);
    }

    public function generateCode()
    {
        $sql = "SELECT code FROM {$this->table} ORDER BY id DESC LIMIT 1";
        $result = $this->query($sql);
        
        if ($result && count($result) > 0) {
            $lastCode = $result[0]['code'];
            $number = intval(substr($lastCode, 2)) + 1;
        } else {
            $number = 1;
        }
        
        return 'KK' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function getByDateRange($startDate, $endDate)
    {
        $sql = "SELECT st.*, w.name as warehouse_name, u.full_name as created_by_name
                FROM {$this->table} st
                LEFT JOIN warehouses w ON st.warehouse_id = w.id
                LEFT JOIN users u ON st.created_by = u.id
                WHERE st.stock_take_date BETWEEN ? AND ?
                ORDER BY st.stock_take_date DESC";
        return $this->query($sql, [$startDate, $endDate]);
    }
}
