<?php

class ExportController extends Controller
{
    private $exportModel;
    private $warehouseModel;

    public function __construct()
    {
        Auth::requireLogin();
        $this->exportModel = $this->model('Export');
        $this->warehouseModel = $this->model('Warehouse');
    }

    public function index()
    {
        Auth::requirePermission('export.view');

        $search = $_GET['search'] ?? '';
        $userWarehouseId = Auth::getWarehouseId();
        
        if ($search) {
            $exports = $this->exportModel->search($search);
        } else {
            $exports = $this->exportModel->getAllWithDetails();
        }

        // Filter by warehouse if user is not admin
        if ($userWarehouseId) {
            $exports = array_filter($exports, function($item) use ($userWarehouseId) {
                return $item['warehouse_id'] == $userWarehouseId;
            });
        }

        $data = [
            'title' => 'Xuất kho',
            'exports' => $exports,
            'search' => $search
        ];

        $this->view('export/index', $data);
    }

    public function create()
    {
        Auth::requirePermission('export.create');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $warehouseId = $_POST['warehouse_id'] ?? null;
            $customerName = trim($_POST['customer_name'] ?? '');
            $exportDate = $_POST['export_date'] ?? date('Y-m-d');
            $exportType = $_POST['export_type'] ?? 'sale';
            $notes = trim($_POST['notes'] ?? '');
            $status = 'pending';

            // Validation
            if (empty($warehouseId)) {
                $_SESSION['error'] = 'Vui lòng chọn kho!';
                $userWarehouseId = Auth::getWarehouseId();
                if ($userWarehouseId) {
                    $warehouses = [$this->warehouseModel->find($userWarehouseId)];
                } else {
                    $warehouses = $this->warehouseModel->getPrimaryWarehouses();
                }
                $this->view('export/create', [
                    'title' => 'Tạo phiếu xuất',
                    'warehouses' => $warehouses,
                    'old' => $_POST
                ]);
                return;
            }

            // Tự động tạo mã phiếu
            $code = $this->generateExportCode($exportDate);

            $data = [
                'code' => $code,
                'warehouse_id' => $warehouseId,
                'customer_name' => $customerName,
                'export_date' => $exportDate,
                'export_type' => $exportType,
                'total_amount' => 0,
                'status' => $status,
                'notes' => $notes,
                'created_by' => Auth::id()
            ];

            if ($this->exportModel->create($data)) {
                $_SESSION['success'] = 'Tạo phiếu xuất thành công! Mã phiếu: ' . $code;
                $this->redirect('export');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
            }
        }

        $userWarehouseId = Auth::getWarehouseId();
        if ($userWarehouseId) {
            $warehouses = [$this->warehouseModel->find($userWarehouseId)];
        } else {
            $warehouses = $this->warehouseModel->getPrimaryWarehouses();
        }

