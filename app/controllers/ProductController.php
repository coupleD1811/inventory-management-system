<?php

class ProductController extends Controller
{
    private $productModel;
    private $warehouseModel;

    public function __construct()
    {
        Auth::requireLogin();
        $this->productModel = $this->model('Product');
        $this->warehouseModel = $this->model('Warehouse');
    }

    public function index()
    {
        Auth::requirePermission('product.view');

        $search = $_GET['search'] ?? '';
        $userWarehouseId = Auth::getWarehouseId();
        $warehouseId = $userWarehouseId ?? ($_GET['warehouse_id'] ?? '');
        $warehouseCode = $_GET['warehouse_code'] ?? '';

        if (!$warehouseId && $warehouseCode) {
            $warehouse = $this->warehouseModel->findByCode($warehouseCode);
            $warehouseId = $warehouse['id'] ?? '';
        }
        
        if ($search || $warehouseId) {
            $products = $this->productModel->search($search, $warehouseId);
        } else {
            $products = $this->productModel->getAllWithWarehouse();
        }

        $data = [
            'title' => 'Sản phẩm',
            'products' => $products,
            'search' => $search,
            'warehouseId' => $warehouseId,
            'warehouses' => $this->warehouseModel->getPrimaryWarehouses()
        ];

        $this->view('product/index', $data);
    }

    public function create()
    {
        Auth::requirePermission('product.create');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $warehouseId = $_POST['warehouse_id'] ?? null;
            $unit = trim($_POST['unit'] ?? 'cái');
            $description = trim($_POST['description'] ?? '');
            $minStock = (int)($_POST['min_stock'] ?? 0);
            $maxStock = (int)($_POST['max_stock'] ?? 0);
            $status = $_POST['status'] ?? 'active';

            // Validation
            if (empty($code) || empty($name) || empty($warehouseId)) {
                $_SESSION['error'] = 'Mã, tên sản phẩm và kho không được để trống!';
                $this->view('product/create', [
                    'title' => 'Thêm sản phẩm',
                    'warehouses' => $this->warehouseModel->getPrimaryWarehouses(),
                    'old' => $_POST
                ]);
                return;
            }

            // Check duplicate code
            if ($this->productModel->findByCode($code)) {
                $_SESSION['error'] = 'Mã sản phẩm đã tồn tại!';
                $this->view('product/create', [
                    'title' => 'Thêm sản phẩm',
                    'warehouses' => $this->warehouseModel->getPrimaryWarehouses(),
                    'old' => $_POST
                ]);
                return;
            }

            // Handle image upload
            $imagePath = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = $this->uploadImage($_FILES['image']);
                if (!$imagePath) {
                    $_SESSION['error'] = 'Lỗi upload ảnh!';
                    $this->view('product/create', [
                        'title' => 'Thêm sản phẩm',
                        'warehouses' => $this->warehouseModel->getPrimaryWarehouses(),
                        'old' => $_POST
                    ]);
                    return;
                }
            }

            $data = [
                'code' => $code,
                'name' => $name,
                'warehouse_id' => $warehouseId,
                'unit' => $unit,
                'description' => $description,
                'image' => $imagePath,
                'min_stock' => $minStock,
                'max_stock' => $maxStock,
                'status' => $status
            ];

