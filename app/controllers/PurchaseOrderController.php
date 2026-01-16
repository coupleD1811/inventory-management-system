<?php

class PurchaseOrderController extends Controller
{
    private $purchaseOrderModel;
    private $supplierModel;
    private $productModel;

    public function __construct()
    {
        Auth::requireLogin();
        $this->purchaseOrderModel = $this->model('PurchaseOrder');
        $this->supplierModel = $this->model('Supplier');
        $this->productModel = $this->model('Product');
    }

    public function index()
    {
        Auth::requirePermission('purchase_order.view');

        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        
        if ($search) {
            $purchaseOrders = $this->purchaseOrderModel->search($search);
        } elseif ($status) {
            $purchaseOrders = $this->purchaseOrderModel->getByStatus($status);
        } else {
            $purchaseOrders = $this->purchaseOrderModel->getAllWithDetails();
        }

        $data = [
            'title' => 'Đơn đặt hàng',
            'purchaseOrders' => $purchaseOrders,
            'search' => $search,
            'status' => $status
        ];

        $this->view('purchase_order/index', $data);
    }

    public function create()
    {
        Auth::requirePermission('purchase_order.create');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $this->purchaseOrderModel->generateCode();
            $supplierId = $_POST['supplier_id'] ?? null;
            $orderDate = $_POST['order_date'] ?? date('Y-m-d');
            $expectedDeliveryDate = $_POST['expected_delivery_date'] ?? null;
            $notes = trim($_POST['notes'] ?? '');
            $products = $_POST['products'] ?? [];

            // Validation
            if (empty($supplierId) || empty($products)) {
                $_SESSION['error'] = 'Nhà cung cấp và sản phẩm không được để trống!';
                $this->view('purchase_order/create', [
                    'title' => 'Tạo đơn đặt hàng',
                    'suppliers' => $this->supplierModel->getAllActive(),
                    'products' => $this->productModel->getAllActive(),
                    'old' => $_POST
                ]);
                return;
            }

            // Calculate total
            $totalAmount = 0;
            foreach ($products as $product) {
                if (!empty($product['product_id']) && !empty($product['quantity']) && !empty($product['unit_price'])) {
                    $totalAmount += $product['quantity'] * $product['unit_price'];
                }
            }

            $data = [
                'code' => $code,
                'supplier_id' => $supplierId,
                'order_date' => $orderDate,
                'expected_delivery_date' => $expectedDeliveryDate,
                'total_amount' => $totalAmount,
                'status' => 'draft',
                'notes' => $notes,
                'created_by' => Auth::id()
            ];

            $purchaseOrderId = $this->purchaseOrderModel->create($data);
            
            if ($purchaseOrderId) {
                // Add products
                $detailModel = $this->model('PurchaseOrderDetail');
                $productCount = 0;
                foreach ($products as $product) {
                    if (!empty($product['product_id']) && !empty($product['quantity']) && !empty($product['unit_price'])) {
                        $detailData = [
                            'purchase_order_id' => $purchaseOrderId,
                            'product_id' => $product['product_id'],
                            'quantity' => $product['quantity'],
                            'unit_price' => $product['unit_price'],
                            'total_price' => $product['quantity'] * $product['unit_price'],
                            'notes' => $product['notes'] ?? ''
                        ];
                        $detailModel->create($detailData);
                        $productCount++;
                    }
                }

                if ($productCount > 0) {
                    $_SESSION['success'] = 'Tạo đơn đặt hàng thành công!';
                    $this->redirect('purchase_order');
                } else {
                    // Delete the order if no products were added
                    $this->purchaseOrderModel->delete($purchaseOrderId);
                    $_SESSION['error'] = 'Vui lòng thêm ít nhất 1 sản phẩm hợp lệ (có đầy đủ số lượng và đơn giá)!';
                    $this->view('purchase_order/create', [
                        'title' => 'Tạo đơn đặt hàng',
                        'suppliers' => $this->supplierModel->getAllActive(),
                        'products' => $this->productModel->getAllActive(),
                        'old' => $_POST
                    ]);
                    return;
                }
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra khi tạo đơn đặt hàng, vui lòng thử lại!';
                $this->view('purchase_order/create', [
                    'title' => 'Tạo đơn đặt hàng',
                    'suppliers' => $this->supplierModel->getAllActive(),
                    'products' => $this->productModel->getAllActive(),
                    'old' => $_POST
                ]);
                return;
            }
        }

