<?php

class Permission extends Model
{
    protected $table = 'permissions';

    public function getGroupedByModule()
    {
        $permissions = $this->getAll();
        $grouped = [];

        foreach ($permissions as $permission) {
            $module = $permission['module'] ?? 'other';
            if (!isset($grouped[$module])) {
                $grouped[$module] = [];
            }
            $grouped[$module][] = $permission;
        }

        return $grouped;
    }

    public function getAllByModule($module)
    {
        $sql = "SELECT * FROM {$this->table} WHERE module = ? ORDER BY id";
        return $this->query($sql, [$module]);
    }
}
