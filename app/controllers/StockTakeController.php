<?php

class StockTakeController extends Controller
{
    private $stockTakeModel;
    private $warehouseModel;
    private $inventoryModel;

    public function __construct()
    {
        Auth::requireLogin();
        $this->stockTakeModel = $this->model('StockTake');
        $this->warehouseModel = $this->model('Warehouse');
        $this->inventoryModel = $this->model('Inventory');
    }

    public function index()
    {
        Auth::requirePermission('stock_take.view');

        $search = $_GET['search'] ?? '';
        $warehouseId = $_GET['warehouse_id'] ?? '';
        
        if ($warehouseId) {
            $stockTakes = $this->stockTakeModel->getByWarehouse($warehouseId);
        } else {
            $stockTakes = $this->stockTakeModel->getAllWithDetails();
        }

        $data = [
            'title' => 'Kiểm kê kho',
            'stockTakes' => $stockTakes,
            'warehouses' => $this->warehouseModel->getAllActive(),
            'warehouseId' => $warehouseId
        ];

        $this->view('stock_take/index', $data);
    }

    public function create()
    {
        Auth::requirePermission('stock_take.create');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $this->stockTakeModel->generateCode();
            $warehouseId = $_POST['warehouse_id'] ?? null;
            $stockTakeDate = $_POST['stock_take_date'] ?? date('Y-m-d');
            $notes = trim($_POST['notes'] ?? '');
            $products = $_POST['products'] ?? [];

            // Validation
            if (empty($warehouseId) || empty($products)) {
                $_SESSION['error'] = 'Kho và sản phẩm không được để trống!';
                $this->redirect('stock_take/create');
                return;
            }

            $data = [
                'code' => $code,
                'warehouse_id' => $warehouseId,
                'stock_take_date' => $stockTakeDate,
                'status' => 'draft',
                'notes' => $notes,
                'created_by' => Auth::id()
            ];

            $stockTakeId = $this->stockTakeModel->create($data);
            
            if ($stockTakeId) {
                // Add products with variance
                $detailModel = $this->model('StockTakeDetail');
                foreach ($products as $product) {
                    if (!empty($product['product_id'])) {
                        $systemQty = $product['system_quantity'] ?? 0;
                        $actualQty = $product['actual_quantity'] ?? 0;
                        $variance = $actualQty - $systemQty;

                        $detailData = [
                            'stock_take_id' => $stockTakeId,
                            'product_id' => $product['product_id'],
                            'system_quantity' => $systemQty,
                            'actual_quantity' => $actualQty,
                            'variance' => $variance,
                            'notes' => $product['notes'] ?? ''
                        ];
                        $detailModel->create($detailData);
                    }
                }

                $_SESSION['success'] = 'Tạo phiếu kiểm kê thành công!';
                $this->redirect('stock_take');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
            }
        }

        // Get warehouses
        $userWarehouseId = Auth::getWarehouseId();
        if ($userWarehouseId) {
            $warehouses = [$this->warehouseModel->find($userWarehouseId)];
        } else {
            $warehouses = $this->warehouseModel->getAllActive();
        }

