<?php

class User extends Model
{
    protected $table = 'users';

    public function findByUsername($username)
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = ?";
        $result = $this->query($sql, [$username]);
        return $result ? $result[0] : null;
    }

    public function getAllWithRoles()
    {
        $sql = "SELECT u.*, r.display_name as role_display_name
                FROM {$this->table} u
                LEFT JOIN roles r ON u.role_id = r.id
                ORDER BY u.id DESC";
        return $this->query($sql);
    }

    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function updatePassword($userId, $newPassword)
    {
        $hash = $this->hashPassword($newPassword);
        return $this->update($userId, ['password' => $hash]);
    }
}
