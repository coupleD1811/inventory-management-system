<?php

class Workshop extends Model
{
    protected $table = 'workshops';

    public function getAllActive()
    {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY name";
        return $this->query($sql);
    }

    public function search($keyword)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE code LIKE ? OR name LIKE ? OR manager_name LIKE ?
                ORDER BY id DESC";
        $param = "%{$keyword}%";
        return $this->query($sql, [$param, $param, $param]);
    }

    public function generateCode()
    {
        $sql = "SELECT code FROM {$this->table} ORDER BY id DESC LIMIT 1";
        $result = $this->query($sql);
        
        if ($result && count($result) > 0) {
            $lastCode = $result[0]['code'];
            $number = intval(substr($lastCode, 2)) + 1;
        } else {
            $number = 1;
        }
        
        return 'PX' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}
