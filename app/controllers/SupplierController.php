<?php

class SupplierController extends Controller
{
    private $supplierModel;

    public function __construct()
    {
        Auth::requireLogin();
        $this->supplierModel = $this->model('Supplier');
    }

    public function index()
    {
        Auth::requirePermission('supplier.view');

        $search = $_GET['search'] ?? '';
        
        if ($search) {
            $suppliers = $this->supplierModel->search($search);
        } else {
            $suppliers = $this->supplierModel->getAll();
        }

        $data = [
            'title' => 'Nhà cung cấp',
            'suppliers' => $suppliers,
            'search' => $search
        ];

        $this->view('supplier/index', $data);
    }

    public function create()
    {
        Auth::requirePermission('supplier.create');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $contactPerson = trim($_POST['contact_person'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $taxCode = trim($_POST['tax_code'] ?? '');
            $status = $_POST['status'] ?? 'active';
            $notes = trim($_POST['notes'] ?? '');

            // Validation
            if (empty($code) || empty($name)) {
                $_SESSION['error'] = 'Mã và tên nhà cung cấp không được để trống!';
                $this->view('supplier/create', [
                    'title' => 'Thêm nhà cung cấp',
                    'old' => $_POST
                ]);
                return;
            }

            // Check duplicate code
            if ($this->supplierModel->findByCode($code)) {
                $_SESSION['error'] = 'Mã nhà cung cấp đã tồn tại!';
                $this->view('supplier/create', [
                    'title' => 'Thêm nhà cung cấp',
                    'old' => $_POST
                ]);
                return;
            }

            $data = [
                'code' => $code,
                'name' => $name,
                'contact_person' => $contactPerson,
                'phone' => $phone,
                'email' => $email,
                'address' => $address,
                'tax_code' => $taxCode,
                'status' => $status,
                'notes' => $notes
            ];

            if ($this->supplierModel->create($data)) {
                $_SESSION['success'] = 'Thêm nhà cung cấp thành công!';
                $this->redirect('supplier');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
            }
        }

        $this->view('supplier/create', ['title' => 'Thêm nhà cung cấp']);
    }

    public function edit($id)
    {
        Auth::requirePermission('supplier.edit');

        $supplier = $this->supplierModel->find($id);
        if (!$supplier) {
            $_SESSION['error'] = 'Không tìm thấy nhà cung cấp!';
            $this->redirect('supplier');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $contactPerson = trim($_POST['contact_person'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $taxCode = trim($_POST['tax_code'] ?? '');
            $status = $_POST['status'] ?? 'active';
            $notes = trim($_POST['notes'] ?? '');

            // Validation
            if (empty($code) || empty($name)) {
                $_SESSION['error'] = 'Mã và tên nhà cung cấp không được để trống!';
                $this->view('supplier/edit', [
                    'title' => 'Sửa nhà cung cấp',
                    'supplier' => array_merge($supplier, $_POST)
                ]);
                return;
            }

            // Check duplicate code (exclude current)
            $existing = $this->supplierModel->findByCode($code);
            if ($existing && $existing['id'] != $id) {
                $_SESSION['error'] = 'Mã nhà cung cấp đã tồn tại!';
                $this->view('supplier/edit', [
                    'title' => 'Sửa nhà cung cấp',
                    'supplier' => array_merge($supplier, $_POST)
                ]);
                return;
            }

            $data = [
                'code' => $code,
                'name' => $name,
                'contact_person' => $contactPerson,
                'phone' => $phone,
                'email' => $email,
                'address' => $address,
                'tax_code' => $taxCode,
                'status' => $status,
                'notes' => $notes
            ];

            if ($this->supplierModel->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật nhà cung cấp thành công!';
                $this->redirect('supplier');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
            }
        }

        $this->view('supplier/edit', [
            'title' => 'Sửa nhà cung cấp',
            'supplier' => $supplier
        ]);
    }

    public function delete($id)
    {
        Auth::requirePermission('supplier.delete');

        $supplier = $this->supplierModel->find($id);
        if (!$supplier) {
            $_SESSION['error'] = 'Không tìm thấy nhà cung cấp!';
            $this->redirect('supplier');
            return;
        }

        // Check if supplier has imports
        if ($this->supplierModel->hasImports($id)) {
            $_SESSION['error'] = 'Không thể xóa nhà cung cấp đã có phiếu nhập!';
            $this->redirect('supplier');
            return;
        }

        if ($this->supplierModel->delete($id)) {
            $_SESSION['success'] = 'Xóa nhà cung cấp thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
        }

        $this->redirect('supplier');
    }
}
