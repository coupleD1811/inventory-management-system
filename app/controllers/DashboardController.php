<?php

class DashboardController extends Controller
{
    public function __construct()
    {
        Auth::requireLogin();
    }

    public function index()
    {
        $warehouseModel = $this->model('Warehouse');
        $productModel = $this->model('Product');
        $inventoryModel = $this->model('Inventory');
        $importModel = $this->model('Import');
        $exportModel = $this->model('Export');

        // Get warehouse statistics
        $warehouses = $warehouseModel->query("
            SELECT w.*, 
                   COUNT(DISTINCT i.product_id) as total_products,
                   SUM(i.quantity) as total_quantity
            FROM warehouses w
            LEFT JOIN inventory i ON w.id = i.warehouse_id
            WHERE w.status = 'active'
            GROUP BY w.id
            ORDER BY w.name
        ");

        // Get low stock alerts
        $lowStockItems = $inventoryModel->query("
            SELECT i.*, w.name as warehouse_name, p.code as product_code, 
                   p.name as product_name, p.min_stock, p.unit
            FROM inventory i
            INNER JOIN warehouses w ON i.warehouse_id = w.id
            INNER JOIN products p ON i.product_id = p.id
            WHERE i.quantity < p.min_stock AND p.status = 'active'
            ORDER BY (i.quantity / p.min_stock) ASC
            LIMIT 10
        ");

        // Get overstock alerts
        $overStockItems = $inventoryModel->query("
            SELECT i.*, w.name as warehouse_name, p.code as product_code, 
                   p.name as product_name, p.max_stock, p.unit
            FROM inventory i
            INNER JOIN warehouses w ON i.warehouse_id = w.id
            INNER JOIN products p ON i.product_id = p.id
            WHERE i.quantity > p.max_stock AND p.status = 'active'
            ORDER BY (i.quantity / p.max_stock) DESC
            LIMIT 10
        ");

        // Get recent imports (last 7 days)
        $recentImports = $importModel->query("
            SELECT i.*, w.name as warehouse_name, s.name as supplier_name
            FROM imports i
            LEFT JOIN warehouses w ON i.warehouse_id = w.id
            LEFT JOIN suppliers s ON i.supplier_id = s.id
            WHERE i.import_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            ORDER BY i.import_date DESC
            LIMIT 5
        ");

        // Get recent exports (last 7 days)
        $recentExports = $exportModel->query("
            SELECT e.*, w.name as warehouse_name
            FROM exports e
            LEFT JOIN warehouses w ON e.warehouse_id = w.id
            WHERE e.export_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
            ORDER BY e.export_date DESC
            LIMIT 5
        ");

        // Summary statistics
        $stats = [
            'total_warehouses' => count($warehouses),
            'total_products' => $productModel->query("SELECT COUNT(*) as count FROM products WHERE status = 'active'")[0]['count'],
            'low_stock_count' => count($lowStockItems),
            'overstock_count' => count($overStockItems),
            'pending_imports' => $importModel->query("SELECT COUNT(*) as count FROM imports WHERE status = 'pending'")[0]['count'],
            'pending_exports' => $exportModel->query("SELECT COUNT(*) as count FROM exports WHERE status = 'pending'")[0]['count']
        ];

        $this->view('dashboard/index', [
            'title' => 'Tổng quan hệ thống',
            'warehouses' => $warehouses,
            'lowStockItems' => $lowStockItems,
            'overStockItems' => $overStockItems,
            'recentImports' => $recentImports,
            'recentExports' => $recentExports,
            'stats' => $stats
        ]);
    }
}
