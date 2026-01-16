<?php

class UserController extends Controller
{
    private $userModel;
    private $roleModel;

    public function __construct()
    {
        Auth::requireLogin();
        $this->userModel = $this->model('User');
        $this->roleModel = $this->model('Role');
    }

    public function index()
    {
        Auth::requirePermission('user.view');

        $users = $this->userModel->getAllWithRoles();

        $data = [
            'title' => 'Người dùng',
            'users' => $users
        ];

        $this->view('user/index', $data);
    }

    public function create()
    {
        Auth::requirePermission('user.create');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirmPassword = trim($_POST['confirm_password'] ?? '');
            $fullName = trim($_POST['full_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $roleId = $_POST['role_id'] ?? null;
            $warehouseId = Auth::isAdmin() ? ($_POST['warehouse_id'] ?? null) : null;
            $status = $_POST['status'] ?? 'active';

            // Validation
            if (empty($username) || empty($password) || empty($fullName)) {
                $_SESSION['error'] = 'Tên đăng nhập, mật khẩu và họ tên không được để trống!';
                $this->view('user/create', [
                    'title' => 'Thêm người dùng',
                    'roles' => $this->roleModel->getAll(),
                    'old' => $_POST
                ]);
                return;
            }

            if ($password !== $confirmPassword) {
                $_SESSION['error'] = 'Mật khẩu xác nhận không khớp!';
                $this->view('user/create', [
                    'title' => 'Thêm người dùng',
                    'roles' => $this->roleModel->getAll(),
                    'old' => $_POST
                ]);
                return;
            }

            if (strlen($password) < 6) {
                $_SESSION['error'] = 'Mật khẩu phải có ít nhất 6 ký tự!';
                $this->view('user/create', [
                    'title' => 'Thêm người dùng',
                    'roles' => $this->roleModel->getAll(),
                    'old' => $_POST
                ]);
                return;
            }

            // Check duplicate username
            if ($this->userModel->findByUsername($username)) {
                $_SESSION['error'] = 'Tên đăng nhập đã tồn tại!';
                $this->view('user/create', [
                    'title' => 'Thêm người dùng',
                    'roles' => $this->roleModel->getAll(),
                    'old' => $_POST
                ]);
                return;
            }

            $data = [
                'username' => $username,
                'password' => $this->userModel->hashPassword($password),
                'full_name' => $fullName,
                'email' => $email,
                'phone' => $phone,
                'role_id' => $roleId,
                'warehouse_id' => $warehouseId,
                'status' => $status
            ];

            if ($this->userModel->create($data)) {
                $_SESSION['success'] = 'Thêm người dùng thành công!';
                $this->redirect('user');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
            }
        }

        $warehouseModel = $this->model('Warehouse');
        $this->view('user/create', [
            'title' => 'Thêm người dùng',
            'roles' => $this->roleModel->getAll(),
            'warehouses' => $warehouseModel->getAllActive()
        ]);
    }

    public function edit($id)
    {
        Auth::requirePermission('user.edit');

        $user = $this->userModel->find($id);
        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy người dùng!';
            $this->redirect('user');
            return;
        }

        $warehouseModel = $this->model('Warehouse');
        $warehouses = $warehouseModel->getAllActive();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $fullName = trim($_POST['full_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $roleId = $_POST['role_id'] ?? null;
            $warehouseId = $_POST['warehouse_id'] ?? null;
            $status = $_POST['status'] ?? 'active';
            $changePassword = isset($_POST['change_password']);
            $newPassword = trim($_POST['new_password'] ?? '');
            $confirmPassword = trim($_POST['confirm_password'] ?? '');

            // Validation
            if (empty($username) || empty($fullName)) {
                $_SESSION['error'] = 'Tên đăng nhập và họ tên không được để trống!';
                $this->view('user/edit', [
                    'title' => 'Sửa người dùng',
                    'user' => array_merge($user, $_POST),
                    'roles' => $this->roleModel->getAll(),
                    'warehouses' => $warehouses
                ]);
                return;
            }

            // Check duplicate username (exclude current)
            $existing = $this->userModel->findByUsername($username);
            if ($existing && $existing['id'] != $id) {
                $_SESSION['error'] = 'Tên đăng nhập đã tồn tại!';
                $this->view('user/edit', [
                    'title' => 'Sửa người dùng',
                    'user' => array_merge($user, $_POST),
                    'roles' => $this->roleModel->getAll(),
                    'warehouses' => $warehouses
                ]);
                return;
            }

            // Validate password if changing
            if ($changePassword) {
                if (empty($newPassword)) {
                    $_SESSION['error'] = 'Vui lòng nhập mật khẩu mới!';
                    $this->view('user/edit', [
                        'title' => 'Sửa người dùng',
                        'user' => array_merge($user, $_POST),
                        'roles' => $this->roleModel->getAll(),
                        'warehouses' => $warehouses
                    ]);
                    return;
                }

                if ($newPassword !== $confirmPassword) {
                    $_SESSION['error'] = 'Mật khẩu xác nhận không khớp!';
                    $this->view('user/edit', [
                        'title' => 'Sửa người dùng',
                        'user' => array_merge($user, $_POST),
                        'roles' => $this->roleModel->getAll(),
                        'warehouses' => $warehouses
                    ]);
                    return;
                }

                if (strlen($newPassword) < 6) {
                    $_SESSION['error'] = 'Mật khẩu phải có ít nhất 6 ký tự!';
                    $this->view('user/edit', [
                        'title' => 'Sửa người dùng',
                        'user' => array_merge($user, $_POST),
                        'roles' => $this->roleModel->getAll(),
                        'warehouses' => $warehouses
                    ]);
                    return;
                }
            }

            $data = [
                'username' => $username,
                'full_name' => $fullName,
                'email' => $email,
                'phone' => $phone,
                'role_id' => $roleId,
                'status' => $status
            ];

            // Only update warehouse_id if provided (admin can change, others keep existing)
            if (isset($_POST['warehouse_id'])) {
                $data['warehouse_id'] = $warehouseId ?: null;
            }

            if ($changePassword) {
                $data['password'] = $this->userModel->hashPassword($newPassword);
            }

            if ($this->userModel->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật người dùng thành công!';
                $this->redirect('user');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
                $this->view('user/edit', [
                    'title' => 'Sửa người dùng',
                    'user' => array_merge($user, $_POST),
                    'roles' => $this->roleModel->getAll(),
                    'warehouses' => $warehouses
                ]);
                return;
            }
        }

        $this->view('user/edit', [
            'title' => 'Sửa người dùng',
            'user' => $user,
            'roles' => $this->roleModel->getAll(),
            'warehouses' => $warehouses
        ]);
    }

    public function delete($id)
    {
        Auth::requirePermission('user.delete');

        // Cannot delete self
        if ($id == Auth::id()) {
            $_SESSION['error'] = 'Không thể xóa tài khoản của chính mình!';
            $this->redirect('user');
            return;
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            $_SESSION['error'] = 'Không tìm thấy người dùng!';
            $this->redirect('user');
            return;
        }

        if ($this->userModel->delete($id)) {
            $_SESSION['success'] = 'Xóa người dùng thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
        }

        $this->redirect('user');
    }
}
