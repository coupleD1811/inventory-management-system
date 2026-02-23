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
        $nearStock = 0;

        foreach ($inventory as $item) {
            if ($item['quantity'] < ($item['min_stock'] ?? 0) && ($item['min_stock'] ?? 0) > 0) {
                $lowStock++;
            }
            if ($item['quantity'] > ($item['max_stock'] ?? 0) && ($item['max_stock'] ?? 0) > 0) {
                $overStock++;
            }
            if (
                (
                    ($item['min_stock'] ?? 0) > 0 &&
                    $item['quantity'] >= ($item['min_stock'] * 1.0) &&
                    $item['quantity'] <= ($item['min_stock'] * 1.1)
                ) ||
                (
                    ($item['max_stock'] ?? 0) > 0 &&
                    $item['quantity'] <= ($item['max_stock'] * 1.0) &&
                    $item['quantity'] >= ($item['max_stock'] * 0.9)
                )
            ) {
                $nearStock++;
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
                'overStock' => $overStock,
                'nearStock' => $nearStock
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

    public function movement()
    {
        Auth::requirePermission('report.view');

        $period = $_GET['period'] ?? 'month';
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        $warehouseId = $_GET['warehouse_id'] ?? '';
        $keyword = trim($_GET['keyword'] ?? '');

        if (!$startDate || !$endDate) {
            [$startDate, $endDate] = $this->resolvePeriodDates($period);
        }

        $rows = $this->inventoryModel->getMovementReport($startDate, $endDate, $warehouseId, $keyword);
        $warehouses = $this->warehouseModel->getAllActive();

        $stats = [
            'opening' => 0,
            'import' => 0,
            'export' => 0,
            'closing' => 0,
            'items' => count($rows),
        ];

        foreach ($rows as &$row) {
            $statusDetail = $this->inventoryModel->getWarningDetailForQuantity(
                (float)$row['closing_qty'],
                $row['min_stock'] ?? 0,
                $row['max_stock'] ?? 0
            );
            $row['status_detail'] = $statusDetail;
            $row['status_label'] = $this->inventoryModel->getStatusLabelFromDetail($statusDetail) ?? 'Bình thường';

            $stats['opening'] += (float)$row['opening_qty'];
            $stats['import'] += (float)$row['import_qty'];
            $stats['export'] += (float)$row['export_qty'];
            $stats['closing'] += (float)$row['closing_qty'];
        }
        unset($row);

        $data = [
            'title' => 'Báo cáo cân đối kho',
            'rows' => $rows,
            'warehouses' => $warehouses,
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'warehouseId' => $warehouseId,
            'keyword' => $keyword,
            'stats' => $stats
        ];

        $this->view('report/movement', $data);
    }

    private function resolvePeriodDates($period)
    {
        $today = new DateTime('today');
        $start = clone $today;
        $end = clone $today;

        switch ($period) {
            case 'day':
                break;
            case 'week':
                $start->modify('monday this week');
                $end->modify('sunday this week');
                break;
            case 'quarter':
                $month = (int)$today->format('n');
                $quarterStartMonth = (int)(floor(($month - 1) / 3) * 3) + 1;
                $start = new DateTime($today->format('Y') . '-' . str_pad($quarterStartMonth, 2, '0', STR_PAD_LEFT) . '-01');
                $end = clone $start;
                $end->modify('+2 months')->modify('last day of this month');
                break;
            case 'year':
                $start = new DateTime($today->format('Y-01-01'));
                $end = new DateTime($today->format('Y-12-31'));
                break;
            case 'month':
            default:
                $start->modify('first day of this month');
                $end->modify('last day of this month');
                break;
        }

        return [$start->format('Y-m-d'), $end->format('Y-m-d')];
    }
}
