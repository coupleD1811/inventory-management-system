<?php

class Role extends Model
{
    protected $table = 'roles';

    public function getPermissions($roleId)
    {
        $sql = "SELECT p.*
                FROM permissions p
                INNER JOIN role_permissions rp ON p.id = rp.permission_id
                WHERE rp.role_id = ?";
        return $this->query($sql, [$roleId]);
    }

    public function syncPermissions($roleId, $permissionIds)
    {
        try {
            $this->db->beginTransaction();

            // Delete existing permissions
            $this->execute("DELETE FROM role_permissions WHERE role_id = ?", [$roleId]);

            // Insert new permissions
            if (!empty($permissionIds)) {
                foreach ($permissionIds as $permissionId) {
                    $this->execute(
                        "INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)",
                        [$roleId, $permissionId]
                    );
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("syncPermissions error: " . $e->getMessage());
            return false;
        }
    }

    public function getAllWithStats()
    {
        $sql = "SELECT r.*, 
                COUNT(DISTINCT rp.permission_id) as permission_count,
                COUNT(DISTINCT u.id) as user_count
                FROM {$this->table} r
                LEFT JOIN role_permissions rp ON r.id = rp.role_id
                LEFT JOIN users u ON r.id = u.role_id
                GROUP BY r.id
                ORDER BY r.id";
        return $this->query($sql);
    }

    public function canDelete($roleId)
    {
        // Check if role has users
        $sql = "SELECT COUNT(*) as count FROM users WHERE role_id = ?";
        $result = $this->query($sql, [$roleId]);
        
        if ($result && $result[0]['count'] > 0) {
            return false;
        }

        // Check if it's a system role (admin)
        $role = $this->find($roleId);
        if ($role && $role['name'] === 'admin') {
            return false;
        }

        return true;
    }
}
