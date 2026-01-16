<?php

class Supplier extends Model
{
    protected $table = 'suppliers';

    public function getAllActive()
    {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY name";
        return $this->query($sql);
    }

    public function search($keyword)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE code LIKE ? OR name LIKE ? OR contact_person LIKE ?
                ORDER BY name";
        $param = "%{$keyword}%";
        return $this->query($sql, [$param, $param, $param]);
    }

    public function findByCode($code)
    {
        $sql = "SELECT * FROM {$this->table} WHERE code = ?";
        $result = $this->query($sql, [$code]);
        return $result ? $result[0] : null;
    }

    public function hasImports($supplierId)
    {
        $sql = "SELECT COUNT(*) as count FROM imports WHERE supplier_id = ?";
        $result = $this->query($sql, [$supplierId]);
        return $result && $result[0]['count'] > 0;
    }
}
