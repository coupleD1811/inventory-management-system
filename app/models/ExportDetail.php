<?php

class ExportDetail extends Model
{
    protected $table = 'export_details';

    public function getByExport($exportId)
    {
        $sql = "SELECT ed.*, p.code as product_code, p.name as product_name, p.unit
                FROM {$this->table} ed
                INNER JOIN products p ON ed.product_id = p.id
                WHERE ed.export_id = ?
                ORDER BY ed.id";
        return $this->query($sql, [$exportId]);
    }

    public function deleteByExport($exportId)
    {
        $sql = "DELETE FROM {$this->table} WHERE export_id = ?";
        return $this->execute($sql, [$exportId]);
    }

    public function getTotalAmount($exportId)
    {
        $sql = "SELECT SUM(total_price) as total FROM {$this->table} WHERE export_id = ?";
        $result = $this->query($sql, [$exportId]);
        return $result ? ($result[0]['total'] ?? 0) : 0;
    }
}
