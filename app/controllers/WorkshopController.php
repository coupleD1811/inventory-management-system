<?php

class WorkshopController extends Controller
{
    private $workshopModel;

    public function __construct()
    {
        Auth::requireLogin();
        $this->workshopModel = $this->model('Workshop');
    }

    public function index()
    {
        Auth::requirePermission('workshop.view');

        $search = $_GET['search'] ?? '';
        
        if ($search) {
            $workshops = $this->workshopModel->search($search);
        } else {
            $workshops = $this->workshopModel->getAll();
        }

        $data = [
            'title' => 'Phân xưởng sản xuất',
            'workshops' => $workshops,
            'search' => $search
        ];

        $this->view('workshop/index', $data);
    }

    public function create()
    {
        Auth::requirePermission('workshop.create');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $this->workshopModel->generateCode();
            $name = trim($_POST['name'] ?? '');
            $location = trim($_POST['location'] ?? '');
            $managerName = trim($_POST['manager_name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $status = $_POST['status'] ?? 'active';
            $notes = trim($_POST['notes'] ?? '');

            // Validation
            if (empty($name)) {
                $_SESSION['error'] = 'Tên phân xưởng không được để trống!';
                $this->view('workshop/create', [
                    'title' => 'Thêm phân xưởng',
                    'old' => $_POST
                ]);
                return;
            }

            $data = [
                'code' => $code,
                'name' => $name,
                'location' => $location,
                'manager_name' => $managerName,
                'phone' => $phone,
                'status' => $status,
                'notes' => $notes
            ];

            if ($this->workshopModel->create($data)) {
                $_SESSION['success'] = 'Thêm phân xưởng thành công!';
                $this->redirect('workshop');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
            }
        }

        $this->view('workshop/create', [
            'title' => 'Thêm phân xưởng'
        ]);
    }

    public function edit($id)
    {
        Auth::requirePermission('workshop.edit');

        $workshop = $this->workshopModel->find($id);
        if (!$workshop) {
            $_SESSION['error'] = 'Không tìm thấy phân xưởng!';
            $this->redirect('workshop');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $location = trim($_POST['location'] ?? '');
            $managerName = trim($_POST['manager_name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $status = $_POST['status'] ?? 'active';
            $notes = trim($_POST['notes'] ?? '');

            // Validation
            if (empty($name)) {
                $_SESSION['error'] = 'Tên phân xưởng không được để trống!';
                $this->redirect('workshop/edit/' . $id);
                return;
            }

            $data = [
                'name' => $name,
                'location' => $location,
                'manager_name' => $managerName,
                'phone' => $phone,
                'status' => $status,
                'notes' => $notes
            ];

            if ($this->workshopModel->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật phân xưởng thành công!';
                $this->redirect('workshop');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
            }
        }

        $this->view('workshop/edit', [
            'title' => 'Sửa phân xưởng',
            'workshop' => $workshop
        ]);
    }

    public function delete($id)
    {
        Auth::requirePermission('workshop.delete');

        $workshop = $this->workshopModel->find($id);
        if (!$workshop) {
            $_SESSION['error'] = 'Không tìm thấy phân xưởng!';
            $this->redirect('workshop');
            return;
        }

        if ($this->workshopModel->delete($id)) {
            $_SESSION['success'] = 'Xóa phân xưởng thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
        }

        $this->redirect('workshop');
    }
}