        $this->view('purchase_order/create', [
            'title' => 'Tạo đơn đặt hàng',
            'suppliers' => $this->supplierModel->getAllActive(),
            'products' => $this->productModel->getAllActive()
        ]);
    }

    public function detail($id)
    {
        Auth::requirePermission('purchase_order.view');

        $purchaseOrder = $this->purchaseOrderModel->getWithDetails($id);
        if (!$purchaseOrder) {
            $_SESSION['error'] = 'Không tìm thấy đơn đặt hàng!';
            $this->redirect('purchase_order');
            return;
        }

        $detailModel = $this->model('PurchaseOrderDetail');
        $details = $detailModel->getByPurchaseOrder($id);

        $this->view('purchase_order/view', [
            'title' => 'Chi tiết đơn đặt hàng',
            'purchaseOrder' => $purchaseOrder,
            'details' => $details
        ]);
    }

    public function edit($id)
    {
        Auth::requirePermission('purchase_order.edit');

        $purchaseOrder = $this->purchaseOrderModel->find($id);
        if (!$purchaseOrder) {
            $_SESSION['error'] = 'Không tìm thấy đơn đặt hàng!';
            $this->redirect('purchase_order');
            return;
        }

        // Cannot edit confirmed or received orders
        if (in_array($purchaseOrder['status'], ['confirmed', 'received'])) {
            $_SESSION['error'] = 'Không thể sửa đơn đặt hàng đã duyệt!';
            $this->redirect('purchase_order');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $supplierId = $_POST['supplier_id'] ?? null;
            $orderDate = $_POST['order_date'] ?? date('Y-m-d');
            $expectedDeliveryDate = $_POST['expected_delivery_date'] ?? null;
            $notes = trim($_POST['notes'] ?? '');
            $products = $_POST['products'] ?? [];

            // Validation
            if (empty($supplierId) || empty($products)) {
                $_SESSION['error'] = 'Nhà cung cấp và sản phẩm không được để trống!';
                $this->redirect('purchase_order/edit/' . $id);
                return;
            }

            // Calculate total
            $totalAmount = 0;
            foreach ($products as $product) {
                if (!empty($product['product_id']) && !empty($product['quantity']) && !empty($product['unit_price'])) {
                    $totalAmount += $product['quantity'] * $product['unit_price'];
                }
            }

            $data = [
                'supplier_id' => $supplierId,
                'order_date' => $orderDate,
                'expected_delivery_date' => $expectedDeliveryDate,
                'total_amount' => $totalAmount,
                'notes' => $notes
            ];

            if ($this->purchaseOrderModel->update($id, $data)) {
                // Delete old details and add new ones
                $detailModel = $this->model('PurchaseOrderDetail');
                $detailModel->deleteByPurchaseOrder($id);

                foreach ($products as $product) {
                    if (!empty($product['product_id']) && !empty($product['quantity']) && !empty($product['unit_price'])) {
                        $detailData = [
                            'purchase_order_id' => $id,
                            'product_id' => $product['product_id'],
                            'quantity' => $product['quantity'],
                            'unit_price' => $product['unit_price'],
                            'total_price' => $product['quantity'] * $product['unit_price'],
                            'notes' => $product['notes'] ?? ''
                        ];
                        $detailModel->create($detailData);
                    }
                }

                $_SESSION['success'] = 'Cập nhật đơn đặt hàng thành công!';
                $this->redirect('purchase_order');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
            }
        }

        $detailModel = $this->model('PurchaseOrderDetail');
        $details = $detailModel->getByPurchaseOrder($id);

        $this->view('purchase_order/edit', [
            'title' => 'Sửa đơn đặt hàng',
            'purchaseOrder' => $purchaseOrder,
            'details' => $details,
            'suppliers' => $this->supplierModel->getAllActive(),
            'products' => $this->productModel->getAllActive()
        ]);
    }

    public function delete($id)
    {
        Auth::requirePermission('purchase_order.delete');

        $purchaseOrder = $this->purchaseOrderModel->find($id);
        if (!$purchaseOrder) {
            $_SESSION['error'] = 'Không tìm thấy đơn đặt hàng!';
            $this->redirect('purchase_order');
            return;
        }

        // Cannot delete confirmed or received orders
        if (in_array($purchaseOrder['status'], ['confirmed', 'received'])) {
            $_SESSION['error'] = 'Không thể xóa đơn đặt hàng đã duyệt!';
            $this->redirect('purchase_order');
            return;
        }

        // Delete details first
        $detailModel = $this->model('PurchaseOrderDetail');
        $detailModel->deleteByPurchaseOrder($id);

        if ($this->purchaseOrderModel->delete($id)) {
            $_SESSION['success'] = 'Xóa đơn đặt hàng thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
        }

        $this->redirect('purchase_order');
    }

    public function approve($id)
    {
        Auth::requirePermission('purchase_order.approve');

        $purchaseOrder = $this->purchaseOrderModel->find($id);
        if (!$purchaseOrder) {
            $_SESSION['error'] = 'Không tìm thấy đơn đặt hàng!';
            $this->redirect('purchase_order');
            return;
        }

        if ($purchaseOrder['status'] !== 'draft' && $purchaseOrder['status'] !== 'sent') {
            $_SESSION['error'] = 'Đơn đặt hàng đã được duyệt hoặc đã hủy!';
            $this->redirect('purchase_order/detail/' . $id);
            return;
        }

        if ($this->purchaseOrderModel->approve($id, Auth::id())) {
            $_SESSION['success'] = 'Duyệt đơn đặt hàng thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
        }

        $this->redirect('purchase_order');
    }
}
