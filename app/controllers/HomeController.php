<?php

class HomeController extends Controller
{
    public function __construct()
    {
        Auth::requireLogin();
    }

    public function index()
    {
        // Lấy thống kê tổng quan
        $productModel = $this->model('Product');
        $importModel = $this->model('Import');
        $exportModel = $this->model('Export');
        $inventoryModel = $this->model('Inventory');
        
        // Thống kê sản phẩm
        $totalProducts = $productModel->query("SELECT COUNT(*) as total FROM products")[0]['total'];
        
        // Thống kê nhập/xuất tháng này
        $startOfMonth = date('Y-m-01');
        $endOfMonth = date('Y-m-t');
        
        $importStats = $importModel->query(
            "SELECT 
                COUNT(*) as total_imports,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_imports,
                SUM(CASE WHEN status = 'approved' THEN total_amount ELSE 0 END) as total_import_value
             FROM imports 
             WHERE import_date BETWEEN ? AND ?",
            [$startOfMonth, $endOfMonth]
        )[0];
        
        $exportStats = $exportModel->query(
            "SELECT 
                COUNT(*) as total_exports,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_exports,
                SUM(CASE WHEN status = 'approved' THEN total_amount ELSE 0 END) as total_export_value
             FROM exports 
             WHERE export_date BETWEEN ? AND ?",
            [$startOfMonth, $endOfMonth]
        )[0];
        
        // Thống kê lời/lỗ tháng này (nếu có quyền)
        $profitStats = null;
        if (Auth::hasPermission('report.profit_loss')) {
            $profitStats = $exportModel->query(
                "SELECT 
                    SUM(ed.total_price) as revenue,
                    SUM(ed.cost_price * ed.quantity) as cost,
                    SUM(ed.profit) as profit
                 FROM exports e
                 INNER JOIN export_details ed ON e.id = ed.export_id
                 WHERE e.status = 'approved' 
                 AND e.export_date BETWEEN ? AND ?",
                [$startOfMonth, $endOfMonth]
            )[0];
        }
        
        // Cảnh báo tồn kho
        $lowStockProducts = $inventoryModel->query(
            "SELECT i.*, p.code, p.name, p.min_stock, w.name as warehouse_name
             FROM inventory i
             INNER JOIN products p ON i.product_id = p.id
             INNER JOIN warehouses w ON i.warehouse_id = w.id
             WHERE p.min_stock > 0 AND i.quantity < p.min_stock
             ORDER BY i.quantity ASC
             LIMIT 10"
        );
        
        // Phiếu chờ duyệt
        $pendingImports = 0;
        $pendingExports = 0;
        if (Auth::hasPermission('import.approve')) {
            $pendingImports = $importModel->query(
                "SELECT COUNT(*) as total FROM imports WHERE status = 'pending'"
            )[0]['total'];
        }
        if (Auth::hasPermission('export.approve')) {
            $pendingExports = $exportModel->query(
                "SELECT COUNT(*) as total FROM exports WHERE status = 'pending'"
            )[0]['total'];
        }
        
        // Top sản phẩm xuất nhiều nhất tháng này
        $topProducts = $exportModel->query(
            "SELECT 
                p.code, p.name, p.unit,
                SUM(ed.quantity) as total_quantity,
                SUM(ed.total_price) as total_revenue
             FROM export_details ed
             INNER JOIN exports e ON ed.export_id = e.id
             INNER JOIN products p ON ed.product_id = p.id
             WHERE e.status = 'approved'
             AND e.export_date BETWEEN ? AND ?
             GROUP BY p.id
             ORDER BY total_quantity DESC
             LIMIT 5",
            [$startOfMonth, $endOfMonth]
        );
        
        $data = [
            'title' => 'Dashboard',
            'totalProducts' => $totalProducts,
            'importStats' => $importStats,
            'exportStats' => $exportStats,
            'profitStats' => $profitStats,
            'lowStockProducts' => $lowStockProducts,
            'pendingImports' => $pendingImports,
            'pendingExports' => $pendingExports,
            'topProducts' => $topProducts,
            'currentMonth' => date('m/Y')
        ];

        $this->view('home/index', $data);
    }
}