        $this->view('export/create', [
            'title' => 'Tạo phiếu xuất',
            'warehouses' => $warehouses
        ]);
    }

    /**
     * Tự động tạo mã phiếu xuất
     * Format: PX-YYYYMMDD-XXX
     */
    private function generateExportCode($date)
    {
        $dateStr = date('Ymd', strtotime($date));
        $prefix = 'PX-' . $dateStr . '-';
        
        // Lấy số thứ tự lớn nhất trong ngày
        $result = $this->exportModel->query(
            "SELECT code FROM exports WHERE code LIKE ? ORDER BY code DESC LIMIT 1",
            [$prefix . '%']
        );
        
        if ($result && count($result) > 0) {
            // Lấy số thứ tự từ mã cũ và tăng lên 1
            $lastCode = $result[0]['code'];
            $lastNumber = (int)substr($lastCode, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function detail($id)
    {
        Auth::requirePermission('export.view');

        $export = $this->exportModel->getWithDetails($id);
        if (!$export) {
            $_SESSION['error'] = 'Không tìm thấy phiếu xuất!';
            $this->redirect('export');
            return;
        }

        if (!Auth::canAccessWarehouse($export['warehouse_id'])) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập kho này!';
            $this->redirect('export');
            return;
        }

        $exportDetailModel = $this->model('ExportDetail');
        $productModel = $this->model('Product');
        
        $details = $exportDetailModel->getByExport($id);
        $products = $productModel->getByWarehouse($export['warehouse_id']);

        $this->view('export/view', [
            'title' => 'Chi tiết phiếu xuất',
            'export' => $export,
            'details' => $details,
            'products' => $products
        ]);
    }

    public function delete($id)
    {
        Auth::requirePermission('export.delete');

        $export = $this->exportModel->find($id);
        if (!$export) {
            $_SESSION['error'] = 'Không tìm thấy phiếu xuất!';
            $this->redirect('export');
            return;
        }

        // Cannot delete approved exports
        if ($export['status'] === 'approved') {
            $_SESSION['error'] = 'Không thể xóa phiếu xuất đã duyệt!';
            $this->redirect('export');
            return;
        }

        if ($this->exportModel->delete($id)) {
            $_SESSION['success'] = 'Xóa phiếu xuất thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
        }

        $this->redirect('export');
    }

    public function addProduct($id)
    {
        Auth::requirePermission('export.edit');

        $export = $this->exportModel->find($id);
        if (!$export || $export['status'] === 'approved') {
            $_SESSION['error'] = 'Không thể thêm sản phẩm vào phiếu này!';
            $this->redirect('export');
            return;
        }
        if (!Auth::canAccessWarehouse($export['warehouse_id'])) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập kho này!';
            $this->redirect('export');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra xem có nhiều sản phẩm không (MỚI)
            $products = $_POST['products'] ?? [];
            
            if (empty($products)) {
                $_SESSION['error'] = 'Vui lòng thêm ít nhất 1 sản phẩm!';
                $this->redirect('export/detail/' . $id);
                return;
            }

            $inventoryModel = $this->model('Inventory');
            $exportDetailModel = $this->model('ExportDetail');
            $productModel = $this->model('Product');
            $successCount = 0;
            
            // Xử lý từng sản phẩm (MỚI)
            foreach ($products as $product) {
                $productId = $product['product_id'] ?? null;
                $quantity = $product['quantity'] ?? 0;
                $unitPrice = $product['unit_price'] ?? 0;
                
                if ($productId && $quantity > 0 && $unitPrice >= 0) {
                    if (!$productModel->belongsToWarehouse($productId, $export['warehouse_id'])) {
                        continue;
                    }
                    // Lấy giá vốn từ inventory
                    $inventory = $inventoryModel->query(
                        "SELECT avg_import_price FROM inventory 
                         WHERE warehouse_id = ? AND product_id = ?",
                        [$export['warehouse_id'], $productId]
                    );
                    $costPrice = $inventory && count($inventory) > 0 ? $inventory[0]['avg_import_price'] : 0;
                    
                    $totalPrice = $quantity * $unitPrice;
                    $profit = ($unitPrice - $costPrice) * $quantity;
                    
                    $data = [
                        'export_id' => $id,
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'cost_price' => $costPrice,
                        'total_price' => $totalPrice,
                        'profit' => $profit
                    ];
                    
                    if ($exportDetailModel->create($data)) {
                        $successCount++;
                    }
                }
            }
            
            if ($successCount > 0) {
                // Update total amount
                $total = $exportDetailModel->getTotalAmount($id);
                $this->exportModel->update($id, ['total_amount' => $total]);
                
                $_SESSION['success'] = "Đã thêm $successCount sản phẩm thành công!";
            } else {
                $_SESSION['error'] = 'Không thể thêm sản phẩm!';
            }
        }

        $this->redirect('export/detail/' . $id);
    }

    public function removeProduct($id, $detailId)
    {
        Auth::requirePermission('export.edit');

        $export = $this->exportModel->find($id);
        if (!$export || $export['status'] === 'approved') {
            $_SESSION['error'] = 'Không thể xóa sản phẩm khỏi phiếu này!';
            $this->redirect('export');
            return;
        }

        $exportDetailModel = $this->model('ExportDetail');
        if ($exportDetailModel->delete($detailId)) {
            // Update total amount
            $total = $exportDetailModel->getTotalAmount($id);
            $this->exportModel->update($id, ['total_amount' => $total]);
            
            $_SESSION['success'] = 'Xóa sản phẩm thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra!';
        }

        $this->redirect('export/detail/' . $id);
    }

    public function approve($id)
    {
        Auth::requirePermission('export.approve');

        $export = $this->exportModel->find($id);
        if (!$export) {
            $_SESSION['error'] = 'Không tìm thấy phiếu xuất!';
            $this->redirect('export');
            return;
        }

        if (!Auth::canAccessWarehouse($export['warehouse_id'])) {
            $_SESSION['error'] = 'Bạn không có quyền duyệt kho này!';
            $this->redirect('export/detail/' . $id);
            return;
        }

        if ($export['status'] === 'approved') {
            $_SESSION['error'] = 'Phiếu xuất đã được duyệt!';
            $this->redirect('export/detail/' . $id);
            return;
        }

        $exportDetailModel = $this->model('ExportDetail');
        $details = $exportDetailModel->getByExport($id);

        if (empty($details)) {
            $_SESSION['error'] = 'Phiếu xuất chưa có sản phẩm!';
            $this->redirect('export/detail/' . $id);
            return;
        }

        $inventoryModel = $this->model('Inventory');
        $transactionModel = $this->model('Transaction');

        try {
            // Check stock and update inventory
            foreach ($details as $detail) {
                // Get current balance and cost price
                $current = $inventoryModel->query(
                    "SELECT quantity, avg_import_price FROM inventory 
                     WHERE warehouse_id = ? AND product_id = ?",
                    [$export['warehouse_id'], $detail['product_id']]
                );
                $balanceBefore = $current ? $current[0]['quantity'] : 0;
                $costPrice = $current ? $current[0]['avg_import_price'] : 0;

                // Check if enough stock
                if ($balanceBefore < $detail['quantity']) {
                    throw new Exception('Không đủ tồn kho cho sản phẩm: ' . $detail['product_name']);
                }
                
                // Cập nhật giá vốn và lợi nhuận nếu chưa có (MỚI)
                if ($detail['cost_price'] == 0) {
                    $profit = ($detail['unit_price'] - $costPrice) * $detail['quantity'];
                    $exportDetailModel->query(
                        "UPDATE export_details 
                         SET cost_price = ?, profit = ? 
                         WHERE id = ?",
                        [$costPrice, $profit, $detail['id']]
                    );
                }

                // Update inventory
                $inventoryModel->updateQuantity(
                    $export['warehouse_id'],
                    $detail['product_id'],
                    $detail['quantity'],
                    'export'
                );

                // Log transaction
                $transactionModel->logTransaction([
                    'warehouse_id' => $export['warehouse_id'],
                    'product_id' => $detail['product_id'],
                    'transaction_type' => 'export',
                    'reference_type' => 'export',
                    'reference_id' => $id,
                    'reference_code' => $export['code'],
                    'quantity' => $detail['quantity'],
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceBefore - $detail['quantity'],
                    'transaction_date' => $export['export_date'] . ' ' . date('H:i:s'),
                    'created_by' => Auth::id()
                ]);
            }

            // Update export status
            $this->exportModel->update($id, [
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => date('Y-m-d H:i:s')
            ]);

            $_SESSION['success'] = 'Duyệt phiếu xuất thành công!';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
        }

        $this->redirect('export');
    }

    public function reject($id)
    {
        Auth::requirePermission('export.approve');

        $export = $this->exportModel->find($id);
        if (!$export) {
            $_SESSION['error'] = 'Không tìm thấy phiếu xuất!';
            $this->redirect('export');
            return;
        }

        if (!Auth::canAccessWarehouse($export['warehouse_id'])) {
            $_SESSION['error'] = 'Bạn không có quyền từ chối kho này!';
            $this->redirect('export/detail/' . $id);
            return;
        }

        if ($export['status'] === 'approved' || $export['status'] === 'cancelled') {
            $_SESSION['error'] = 'Phiếu xuất không thể từ chối!';
            $this->redirect('export/detail/' . $id);
            return;
        }

        if ($this->exportModel->update($id, [
            'status' => 'cancelled',
            'approved_by' => Auth::id(),
            'approved_at' => date('Y-m-d H:i:s')
        ])) {
            $_SESSION['success'] = 'Đã từ chối phiếu xuất!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
        }

        $this->redirect('export');
    }
}
