<?php

class ImportController extends Controller
{
    private $importModel;
    private $warehouseModel;
    private $supplierModel;
    private $workshopModel;

    public function __construct()
    {
        Auth::requireLogin();
        $this->importModel = $this->model('Import');
        $this->warehouseModel = $this->model('Warehouse');
        $this->supplierModel = $this->model('Supplier');
        $this->workshopModel = $this->model('Workshop');
    }

    public function index()
    {
        Auth::requirePermission('import.view');

        $search = $_GET['search'] ?? '';
        $userWarehouseId = Auth::getWarehouseId();
        
        if ($search) {
            $imports = $this->importModel->search($search);
        } else {
            $imports = $this->importModel->getAllWithDetails();
        }

        // Filter by warehouse if user is not admin
        if ($userWarehouseId) {
            $imports = array_filter($imports, function($item) use ($userWarehouseId) {
                return $item['warehouse_id'] == $userWarehouseId;
            });
        }

        $data = [
            'title' => 'Nhập kho',
            'imports' => $imports,
            'search' => $search
        ];

        $this->view('import/index', $data);
    }

    public function create()
    {
        Auth::requirePermission('import.create');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $warehouseId = $_POST['warehouse_id'] ?? null;
            $supplierId = $_POST['supplier_id'] ?? null;
            $workshopId = $_POST['workshop_id'] ?? null;
            $importDate = $_POST['import_date'] ?? date('Y-m-d');
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
                $this->view('import/create', [
                    'title' => 'Tạo phiếu nhập',
                    'warehouses' => $warehouses,
                    'suppliers' => $this->supplierModel->getAllActive(),
                    'workshops' => $this->workshopModel->getAllActive(),
                    'old' => $_POST
                ]);
                return;
            }

            // Tự động tạo mã phiếu
            $code = $this->generateImportCode($importDate);

            $warehouse = $this->warehouseModel->find($warehouseId);
            $isFinished = $warehouse && $warehouse['code'] === 'KHO-TP';

            $data = [
                'code' => $code,
                'warehouse_id' => $warehouseId,
                'supplier_id' => $isFinished ? null : $supplierId,
                'workshop_id' => $isFinished ? $workshopId : null,
                'import_date' => $importDate,
                'total_amount' => 0,
                'status' => $status,
                'notes' => $notes,
                'created_by' => Auth::id()
            ];

