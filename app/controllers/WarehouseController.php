<?php

class WarehouseController extends Controller
{
    private $warehouseModel;

    public function __construct()
    {
        Auth::requireLogin();
        $this->warehouseModel = $this->model('Warehouse');
    }

    public function index()
    {
        Auth::requirePermission('warehouse.view');

        $search = $_GET['search'] ?? '';
        
        if ($search) {
            $warehouses = $this->warehouseModel->search($search);
        } else {
            $warehouses = $this->warehouseModel->getAll();
        }

        $data = [
            'title' => 'Kho hàng',
            'warehouses' => $warehouses,
            'search' => $search
        ];

        $this->view('warehouse/index', $data);
    }

    public function create()
    {
        Auth::requirePermission('warehouse.create');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $managerName = trim($_POST['manager_name'] ?? '');
            $managerId = $_POST['manager_id'] ?? null;
            $phone = trim($_POST['phone'] ?? '');
            $capacity = floatval($_POST['capacity'] ?? 0);
            $status = $_POST['status'] ?? 'active';
            $notes = trim($_POST['notes'] ?? '');

            // Validation
            if (empty($code) || empty($name)) {
                $_SESSION['error'] = 'Mã và tên kho không được để trống!';
                $this->view('warehouse/create', [
                    'title' => 'Thêm kho hàng',
                    'old' => $_POST
                ]);
                return;
            }

            // Check duplicate code
            if ($this->warehouseModel->findByCode($code)) {
                $_SESSION['error'] = 'Mã kho đã tồn tại!';
                $this->view('warehouse/create', [
                    'title' => 'Thêm kho hàng',
                    'old' => $_POST
                ]);
                return;
            }

            $data = [
                'code' => $code,
                'name' => $name,
                'address' => $address,
                'manager_name' => $managerName,
                'manager_id' => $managerId,
                'phone' => $phone,
                'capacity' => $capacity,
                'status' => $status,
                'notes' => $notes
            ];

            if ($this->warehouseModel->create($data)) {
                $_SESSION['success'] = 'Thêm kho hàng thành công!';
                $this->redirect('warehouse');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
            }
        }

        $userModel = $this->model('User');
        $this->view('warehouse/create', [
            'title' => 'Thêm kho hàng',
            'users' => $userModel->getAll()
        ]);
    }

    public function edit($id)
    {
        Auth::requirePermission('warehouse.edit');

        $warehouse = $this->warehouseModel->find($id);
        if (!$warehouse) {
            $_SESSION['error'] = 'Không tìm thấy kho hàng!';
            $this->redirect('warehouse');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $managerName = trim($_POST['manager_name'] ?? '');
            $managerId = $_POST['manager_id'] ?? null;
            $phone = trim($_POST['phone'] ?? '');
            $capacity = floatval($_POST['capacity'] ?? 0);
            $status = $_POST['status'] ?? 'active';
            $notes = trim($_POST['notes'] ?? '');

            // Validation
            if (empty($code) || empty($name)) {
                $_SESSION['error'] = 'Mã và tên kho không được để trống!';
                $this->view('warehouse/edit', [
                    'title' => 'Sửa kho hàng',
                    'warehouse' => array_merge($warehouse, $_POST)
                ]);
                return;
            }

            // Check duplicate code (exclude current)
            $existing = $this->warehouseModel->findByCode($code);
            if ($existing && $existing['id'] != $id) {
                $_SESSION['error'] = 'Mã kho đã tồn tại!';
                $this->view('warehouse/edit', [
                    'title' => 'Sửa kho hàng',
                    'warehouse' => array_merge($warehouse, $_POST)
                ]);
                return;
            }

            $data = [
                'code' => $code,
                'name' => $name,
                'address' => $address,
                'manager_name' => $managerName,
                'manager_id' => $managerId,
                'phone' => $phone,
                'capacity' => $capacity,
                'status' => $status,
                'notes' => $notes
            ];

            if ($this->warehouseModel->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật kho hàng thành công!';
                $this->redirect('warehouse');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
            }
        }

        $userModel = $this->model('User');
        $this->view('warehouse/edit', [
            'title' => 'Sửa kho hàng',
            'warehouse' => $warehouse,
            'users' => $userModel->getAll()
        ]);
    }

    public function delete($id)
    {
        Auth::requirePermission('warehouse.delete');

        $warehouse = $this->warehouseModel->find($id);
        if (!$warehouse) {
            $_SESSION['error'] = 'Không tìm thấy kho hàng!';
            $this->redirect('warehouse');
            return;
        }

        // Check if warehouse has inventory
        if ($this->warehouseModel->hasInventory($id)) {
            $_SESSION['error'] = 'Không thể xóa kho đang có hàng tồn!';
            $this->redirect('warehouse');
            return;
        }

        if ($this->warehouseModel->delete($id)) {
            $_SESSION['success'] = 'Xóa kho hàng thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
        }

        $this->redirect('warehouse');
    }
}
