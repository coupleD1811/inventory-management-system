<?php

class PurchaseOrder extends Model
{
    protected $table = 'purchase_orders';

    public function getAllWithDetails()
    {
        $sql = "SELECT po.*, s.name as supplier_name, u.full_name as created_by_name
                FROM {$this->table} po
                LEFT JOIN suppliers s ON po.supplier_id = s.id
                LEFT JOIN users u ON po.created_by = u.id
                ORDER BY po.id DESC";
        return $this->query($sql);
    }

    public function getWithDetails($id)
    {
        $sql = "SELECT po.*, s.name as supplier_name, s.contact_person, s.phone as supplier_phone, 
                       u.full_name as created_by_name, a.full_name as approved_by_name
                FROM {$this->table} po
                LEFT JOIN suppliers s ON po.supplier_id = s.id
                LEFT JOIN users u ON po.created_by = u.id
                LEFT JOIN users a ON po.approved_by = a.id
                WHERE po.id = ?";
        $result = $this->query($sql, [$id]);
        return $result ? $result[0] : null;
    }

    public function getByStatus($status)
    {
        $sql = "SELECT po.*, s.name as supplier_name, u.full_name as created_by_name
                FROM {$this->table} po
                LEFT JOIN suppliers s ON po.supplier_id = s.id
                LEFT JOIN users u ON po.created_by = u.id
                WHERE po.status = ?
                ORDER BY po.id DESC";
        return $this->query($sql, [$status]);
    }

    public function approve($id, $userId)
    {
        $sql = "UPDATE {$this->table} 
                SET status = 'confirmed', approved_by = ?, approved_at = NOW() 
                WHERE id = ?";
        return $this->query($sql, [$userId, $id]);
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE {$this->table} SET status = ? WHERE id = ?";
        return $this->query($sql, [$status, $id]);
    }

    public function search($keyword)
    {
        $sql = "SELECT po.*, s.name as supplier_name
                FROM {$this->table} po
                LEFT JOIN suppliers s ON po.supplier_id = s.id
                WHERE po.code LIKE ? OR s.name LIKE ?
                ORDER BY po.id DESC";
        $param = "%{$keyword}%";
        return $this->query($sql, [$param, $param]);
    }

    public function generateCode()
    {
        $sql = "SELECT code FROM {$this->table} ORDER BY id DESC LIMIT 1";
        $result = $this->query($sql);
        
        if ($result && count($result) > 0) {
            $lastCode = $result[0]['code'];
            $number = intval(substr($lastCode, 3)) + 1;
        } else {
            $number = 1;
        }
        
        return 'DDH' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function getByDateRange($startDate, $endDate)
    {
        $sql = "SELECT po.*, s.name as supplier_name, u.full_name as created_by_name
                FROM {$this->table} po
                LEFT JOIN suppliers s ON po.supplier_id = s.id
                LEFT JOIN users u ON po.created_by = u.id
                WHERE po.order_date BETWEEN ? AND ?
                ORDER BY po.order_date DESC";
        return $this->query($sql, [$startDate, $endDate]);
    }

    public function getStatsByDateRange($startDate, $endDate)
    {
        $sql = "SELECT 
                    COUNT(*) as total_orders,
                    SUM(total_amount) as total_value,
                    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_orders,
                    SUM(CASE WHEN status = 'received' THEN 1 ELSE 0 END) as received_orders
                FROM {$this->table}
                WHERE order_date BETWEEN ? AND ?";
        $result = $this->query($sql, [$startDate, $endDate]);
        return $result ? $result[0] : null;
    }
}
