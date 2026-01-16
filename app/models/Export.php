<?php

class Export extends Model
{
    protected $table = 'exports';

    public function getAllWithDetails()
    {
        $sql = "SELECT e.*, w.name as warehouse_name, u.full_name as created_by_name
                FROM {$this->table} e
                LEFT JOIN warehouses w ON e.warehouse_id = w.id
                LEFT JOIN users u ON e.created_by = u.id
                ORDER BY e.id DESC";
        return $this->query($sql);
    }

    public function getWithDetails($id)
    {
        $sql = "SELECT e.*, w.name as warehouse_name, u.full_name as created_by_name
                FROM {$this->table} e
                LEFT JOIN warehouses w ON e.warehouse_id = w.id
                LEFT JOIN users u ON e.created_by = u.id
                WHERE e.id = ?";
        $result = $this->query($sql, [$id]);
        return $result ? $result[0] : null;
    }

    public function search($keyword)
    {
        $sql = "SELECT e.*, w.name as warehouse_name
                FROM {$this->table} e
                LEFT JOIN warehouses w ON e.warehouse_id = w.id
                WHERE e.code LIKE ? OR e.customer_name LIKE ?
                ORDER BY e.id DESC";
        $param = "%{$keyword}%";
        return $this->query($sql, [$param, $param]);
    }

    public function getByDateRange($startDate, $endDate)
    {
        $sql = "SELECT e.*, w.name as warehouse_name, u.full_name as created_by_name
                FROM {$this->table} e
                LEFT JOIN warehouses w ON e.warehouse_id = w.id
                LEFT JOIN users u ON e.created_by = u.id
                WHERE e.export_date BETWEEN ? AND ?
                ORDER BY e.export_date DESC";
        return $this->query($sql, [$startDate, $endDate]);
    }

    public function getStatsByDateRange($startDate, $endDate)
    {
        $sql = "SELECT 
                    COUNT(*) as total_exports,
                    SUM(total_amount) as total_value,
                    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_exports,
                    SUM(CASE WHEN export_type = 'sale' THEN 1 ELSE 0 END) as sale_exports,
                    SUM(CASE WHEN export_type = 'internal' THEN 1 ELSE 0 END) as internal_exports
                FROM {$this->table}
                WHERE export_date BETWEEN ? AND ?";
        $result = $this->query($sql, [$startDate, $endDate]);
        return $result ? $result[0] : null;
    }

    /**
     * Lấy báo cáo lời/lỗ theo khoảng thời gian
     */
    public function getProfitLossReport($startDate, $endDate, $warehouseId = null)
    {
        $sql = "SELECT 
                    e.id,
                    e.code,
                    e.export_date,
                    e.warehouse_id,
                    w.name as warehouse_name,
                    e.customer_name,
                    e.export_type,
                    ed.product_id,
                    p.code as product_code,
                    p.name as product_name,
                    ed.quantity,
                    ed.unit_price as sell_price,
                    ed.cost_price,
                    ed.profit as profit_per_unit,
                    ed.total_price as revenue,
                    (ed.cost_price * ed.quantity) as total_cost,
                    ed.profit as total_profit
                FROM exports e
                INNER JOIN export_details ed ON e.id = ed.export_id
                INNER JOIN products p ON ed.product_id = p.id
                INNER JOIN warehouses w ON e.warehouse_id = w.id
                WHERE e.status = 'approved'
                AND e.export_date BETWEEN ? AND ?";
        
        $params = [$startDate, $endDate];
        
        if ($warehouseId) {
            $sql .= " AND e.warehouse_id = ?";
            $params[] = $warehouseId;
        }
        
        $sql .= " ORDER BY e.export_date DESC, e.code, p.code";
        
        return $this->query($sql, $params);
    }

    /**
     * Lấy thống kê tổng hợp lời/lỗ
     */
    public function getProfitLossStats($startDate, $endDate, $warehouseId = null)
    {
        $sql = "SELECT 
                    COUNT(DISTINCT e.id) as total_exports,
                    SUM(ed.quantity) as total_quantity,
                    SUM(ed.total_price) as total_revenue,
                    SUM(ed.cost_price * ed.quantity) as total_cost,
                    SUM(ed.profit) as total_profit,
                    AVG(ed.profit / NULLIF(ed.total_price, 0) * 100) as avg_profit_margin
                FROM exports e
                INNER JOIN export_details ed ON e.id = ed.export_id
                WHERE e.status = 'approved'
                AND e.export_date BETWEEN ? AND ?";
        
        $params = [$startDate, $endDate];
        
        if ($warehouseId) {
            $sql .= " AND e.warehouse_id = ?";
            $params[] = $warehouseId;
        }
        
        $result = $this->query($sql, $params);
        return $result ? $result[0] : null;
    }
}

