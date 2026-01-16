<?php

class AgencyController extends Controller
{
    private $agencyModel;

    public function __construct()
    {
        Auth::requireLogin();
        $this->agencyModel = $this->model('Agency');
    }

    public function index()
    {
        Auth::requirePermission('agency.view');

        $search = $_GET['search'] ?? '';
        
        if ($search) {
            $agencies = $this->agencyModel->search($search);
        } else {
            $agencies = $this->agencyModel->getAll();
        }

        $data = [
            'title' => 'Quản lý đại lý',
            'agencies' => $agencies,
            'search' => $search
        ];

        $this->view('agency/index', $data);
    }

    public function create()
    {
        Auth::requirePermission('agency.create');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $contractNumber = trim($_POST['contract_number'] ?? '');
            $contractDate = $_POST['contract_date'] ?? null;
            $representative = trim($_POST['representative'] ?? '');
            $idCard = trim($_POST['id_card'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $taxCode = trim($_POST['tax_code'] ?? '');
            $discountPercent = $_POST['discount_percent'] ?? 0;
            $notes = trim($_POST['notes'] ?? '');

            // Validation
            if (empty($code) || empty($name)) {
                $_SESSION['error'] = 'Mã đại lý và tên không được để trống!';
                $this->view('agency/create', [
                    'title' => 'Thêm đại lý',
                    'old' => $_POST
                ]);
                return;
            }

            // Check duplicate code
            if ($this->agencyModel->findByCode($code)) {
                $_SESSION['error'] = 'Mã đại lý đã tồn tại!';
                $this->view('agency/create', [
                    'title' => 'Thêm đại lý',
                    'old' => $_POST
                ]);
                return;
            }

            $data = [
                'code' => $code,
                'name' => $name,
                'address' => $address,
                'contract_number' => $contractNumber,
                'contract_date' => $contractDate,
                'representative' => $representative,
                'id_card' => $idCard,
                'phone' => $phone,
                'email' => $email,
                'tax_code' => $taxCode,
                'discount_percent' => $discountPercent,
                'notes' => $notes,
                'status' => 'active'
            ];

            if ($this->agencyModel->create($data)) {
                $_SESSION['success'] = 'Thêm đại lý thành công!';
                $this->redirect('agency');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
            }
        }

        $this->view('agency/create', ['title' => 'Thêm đại lý']);
    }

    public function edit($id)
    {
        Auth::requirePermission('agency.edit');

        $agency = $this->agencyModel->find($id);
        if (!$agency) {
            $_SESSION['error'] = 'Không tìm thấy đại lý!';
            $this->redirect('agency');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $contractNumber = trim($_POST['contract_number'] ?? '');
            $contractDate = $_POST['contract_date'] ?? null;
            $representative = trim($_POST['representative'] ?? '');
            $idCard = trim($_POST['id_card'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $taxCode = trim($_POST['tax_code'] ?? '');
            $discountPercent = $_POST['discount_percent'] ?? 0;
            $status = $_POST['status'] ?? 'active';
            $notes = trim($_POST['notes'] ?? '');

            // Validation
            if (empty($code) || empty($name)) {
                $_SESSION['error'] = 'Mã đại lý và tên không được để trống!';
                $this->view('agency/edit', [
                    'title' => 'Sửa đại lý',
                    'agency' => $agency,
                    'old' => $_POST
                ]);
                return;
            }

            // Check duplicate code
            $existing = $this->agencyModel->findByCode($code);
            if ($existing && $existing['id'] != $id) {
                $_SESSION['error'] = 'Mã đại lý đã tồn tại!';
                $this->view('agency/edit', [
                    'title' => 'Sửa đại lý',
                    'agency' => $agency,
                    'old' => $_POST
                ]);
                return;
            }

            $data = [
                'code' => $code,
                'name' => $name,
                'address' => $address,
                'contract_number' => $contractNumber,
                'contract_date' => $contractDate,
                'representative' => $representative,
                'id_card' => $idCard,
                'phone' => $phone,
                'email' => $email,
                'tax_code' => $taxCode,
                'discount_percent' => $discountPercent,
                'status' => $status,
                'notes' => $notes
            ];

            if ($this->agencyModel->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật đại lý thành công!';
                $this->redirect('agency');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
            }
        }

        $this->view('agency/edit', [
            'title' => 'Sửa đại lý',
            'agency' => $agency
        ]);
    }

    public function delete($id)
    {
        Auth::requirePermission('agency.delete');

        if ($this->agencyModel->hasExports($id)) {
            $_SESSION['error'] = 'Không thể xóa đại lý đã có phiếu xuất!';
            $this->redirect('agency');
            return;
        }

        if ($this->agencyModel->delete($id)) {
            $_SESSION['success'] = 'Xóa đại lý thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
        }

        $this->redirect('agency');
    }

    public function detail($id)
    {
        Auth::requirePermission('agency.view');

        $agency = $this->agencyModel->getWithStats($id);
        if (!$agency) {
            $_SESSION['error'] = 'Không tìm thấy đại lý!';
            $this->redirect('agency');
            return;
        }

        // Get exports for this agency
        $exportModel = $this->model('Export');
        $exports = $exportModel->query(
            "SELECT * FROM exports WHERE agency_id = ? ORDER BY export_date DESC LIMIT 50",
            [$id]
        );

        $this->view('agency/detail', [
            'title' => 'Chi tiết đại lý',
            'agency' => $agency,
            'exports' => $exports
        ]);
    }
}
