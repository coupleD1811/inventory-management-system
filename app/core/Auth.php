<?php

class Auth
{
    public static function check()
    {
        return isset($_SESSION['user_id']);
    }

    public static function user()
    {
        if (!self::check()) {
            return null;
        }

        if (!isset($_SESSION['user_data'])) {
            try {
                $db = new Database();
                $sql = "SELECT u.*, r.name as role_name, r.display_name as role_display_name
                        FROM users u
                        LEFT JOIN roles r ON u.role_id = r.id
                        WHERE u.id = ?";
                $result = $db->query($sql, [$_SESSION['user_id']]);
                $_SESSION['user_data'] = $result[0] ?? null;
            } catch (Exception $e) {
                error_log("Auth::user() exception: " . $e->getMessage());
                return null;
            }
        }

        return $_SESSION['user_data'];
    }

    public static function id()
    {
        return $_SESSION['user_id'] ?? null;
    }

    public static function hasRole($role)
    {
        $user = self::user();
        if (!$user) return false;

        if (is_array($role)) {
            return in_array($user['role_name'], $role);
        }

        return $user['role_name'] === $role;
    }

    public static function hasPermission($permission)
    {
        $user = self::user();
        if (!$user || !isset($user['role_id'])) {
            return false;
        }

        // Cache permissions in session
        if (!isset($_SESSION['permissions'])) {
            require_once '../app/models/Role.php';
            $roleModel = new Role();
            $permissions = $roleModel->getPermissions($user['role_id']);
            $_SESSION['permissions'] = array_column($permissions, 'name');
        }

        return in_array($permission, $_SESSION['permissions']);
    }

    public static function hasAnyPermission($permissions)
    {
        foreach ($permissions as $permission) {
            if (self::hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    public static function hasAllPermissions($permissions)
    {
        foreach ($permissions as $permission) {
            if (!self::hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    public static function isAdmin()
    {
        return self::hasRole('admin');
    }

    public static function isStorekeeper()
    {
        return self::hasRole([
            'storekeeper_fuel',
            'storekeeper_raw',
            'storekeeper_spare',
            'storekeeper_finished'
        ]);
    }

    public static function login($userId)
    {
        $_SESSION['user_id'] = $userId;

        // Update last login
        $db = new Database();
        $db->execute("UPDATE users SET last_login = NOW() WHERE id = ?", [$userId]);

        // Clear cached data
        unset($_SESSION['user_data']);
        unset($_SESSION['permissions']);
    }

    public static function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_data']);
        unset($_SESSION['permissions']);
        session_destroy();
    }

    public static function requireLogin()
    {
        if (!self::check()) {
            header('Location: ' . BASE_URL . 'auth/login');
            exit;
        }
    }

    public static function requirePermission($permission, $redirect = true)
    {
        self::requireLogin();

        if (!self::hasPermission($permission)) {
            if ($redirect) {
                $_SESSION['error'] = 'Bạn không có quyền truy cập chức năng này!';
                header('Location: ' . BASE_URL);
                exit;
            }
            return false;
        }
        return true;
    }

    public static function requireRole($role, $redirect = true)
    {
        self::requireLogin();

        if (!self::hasRole($role)) {
            if ($redirect) {
                $_SESSION['error'] = 'Bạn không có quyền truy cập trang này!';
                header('Location: ' . BASE_URL);
                exit;
            }
            return false;
        }
        return true;
    }

    /**
     * Get assigned warehouse ID for current user
     * Returns null if user is admin or has no warehouse assigned
     */
    public static function getWarehouseId()
    {
        $user = self::user();
        if (!$user) return null;

        // Admin sees all warehouses
        if (self::isAdmin()) {
            return null;
        }

        return $user['warehouse_id'] ?? null;
    }

    /**
     * Check if user can access a specific warehouse
     */
    public static function canAccessWarehouse($warehouseId)
    {
        $user = self::user();
        if (!$user) return false;

        // Admin can access all warehouses
        if (self::isAdmin()) {
            return true;
        }

        if (self::hasRole('manager')) {
            return true;
        }

        // If user has no warehouse assigned, deny access
        if (empty($user['warehouse_id'])) {
            return false;
        }

        // Check if warehouse matches
        return $user['warehouse_id'] == $warehouseId;
    }

    /**
     * Get SQL WHERE clause for warehouse filtering
     * Returns empty string for admin, or "warehouse_id = X" for others
     */
    public static function getWarehouseFilter($tableAlias = '')
    {
        $warehouseId = self::getWarehouseId();
        
        if ($warehouseId === null) {
            return ''; // Admin sees all
        }

        $column = $tableAlias ? "{$tableAlias}.warehouse_id" : 'warehouse_id';
        return "{$column} = {$warehouseId}";
    }
}
