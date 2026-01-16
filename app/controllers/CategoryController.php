<?php

class CategoryController extends Controller
{
    private $categoryModel;

    public function __construct()
    {
        Auth::requireLogin();
        $this->categoryModel = $this->model('Category');
    }

    public function index()
    {
        Auth::requirePermission('category.view');

        $search = $_GET['search'] ?? '';
        
        if ($search) {
            $categories = $this->categoryModel->search($search);
        } else {
            $categories = $this->categoryModel->getAll();
        }

        $data = [
            'title' => 'Danh mục sản phẩm',
            'categories' => $categories,
            'search' => $search
        ];

        $this->view('category/index', $data);
    }

    public function create()
    {
        Auth::requirePermission('category.create');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $status = $_POST['status'] ?? 'active';

            // Validation
            if (empty($code) || empty($name)) {
                $_SESSION['error'] = 'Mã và tên danh mục không được để trống!';
                $this->view('category/create', [
                    'title' => 'Thêm danh mục',
                    'old' => $_POST
                ]);
                return;
            }

            // Check duplicate code
            if ($this->categoryModel->findByCode($code)) {
                $_SESSION['error'] = 'Mã danh mục đã tồn tại!';
                $this->view('category/create', [
                    'title' => 'Thêm danh mục',
                    'old' => $_POST
                ]);
                return;
            }

            $data = [
                'code' => $code,
                'name' => $name,
                'description' => $description,
                'status' => $status
            ];

            if ($this->categoryModel->create($data)) {
                $_SESSION['success'] = 'Thêm danh mục thành công!';
                $this->redirect('category');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
            }
        }

        $this->view('category/create', ['title' => 'Thêm danh mục']);
    }

    public function edit($id)
    {
        Auth::requirePermission('category.edit');

        $category = $this->categoryModel->find($id);
        if (!$category) {
            $_SESSION['error'] = 'Không tìm thấy danh mục!';
            $this->redirect('category');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = trim($_POST['code'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $status = $_POST['status'] ?? 'active';

            // Validation
            if (empty($code) || empty($name)) {
                $_SESSION['error'] = 'Mã và tên danh mục không được để trống!';
                $this->view('category/edit', [
                    'title' => 'Sửa danh mục',
                    'category' => array_merge($category, $_POST)
                ]);
                return;
            }

            // Check duplicate code (exclude current)
            $existing = $this->categoryModel->findByCode($code);
            if ($existing && $existing['id'] != $id) {
                $_SESSION['error'] = 'Mã danh mục đã tồn tại!';
                $this->view('category/edit', [
                    'title' => 'Sửa danh mục',
                    'category' => array_merge($category, $_POST)
                ]);
                return;
            }

            $data = [
                'code' => $code,
                'name' => $name,
                'description' => $description,
                'status' => $status
            ];

            if ($this->categoryModel->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật danh mục thành công!';
                $this->redirect('category');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
            }
        }

        $this->view('category/edit', [
            'title' => 'Sửa danh mục',
            'category' => $category
        ]);
    }

    public function delete($id)
    {
        Auth::requirePermission('category.delete');

        $category = $this->categoryModel->find($id);
        if (!$category) {
            $_SESSION['error'] = 'Không tìm thấy danh mục!';
            $this->redirect('category');
            return;
        }

        // Check if category has products
        if ($this->categoryModel->hasProducts($id)) {
            $_SESSION['error'] = 'Không thể xóa danh mục đang có sản phẩm!';
            $this->redirect('category');
            return;
        }

        if ($this->categoryModel->delete($id)) {
            $_SESSION['success'] = 'Xóa danh mục thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
        }

        $this->redirect('category');
    }
}