        $this->view('stock_take/create', [
            'title' => 'Tạo phiếu kiểm kê',
            'warehouses' => $warehouses
        ]);
    }

    public function detail($id)
    {
        Auth::requirePermission('stock_take.view');

        $stockTake = $this->stockTakeModel->getWithDetails($id);
        if (!$stockTake) {
            $_SESSION['error'] = 'Không tìm thấy phiếu kiểm kê!';
            $this->redirect('stock_take');
            return;
        }

        $detailModel = $this->model('StockTakeDetail');
        $details = $detailModel->getByStockTake($id);

        $this->view('stock_take/view', [
            'title' => 'Chi tiết phiếu kiểm kê',
            'stockTake' => $stockTake,
            'details' => $details
        ]);
    }

    public function edit($id)
    {
        Auth::requirePermission('stock_take.edit');

        $stockTake = $this->stockTakeModel->find($id);
        if (!$stockTake) {
            $_SESSION['error'] = 'Không tìm thấy phiếu kiểm kê!';
            $this->redirect('stock_take');
            return;
        }

        // Cannot edit completed stock takes
        if ($stockTake['status'] === 'completed') {
            $_SESSION['error'] = 'Không thể sửa phiếu kiểm kê đã hoàn thành!';
            $this->redirect('stock_take');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $warehouseId = $_POST['warehouse_id'] ?? null;
            $stockTakeDate = $_POST['stock_take_date'] ?? date('Y-m-d');
            $notes = trim($_POST['notes'] ?? '');
            $products = $_POST['products'] ?? [];

            // Validation
            if (empty($warehouseId) || empty($products)) {
                $_SESSION['error'] = 'Kho và sản phẩm không được để trống!';
                $this->redirect('stock_take/edit/' . $id);
                return;
            }

            $data = [
                'warehouse_id' => $warehouseId,
                'stock_take_date' => $stockTakeDate,
                'notes' => $notes
            ];

            if ($this->stockTakeModel->update($id, $data)) {
                // Delete old details and add new ones
                $detailModel = $this->model('StockTakeDetail');
                $detailModel->deleteByStockTake($id);

                foreach ($products as $product) {
                    if (!empty($product['product_id'])) {
                        $systemQty = $product['system_quantity'] ?? 0;
                        $actualQty = $product['actual_quantity'] ?? 0;
                        $variance = $actualQty - $systemQty;

                        $detailData = [
                            'stock_take_id' => $id,
                            'product_id' => $product['product_id'],
                            'system_quantity' => $systemQty,
                            'actual_quantity' => $actualQty,
                            'variance' => $variance,
                            'notes' => $product['notes'] ?? ''
                        ];
                        $detailModel->create($detailData);
                    }
                }

                $_SESSION['success'] = 'Cập nhật phiếu kiểm kê thành công!';
                $this->redirect('stock_take');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
            }
        }

        $detailModel = $this->model('StockTakeDetail');
        $details = $detailModel->getByStockTake($id);

        $this->view('stock_take/edit', [
            'title' => 'Sửa phiếu kiểm kê',
            'stockTake' => $stockTake,
            'details' => $details,
            'warehouses' => $this->warehouseModel->getAllActive()
        ]);
    }

    public function delete($id)
    {
        Auth::requirePermission('stock_take.delete');

        $stockTake = $this->stockTakeModel->find($id);
        if (!$stockTake) {
            $_SESSION['error'] = 'Không tìm thấy phiếu kiểm kê!';
            $this->redirect('stock_take');
            return;
        }

        // Cannot delete completed stock takes
        if ($stockTake['status'] === 'completed') {
            $_SESSION['error'] = 'Không thể xóa phiếu kiểm kê đã hoàn thành!';
            $this->redirect('stock_take');
            return;
        }

        // Delete details first
        $detailModel = $this->model('StockTakeDetail');
        $detailModel->deleteByStockTake($id);

        if ($this->stockTakeModel->delete($id)) {
            $_SESSION['success'] = 'Xóa phiếu kiểm kê thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
        }

        $this->redirect('stock_take');
    }

    public function approve($id)
    {
        Auth::requirePermission('stock_take.approve');

        $stockTake = $this->stockTakeModel->find($id);
        if (!$stockTake) {
            $_SESSION['error'] = 'Không tìm thấy phiếu kiểm kê!';
            $this->redirect('stock_take');
            return;
        }

        if ($stockTake['status'] === 'completed') {
            $_SESSION['error'] = 'Phiếu kiểm kê đã được duyệt!';
            $this->redirect('stock_take/detail/' . $id);
            return;
        }

        $detailModel = $this->model('StockTakeDetail');
        $details = $detailModel->getByStockTake($id);

        if (empty($details)) {
            $_SESSION['error'] = 'Phiếu kiểm kê chưa có sản phẩm!';
            $this->redirect('stock_take/detail/' . $id);
            return;
        }

        $transactionModel = $this->model('Transaction');

        try {
            // Adjust inventory based on variance
            foreach ($details as $detail) {
                if ($detail['variance'] != 0) {
                    // Get current balance
                    $current = $this->inventoryModel->query(
                        "SELECT quantity FROM inventory WHERE warehouse_id = ? AND product_id = ?",
                        [$stockTake['warehouse_id'], $detail['product_id']]
                    );
                    $balanceBefore = $current ? $current[0]['quantity'] : 0;

                    // Update inventory
                    $this->inventoryModel->updateQuantity(
                        $stockTake['warehouse_id'],
                        $detail['product_id'],
                        $detail['variance'],
                        'adjustment'
                    );

                    // Log transaction
                    $transactionModel->logTransaction([
                        'warehouse_id' => $stockTake['warehouse_id'],
                        'product_id' => $detail['product_id'],
                        'transaction_type' => 'adjustment',
                        'reference_type' => 'adjustment',
                        'reference_id' => $id,
                        'reference_code' => $stockTake['code'],
                        'quantity' => $detail['variance'],
                        'balance_before' => $balanceBefore,
                        'balance_after' => $detail['actual_quantity'],
                        'transaction_date' => $stockTake['stock_take_date'] . ' ' . date('H:i:s'),
                        'created_by' => Auth::id()
                    ]);
                }
            }

            // Update stock take status
            $this->stockTakeModel->approve($id, Auth::id());

            $_SESSION['success'] = 'Duyệt phiếu kiểm kê thành công!';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
        }

        $this->redirect('stock_take');
    }

    public function getInventory($warehouseId)
    {
        Auth::requirePermission('stock_take.create');

        $inventory = $this->inventoryModel->getByWarehouse($warehouseId);
        header('Content-Type: application/json');
        echo json_encode($inventory);
    }
}
