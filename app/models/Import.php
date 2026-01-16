<?php

class Import extends Model
{
    protected $table = 'imports';

    public function getAllWithDetails()
    {
        $sql = "SELECT i.*, w.name as warehouse_name, s.name as supplier_name, u.full_name as created_by_name
                FROM {$this->table} i
                LEFT JOIN warehouses w ON i.warehouse_id = w.id
                LEFT JOIN suppliers s ON i.supplier_id = s.id
                LEFT JOIN users u ON i.created_by = u.id
                ORDER BY i.id DESC";
        return $this->query($sql);
    }

    public function getWithDetails($id)
    {
        $sql = "SELECT i.*, w.name as warehouse_name, s.name as supplier_name, u.full_name as created_by_name
                FROM {$this->table} i
                LEFT JOIN warehouses w ON i.warehouse_id = w.id
                LEFT JOIN suppliers s ON i.supplier_id = s.id
                LEFT JOIN users u ON i.created_by = u.id
                WHERE i.id = ?";
        $result = $this->query($sql, [$id]);
        return $result ? $result[0] : null;
    }

    public function search($keyword)
    {
        $sql = "SELECT i.*, w.name as warehouse_name, s.name as supplier_name
                FROM {$this->table} i
                LEFT JOIN warehouses w ON i.warehouse_id = w.id
                LEFT JOIN suppliers s ON i.supplier_id = s.id
                WHERE i.code LIKE ? OR s.name LIKE ?
                ORDER BY i.id DESC";
        $param = "%{$keyword}%";
        return $this->query($sql, [$param, $param]);
    }

    public function getByDateRange($startDate, $endDate)
    {
        $sql = "SELECT i.*, w.name as warehouse_name, s.name as supplier_name, u.full_name as created_by_name
                FROM {$this->table} i
                LEFT JOIN warehouses w ON i.warehouse_id = w.id
                LEFT JOIN suppliers s ON i.supplier_id = s.id
                LEFT JOIN users u ON i.created_by = u.id
                WHERE i.import_date BETWEEN ? AND ?
                ORDER BY i.import_date DESC";
        return $this->query($sql, [$startDate, $endDate]);
    }

    public function getStatsByDateRange($startDate, $endDate)
    {
        $sql = "SELECT 
                    COUNT(*) as total_imports,
                    SUM(total_amount) as total_value,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_imports,
                    COUNT(DISTINCT supplier_id) as total_suppliers
                FROM {$this->table}
                WHERE import_date BETWEEN ? AND ?";
        $result = $this->query($sql, [$startDate, $endDate]);
        return $result ? $result[0] : null;
    }
}
