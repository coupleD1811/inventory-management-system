<?php

class Inventory extends Model
{
    protected $table = 'inventory';

    public function getByWarehouse($warehouseId)
    {
        $sql = "SELECT i.*, p.code as product_code, p.name as product_name, 
                       p.unit, w.name as warehouse_name
                FROM {$this->table} i
                INNER JOIN products p ON i.product_id = p.id
                INNER JOIN warehouses w ON i.warehouse_id = w.id
                WHERE i.warehouse_id = ?
                ORDER BY p.name";
        return $this->query($sql, [$warehouseId]);
    }

    public function getByProduct($productId)
    {
        $sql = "SELECT i.*, w.name as warehouse_name, w.code as warehouse_code
                FROM {$this->table} i
                INNER JOIN warehouses w ON i.warehouse_id = w.id
                WHERE i.product_id = ?
                ORDER BY w.name";
        return $this->query($sql, [$productId]);
    }

    public function getAllWithDetails()
    {
        $sql = "SELECT i.*, p.code as product_code, p.name as product_name, 
                       p.unit, w.name as warehouse_name, w.code as warehouse_code
                FROM {$this->table} i
                INNER JOIN products p ON i.product_id = p.id
                INNER JOIN warehouses w ON i.warehouse_id = w.id
                ORDER BY i.updated_at DESC, i.id DESC";
        return $this->query($sql);
    }

    public function updateQuantity($warehouseId, $productId, $quantity, $type = 'import')
    {
        // Check if inventory record exists
        $sql = "SELECT * FROM {$this->table} WHERE warehouse_id = ? AND product_id = ?";
        $result = $this->query($sql, [$warehouseId, $productId]);

        if ($result) {
            // Update existing record
            $currentQty = $result[0]['quantity'];
            $newQty = $type === 'import' ? $currentQty + $quantity : $currentQty - $quantity;
            
            $updateData = [
                'quantity' => $newQty,
                'last_' . $type . '_date' => date('Y-m-d')
            ];

            $this->execute(
                "UPDATE {$this->table} SET quantity = ?, last_{$type}_date = ? WHERE warehouse_id = ? AND product_id = ?",
                [$newQty, date('Y-m-d'), $warehouseId, $productId]
            );
        } else {
            // Create new record
            $this->create([
                'warehouse_id' => $warehouseId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'last_' . $type . '_date' => date('Y-m-d')
            ]);
        }

        return true;
    }

    public function search($warehouseId = null, $keyword = null)
    {
        $sql = "SELECT i.*, p.code as product_code, p.name as product_name, 
                       p.unit, p.min_stock, p.max_stock,
                       w.name as warehouse_name, w.code as warehouse_code
                FROM {$this->table} i
                INNER JOIN products p ON i.product_id = p.id
                INNER JOIN warehouses w ON i.warehouse_id = w.id
                WHERE 1=1";
        
        $params = [];
        
        if ($warehouseId) {
            $sql .= " AND i.warehouse_id = ?";
            $params[] = $warehouseId;
        }
        
        if ($keyword) {
            $sql .= " AND (p.code LIKE ? OR p.name LIKE ?)";
            $params[] = "%{$keyword}%";
            $params[] = "%{$keyword}%";
        }
        
        $sql .= " ORDER BY i.updated_at DESC, i.id DESC";
        
        return $this->query($sql, $params);
    }

    public function getAllPaged($limit, $offset)
    {
        $sql = "SELECT i.*, p.code as product_code, p.name as product_name, 
                       p.unit, p.min_stock, p.max_stock,
                       w.name as warehouse_name, w.code as warehouse_code
                FROM {$this->table} i
                INNER JOIN products p ON i.product_id = p.id
                INNER JOIN warehouses w ON i.warehouse_id = w.id
                ORDER BY i.updated_at DESC, i.id DESC
                LIMIT ? OFFSET ?";
        return $this->query($sql, [$limit, $offset]);
    }

    public function searchPaged($warehouseId, $keyword, $limit, $offset)
    {
        $sql = "SELECT i.*, p.code as product_code, p.name as product_name, 
                       p.unit, p.min_stock, p.max_stock,
                       w.name as warehouse_name, w.code as warehouse_code
                FROM {$this->table} i
                INNER JOIN products p ON i.product_id = p.id
                INNER JOIN warehouses w ON i.warehouse_id = w.id
                WHERE 1=1";
        $params = [];

        if ($warehouseId) {
            $sql .= " AND i.warehouse_id = ?";
            $params[] = $warehouseId;
        }

        if ($keyword) {
            $sql .= " AND (p.code LIKE ? OR p.name LIKE ?)";
            $params[] = "%{$keyword}%";
            $params[] = "%{$keyword}%";
        }

        $sql .= " ORDER BY i.updated_at DESC, i.id DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        return $this->query($sql, $params);
    }

    public function countAll()
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->query($sql);
        return $result ? (int)$result[0]['total'] : 0;
    }

    public function countSearch($warehouseId, $keyword)
    {
        $sql = "SELECT COUNT(*) as total
                FROM {$this->table} i
                INNER JOIN products p ON i.product_id = p.id
                WHERE 1=1";
        $params = [];

        if ($warehouseId) {
            $sql .= " AND i.warehouse_id = ?";
            $params[] = $warehouseId;
        }

        if ($keyword) {
            $sql .= " AND (p.code LIKE ? OR p.name LIKE ?)";
            $params[] = "%{$keyword}%";
            $params[] = "%{$keyword}%";
        }

        $result = $this->query($sql, $params);
        return $result ? (int)$result[0]['total'] : 0;
    }

    /**
     * Cập nhật giá nhập trung bình khi nhập hàng
     * Sử dụng phương pháp bình quân gia quyền
     */
    public function updateAvgImportPrice($warehouseId, $productId, $newQuantity, $newPrice)
    {
        // Lấy thông tin tồn kho hiện tại
        $current = $this->query(
            "SELECT quantity, avg_import_price FROM {$this->table} 
             WHERE warehouse_id = ? AND product_id = ?",
            [$warehouseId, $productId]
        );
        
        if ($current && count($current) > 0) {
            $currentQty = $current[0]['quantity'];
            $currentAvgPrice = $current[0]['avg_import_price'];
            
            // Tính giá trung bình mới: (Tồn cũ * Giá cũ + Nhập mới * Giá mới) / Tổng tồn
            $totalValue = ($currentQty * $currentAvgPrice) + ($newQuantity * $newPrice);
            $totalQty = $currentQty + $newQuantity;
            $newAvgPrice = $totalQty > 0 ? $totalValue / $totalQty : 0;
            
            // Cập nhật giá trung bình
            $this->execute(
                "UPDATE {$this->table} 
                 SET avg_import_price = ? 
                 WHERE warehouse_id = ? AND product_id = ?",
                [$newAvgPrice, $warehouseId, $productId]
            );
            
            return $newAvgPrice;
        }
        
        return $newPrice; // Nếu chưa có tồn kho, giá TB = giá nhập đầu tiên
    }
}
