<?php

class RoleController extends Controller
{
    private $roleModel;

    public function __construct()
    {
        Auth::requireLogin();
        $this->roleModel = $this->model('Role');
    }

    /**
     * Danh sách roles
     */
    public function index()
    {
        Auth::requirePermission('role.view');

        $allRoles = $this->roleModel->getAllWithStats();
        
        // Filter out admin role from list
        $roles = array_filter($allRoles, function($role) {
            return $role['name'] !== 'admin';
        });

        $data = [
            'title' => 'Quản lý vai trò',
            'roles' => $roles,
            'canCreate' => Auth::hasPermission('role.create'),
            'canEdit' => Auth::hasPermission('role.edit'),
            'canDelete' => Auth::hasPermission('role.delete')
        ];

        $this->view('role/index', $data);
    }

    /**
     * Thêm role mới
     */
    public function add()
    {
        Auth::requirePermission('role.create');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'display_name' => $_POST['display_name'],
                'description' => $_POST['description'] ?? null,
                'created_at' => date('Y-m-d H:i:s')
            ];

            if ($this->roleModel->create($data)) {
                $_SESSION['success'] = 'Thêm vai trò thành công!';
                $this->redirect('role');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra!';
            }
        }

        $this->view('role/add', ['title' => 'Thêm vai trò']);
    }

    /**
     * Sửa role
     */
    public function edit($id)
    {
        Auth::requirePermission('role.edit');

        $role = $this->roleModel->find($id);
        if (!$role) {
            $_SESSION['error'] = 'Không tìm thấy vai trò!';
            $this->redirect('role');
            return;
        }

        // Cannot edit admin role
        if ($role['name'] === 'admin') {
            $_SESSION['error'] = 'Không thể chỉnh sửa vai trò hệ thống!';
            $this->redirect('role');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'display_name' => $_POST['display_name'],
                'description' => $_POST['description'] ?? null,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if ($this->roleModel->update($id, $data)) {
                $_SESSION['success'] = 'Cập nhật vai trò thành công!';
                $this->redirect('role');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra!';
            }
        }

        $this->view('role/edit', [
            'title' => 'Sửa vai trò',
            'role' => $role
        ]);
    }

    /**
     * Xóa role
     */
    public function delete($id)
    {
        Auth::requirePermission('role.delete');

        if (!$this->roleModel->canDelete($id)) {
            $_SESSION['error'] = 'Không thể xóa vai trò này! (Vai trò hệ thống hoặc đang được sử dụng)';
            $this->redirect('role');
        }

        if ($this->roleModel->delete($id)) {
            $_SESSION['success'] = 'Xóa vai trò thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra!';
        }

        $this->redirect('role');
    }

    /**
     * Quản lý permissions cho role
     */
    public function permissions($id)
    {
        Auth::requirePermission('role.edit');

        $role = $this->roleModel->find($id);
        if (!$role) {
            $_SESSION['error'] = 'Không tìm thấy vai trò!';
            $this->redirect('role');
            return;
        }

        // Cannot edit admin role permissions
        if ($role['name'] === 'admin') {
            $_SESSION['error'] = 'Không thể thay đổi quyền của vai trò hệ thống!';
            $this->redirect('role');
            return;
        }

        $permissionModel = $this->model('Permission');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $permissionIds = $_POST['permissions'] ?? [];
            
            if ($this->roleModel->syncPermissions($id, $permissionIds)) {
                $_SESSION['success'] = 'Cập nhật quyền hạn thành công!';
                $this->redirect('role');
            } else {
                $_SESSION['error'] = 'Có lỗi xảy ra!';
            }
        }

        $permissions = $permissionModel->getGroupedByModule();
        $rolePermissions = $this->roleModel->getPermissions($id);
        $rolePermissionIds = array_column($rolePermissions, 'id');

        $this->view('role/permissions', [
            'title' => 'Phân quyền - ' . $role['display_name'],
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissionIds' => $rolePermissionIds
        ]);
    }
}