            if ($this->importModel->create($data)) {
                $_SESSION['success'] = 'Tạo phiếu nhập thành công! Mã phiếu: ' . $code;
                $this->redirect('import');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
            }
        }

        // Get warehouses - admin sees all, others see only their warehouse
        $userWarehouseId = Auth::getWarehouseId();
        if ($userWarehouseId) {
            $warehouses = [$this->warehouseModel->find($userWarehouseId)];
        } else {
            $warehouses = $this->warehouseModel->getPrimaryWarehouses();
        }

        $this->view('import/create', [
            'title' => 'Tạo phiếu nhập',
            'warehouses' => $warehouses,
            'suppliers' => $this->supplierModel->getAllActive(),
            'workshops' => $this->workshopModel->getAllActive()
        ]);
    }

    /**
     * Tự động tạo mã phiếu nhập
     * Format: PN-YYYYMMDD-XXX
     */
    private function generateImportCode($date)
    {
        $dateStr = date('Ymd', strtotime($date));
        $prefix = 'PN-' . $dateStr . '-';
        
        // Lấy số thứ tự lớn nhất trong ngày
        $result = $this->importModel->query(
            "SELECT code FROM imports WHERE code LIKE ? ORDER BY code DESC LIMIT 1",
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
        Auth::requirePermission('import.view');

        $import = $this->importModel->getWithDetails($id);
        if (!$import) {
            $_SESSION['error'] = 'Không tìm thấy phiếu nhập!';
            $this->redirect('import');
            return;
        }

        // Check warehouse access
        if (!Auth::canAccessWarehouse($import['warehouse_id'])) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập kho này!';
            $this->redirect('import');
            return;
        }

        $importDetailModel = $this->model('ImportDetail');
        $productModel = $this->model('Product');
        $inventoryModel = $this->model('Inventory');
        
        $details = $importDetailModel->getByImport($id);
        $products = $productModel->getByWarehouse($import['warehouse_id']);
        $detailWarnings = [];
        if (!empty($details)) {
            $productTotals = [];
            foreach ($details as $detail) {
                $productTotals[$detail['product_id']] = ($productTotals[$detail['product_id']] ?? 0) + $detail['quantity'];
            }
            foreach ($details as $detail) {
                $currentQty = $inventoryModel->getQuantity($import['warehouse_id'], $detail['product_id']);
                $projectedQty = $currentQty + ($productTotals[$detail['product_id']] ?? 0);
                $detailWarnings[$detail['id']] = $inventoryModel->getWarningStatusForQuantity(
                    $projectedQty,
                    $detail['min_stock'] ?? 0,
                    $detail['max_stock'] ?? 0
                );
            }
        }

        $this->view('import/view', [
            'title' => 'Chi tiết phiếu nhập',
            'import' => $import,
            'details' => $details,
            'products' => $products,
            'detailWarnings' => $detailWarnings
        ]);
    }

    public function delete($id)
    {
        Auth::requirePermission('import.delete');

        $import = $this->importModel->find($id);
        if (!$import) {
            $_SESSION['error'] = 'Không tìm thấy phiếu nhập!';
            $this->redirect('import');
            return;
        }

        // Cannot delete approved imports
        if ($import['status'] === 'approved') {
            $_SESSION['error'] = 'Không thể xóa phiếu nhập đã duyệt!';
            $this->redirect('import');
            return;
        }

        if ($this->importModel->delete($id)) {
            $_SESSION['success'] = 'Xóa phiếu nhập thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
        }

        $this->redirect('import');
    }

    public function addProduct($id)
    {
        Auth::requirePermission('import.edit');

        $import = $this->importModel->find($id);
        if (!$import || $import['status'] === 'approved') {
            $_SESSION['error'] = 'Không thể thêm sản phẩm vào phiếu này!';
            $this->redirect('import');
            return;
        }
        if (!Auth::canAccessWarehouse($import['warehouse_id'])) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập kho này!';
            $this->redirect('import');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Kiểm tra xem có nhiều sản phẩm không (MỚI)
            $products = $_POST['products'] ?? [];
            
            if (empty($products)) {
                $_SESSION['error'] = 'Vui lòng thêm ít nhất 1 sản phẩm!';
                $this->redirect('import/detail/' . $id);
                return;
            }
            
            $importDetailModel = $this->model('ImportDetail');
            $productModel = $this->model('Product');
            $successCount = 0;
            
            // Xử lý từng sản phẩm (MỚI)
            foreach ($products as $product) {
                $productId = $product['product_id'] ?? null;
                $quantity = $product['quantity'] ?? 0;
                $unitPrice = $product['unit_price'] ?? 0;
                
                if ($productId && $quantity > 0 && $unitPrice >= 0) {
                    if (!$productModel->belongsToWarehouse($productId, $import['warehouse_id'])) {
                        continue;
                    }
                    $data = [
                        'import_id' => $id,
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $quantity * $unitPrice
                    ];
                    
                    if ($importDetailModel->create($data)) {
                        $successCount++;
                    }
                }
            }
            
            if ($successCount > 0) {
                // Update total amount
                $total = $importDetailModel->getTotalAmount($id);
                $this->importModel->update($id, ['total_amount' => $total]);
                
                $_SESSION['success'] = "Đã thêm $successCount sản phẩm thành công!";
            } else {
                $_SESSION['error'] = 'Không thể thêm sản phẩm!';
            }
        }

        $this->redirect('import/detail/' . $id);
    }

    public function removeProduct($id, $detailId)
    {
        Auth::requirePermission('import.edit');

        $import = $this->importModel->find($id);
        if (!$import || $import['status'] === 'approved') {
            $_SESSION['error'] = 'Không thể xóa sản phẩm khỏi phiếu này!';
            $this->redirect('import');
            return;
        }

        $importDetailModel = $this->model('ImportDetail');
        if ($importDetailModel->delete($detailId)) {
            // Update total amount
            $total = $importDetailModel->getTotalAmount($id);
            $this->importModel->update($id, ['total_amount' => $total]);
            
            $_SESSION['success'] = 'Xóa sản phẩm thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra!';
        }

        $this->redirect('import/detail/' . $id);
    }

    public function approve($id)
    {
        Auth::requirePermission('import.approve');

        $import = $this->importModel->find($id);
        if (!$import) {
            $_SESSION['error'] = 'Không tìm thấy phiếu nhập!';
            $this->redirect('import');
            return;
        }

        if (!Auth::canAccessWarehouse($import['warehouse_id'])) {
            $_SESSION['error'] = 'Bạn không có quyền duyệt kho này!';
            $this->redirect('import/detail/' . $id);
            return;
        }

        if ($import['status'] === 'approved') {
            $_SESSION['error'] = 'Phiếu nhập đã được duyệt!';
            $this->redirect('import/detail/' . $id);
            return;
        }

        $importDetailModel = $this->model('ImportDetail');
        $details = $importDetailModel->getByImport($id);

        if (empty($details)) {
            $_SESSION['error'] = 'Phiếu nhập chưa có sản phẩm!';
            $this->redirect('import/detail/' . $id);
            return;
        }

        $inventoryModel = $this->model('Inventory');
        $transactionModel = $this->model('Transaction');

        try {
            // Update inventory and log transactions
            foreach ($details as $detail) {
                // Get current balance
                $current = $inventoryModel->query(
                    "SELECT quantity FROM inventory WHERE warehouse_id = ? AND product_id = ?",
                    [$import['warehouse_id'], $detail['product_id']]
                );
                $balanceBefore = $current ? $current[0]['quantity'] : 0;

                // Update inventory
                $inventoryModel->updateQuantity(
                    $import['warehouse_id'],
                    $detail['product_id'],
                    $detail['quantity'],
                    'import'
                );
                
                // Cập nhật giá nhập trung bình (MỚI)
                $inventoryModel->updateAvgImportPrice(
                    $import['warehouse_id'],
                    $detail['product_id'],
                    $detail['quantity'],
                    $detail['unit_price']
                );

                // Log transaction
                $transactionModel->logTransaction([
                    'warehouse_id' => $import['warehouse_id'],
                    'product_id' => $detail['product_id'],
                    'transaction_type' => 'import',
                    'reference_type' => 'import',
                    'reference_id' => $id,
                    'reference_code' => $import['code'],
                    'quantity' => $detail['quantity'],
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceBefore + $detail['quantity'],
                    'transaction_date' => $import['import_date'] . ' ' . date('H:i:s'),
                    'created_by' => Auth::id()
                ]);
            }

            // Update import status
            $this->importModel->update($id, [
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => date('Y-m-d H:i:s')
            ]);

            $_SESSION['success'] = 'Duyệt phiếu nhập thành công!';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
        }

        $this->redirect('import');
    }

    public function reject($id)
    {
        Auth::requirePermission('import.approve');

        $import = $this->importModel->find($id);
        if (!$import) {
            $_SESSION['error'] = 'Không tìm thấy phiếu nhập!';
            $this->redirect('import');
            return;
        }

        if (!Auth::canAccessWarehouse($import['warehouse_id'])) {
            $_SESSION['error'] = 'Bạn không có quyền từ chối kho này!';
            $this->redirect('import/detail/' . $id);
            return;
        }

        if ($import['status'] === 'approved' || $import['status'] === 'cancelled') {
            $_SESSION['error'] = 'Phiếu nhập không thể từ chối!';
            $this->redirect('import/detail/' . $id);
            return;
        }

        if ($this->importModel->update($id, [
            'status' => 'cancelled',
            'approved_by' => Auth::id(),
            'approved_at' => date('Y-m-d H:i:s')
        ])) {
            $_SESSION['success'] = 'Đã từ chối phiếu nhập!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
        }

        $this->redirect('import');
    }
}