            if ($this->productModel->create($data)) {
                $_SESSION['success'] = 'Thêm sản phẩm thành công!';
                $this->redirect('product');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
            }
        }

        $this->view('product/create', [
            'title' => 'Thêm sản phẩm',
            'warehouses' => $this->warehouseModel->getPrimaryWarehouses()
        ]);
    }

    public function edit($id)
    {
        Auth::requirePermission('product.edit');

        $product = $this->productModel->find($id);
        if (!$product) {
            $_SESSION['error'] = 'Không tìm thấy sản phẩm!';
            $this->redirect('product');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $warehouseId = $_POST['warehouse_id'] ?? null;
            $unit = trim($_POST['unit'] ?? 'cái');
            $description = trim($_POST['description'] ?? '');
            $minStock = (int)($_POST['min_stock'] ?? 0);
            $maxStock = (int)($_POST['max_stock'] ?? 0);
            $status = $_POST['status'] ?? 'active';

            // Validation
            if (empty($code) || empty($name) || empty($warehouseId)) {
                $_SESSION['error'] = 'Mã, tên sản phẩm và kho không được để trống!';
                $this->view('product/edit', [
                    'title' => 'Sửa sản phẩm',
                    'product' => array_merge($product, $_POST),
                    'warehouses' => $this->warehouseModel->getPrimaryWarehouses()
                ]);
                return;
            }

            // Check duplicate code (exclude current)
            $existing = $this->productModel->findByCode($code);
            if ($existing && $existing['id'] != $id) {
                $_SESSION['error'] = 'Mã sản phẩm đã tồn tại!';
                $this->view('product/edit', [
                    'title' => 'Sửa sản phẩm',
                    'product' => array_merge($product, $_POST),
                    'warehouses' => $this->warehouseModel->getPrimaryWarehouses()
                ]);
                return;
            }

            // Handle image upload
            $imagePath = $product['image'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $newImagePath = $this->uploadImage($_FILES['image']);
                if ($newImagePath) {
                    // Delete old image
                    $oldImagePath = __DIR__ . '/../../public/uploads/products/' . $imagePath;
                    if ($imagePath && file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                    $imagePath = $newImagePath;
                }
            }

            $data = [
                'code' => $code,
                'name' => $name,
                'warehouse_id' => $warehouseId,
                'unit' => $unit,
                'description' => $description,
                'image' => $imagePath,
                'min_stock' => $minStock,
                'max_stock' => $maxStock,
                'status' => $status
            ];

            if ($this->productModel->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật sản phẩm thành công!';
                $this->redirect('product');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
            }
        }

        $this->view('product/edit', [
            'title' => 'Sửa sản phẩm',
            'product' => $product,
            'warehouses' => $this->warehouseModel->getPrimaryWarehouses()
        ]);
    }

    public function delete($id)
    {
        Auth::requirePermission('product.delete');

        $product = $this->productModel->find($id);
        if (!$product) {
            $_SESSION['error'] = 'Không tìm thấy sản phẩm!';
            $this->redirect('product');
            return;
        }

        // Check if product is in inventory
        if ($this->productModel->hasInventory($id)) {
            $_SESSION['error'] = 'Không thể xóa sản phẩm đang có trong kho!';
            $this->redirect('product');
            return;
        }

        // Delete image
        $imagePath = __DIR__ . '/../../public/uploads/products/' . $product['image'];
        if ($product['image'] && file_exists($imagePath)) {
            unlink($imagePath);
        }

        if ($this->productModel->delete($id)) {
            $_SESSION['success'] = 'Xóa sản phẩm thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
        }

        $this->redirect('product');
    }

    private function uploadImage($file)
    {
        $uploadDir = __DIR__ . '/../../public/uploads/products/';
        
        // Create directory if not exists
        if (!file_exists($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                error_log("Failed to create upload directory: " . $uploadDir);
                $_SESSION['error'] = 'Không thể tạo thư mục upload!';
                return false;
            }
        }

        // Check if directory is writable
        if (!is_writable($uploadDir)) {
            error_log("Upload directory is not writable: " . $uploadDir);
            $_SESSION['error'] = 'Thư mục upload không có quyền ghi!';
            return false;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            $_SESSION['error'] = 'Định dạng file không hợp lệ! Chỉ chấp nhận: JPG, PNG, GIF, WEBP';
            return false;
        }

        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $maxSize) {
            $_SESSION['error'] = 'Kích thước file quá lớn! Tối đa 5MB';
            return false;
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $filepath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $filename;
        }

        error_log("Failed to move uploaded file to: " . $filepath);
        $_SESSION['error'] = 'Lỗi khi lưu file!';
        return false;
    }
}
