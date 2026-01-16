<?php

class ReportController extends Controller
{
    private $inventoryModel;
    private $warehouseModel;

    public function __construct()
    {
        Auth::requireLogin();
        $this->inventoryModel = $this->model('Inventory');
        $this->warehouseModel = $this->model('Warehouse');
    }

    public function inventory()
    {
        Auth::requirePermission('inventory.report');

        $warehouseId = $_GET['warehouse_id'] ?? '';
        
        if ($warehouseId) {
            $inventory = $this->inventoryModel->getByWarehouse($warehouseId);
        } else {
            $inventory = $this->inventoryModel->getAllWithDetails();
        }

        $warehouses = $this->warehouseModel->getAllActive();

        // Calculate statistics
        $totalProducts = count($inventory);
        $totalQuantity = array_sum(array_column($inventory, 'quantity'));
        $lowStock = 0;
        $overStock = 0;

        foreach ($inventory as $item) {
            if ($item['quantity'] < ($item['min_stock'] ?? 0) && ($item['min_stock'] ?? 0) > 0) {
                $lowStock++;
            }
            if ($item['quantity'] > ($item['max_stock'] ?? 0) && ($item['max_stock'] ?? 0) > 0) {
                $overStock++;
            }
        }

        $data = [
            'title' => 'Báo cáo tồn kho',
            'inventory' => $inventory,
            'warehouses' => $warehouses,
            'warehouseId' => $warehouseId,
            'stats' => [
                'totalProducts' => $totalProducts,
                'totalQuantity' => $totalQuantity,
                'lowStock' => $lowStock,
                'overStock' => $overStock
            ]
        ];

        $this->view('report/inventory', $data);
    }

    public function import()
    {
        Auth::requirePermission('report.view');
        
        $startDate = $_GET['start_date'] ?? date('Y-m-01'); // First day of current month
        $endDate = $_GET['end_date'] ?? date('Y-m-d'); // Today
        $warehouseId = $_GET['warehouse_id'] ?? '';

        $importModel = $this->model('Import');
        $imports = $importModel->getByDateRange($startDate, $endDate);
        
        // Filter by warehouse if selected
        if ($warehouseId) {
            $imports = array_filter($imports, function($item) use ($warehouseId) {
                return $item['warehouse_id'] == $warehouseId;
            });
        }

        $stats = $importModel->getStatsByDateRange($startDate, $endDate);
        $warehouses = $this->warehouseModel->getAllActive();

        $data = [
            'title' => 'Báo cáo nhập kho',
            'imports' => $imports,
            'stats' => $stats,
            'warehouses' => $warehouses,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'warehouseId' => $warehouseId
        ];

        $this->view('report/import', $data);
    }

    public function export()
    {
        Auth::requirePermission('report.view');
        
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $warehouseId = $_GET['warehouse_id'] ?? '';

        $exportModel = $this->model('Export');
        $exports = $exportModel->getByDateRange($startDate, $endDate);
        
        // Filter by warehouse if selected
        if ($warehouseId) {
            $exports = array_filter($exports, function($item) use ($warehouseId) {
                return $item['warehouse_id'] == $warehouseId;
            });
        }

        $stats = $exportModel->getStatsByDateRange($startDate, $endDate);
        $warehouses = $this->warehouseModel->getAllActive();

        $data = [
            'title' => 'Báo cáo xuất kho',
            'exports' => $exports,
            'stats' => $stats,
            'warehouses' => $warehouses,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'warehouseId' => $warehouseId
        ];

        $this->view('report/export', $data);
    }

    // CHỨC NĂNG ĐÃ BỊ TẮT - Không sử dụng nữa
    /*
    public function transaction()
    {
        Auth::requirePermission('report.view');
        
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $warehouseId = $_GET['warehouse_id'] ?? '';

        $transactionModel = $this->model('Transaction');
        
        $sql = "SELECT t.*, w.name as warehouse_name, p.name as product_name, p.code as product_code, u.full_name as created_by_name
                FROM inventory_transactions t
                LEFT JOIN warehouses w ON t.warehouse_id = w.id
                LEFT JOIN products p ON t.product_id = p.id
                LEFT JOIN users u ON t.created_by = u.id
                WHERE DATE(t.created_at) BETWEEN ? AND ?";
        
        $params = [$startDate, $endDate];
        
        if ($warehouseId) {
            $sql .= " AND t.warehouse_id = ?";
            $params[] = $warehouseId;
        }
        
        $sql .= " ORDER BY t.created_at DESC";
        
        $transactions = $transactionModel->query($sql, $params);
        $warehouses = $this->warehouseModel->getAllActive();

        $data = [
            'title' => 'Báo cáo giao dịch',
            'transactions' => $transactions,
            'warehouses' => $warehouses,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'warehouseId' => $warehouseId
        ];

        $this->view('report/transaction', $data);
    }
    */

    public function profitLoss()
    {
        Auth::requirePermission('report.profit_loss');
        
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $warehouseId = $_GET['warehouse_id'] ?? '';

        $exportModel = $this->model('Export');
        $details = $exportModel->getProfitLossReport($startDate, $endDate, $warehouseId);
        $stats = $exportModel->getProfitLossStats($startDate, $endDate, $warehouseId);
        $warehouses = $this->warehouseModel->getAllActive();

        $data = [
            'title' => 'Báo cáo Lời/Lỗ',
            'details' => $details,
            'stats' => $stats,
            'warehouses' => $warehouses,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'warehouseId' => $warehouseId
        ];

        $this->view('report/profit_loss', $data);
    }

    public function purchaseOrder()
    {
        Auth::requirePermission('report.view');
        
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');

        $purchaseOrderModel = $this->model('PurchaseOrder');
        $purchaseOrders = $purchaseOrderModel->getByDateRange($startDate, $endDate);
        $stats = $purchaseOrderModel->getStatsByDateRange($startDate, $endDate);

        $data = [
            'title' => 'Báo cáo đơn đặt hàng',
            'purchaseOrders' => $purchaseOrders,
            'stats' => $stats,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        $this->view('report/purchase_order', $data);
    }

    public function stockTake()
    {
        Auth::requirePermission('report.view');
        
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-d');
        $warehouseId = $_GET['warehouse_id'] ?? '';

        $stockTakeModel = $this->model('StockTake');
        $stockTakes = $stockTakeModel->getByDateRange($startDate, $endDate);
        
        // Filter by warehouse if selected
        if ($warehouseId) {
            $stockTakes = array_filter($stockTakes, function($item) use ($warehouseId) {
                return $item['warehouse_id'] == $warehouseId;
            });
        }

        $warehouses = $this->warehouseModel->getAllActive();

        $data = [
            'title' => 'Báo cáo kiểm kê kho',
            'stockTakes' => $stockTakes,
            'warehouses' => $warehouses,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'warehouseId' => $warehouseId
        ];

        $this->view('report/stock_take', $data);
    }
}
