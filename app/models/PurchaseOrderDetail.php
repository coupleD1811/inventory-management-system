<?php

class PurchaseOrderDetail extends Model
{
    protected $table = 'purchase_order_details';

    public function getByPurchaseOrder($purchaseOrderId)
    {
        $sql = "SELECT pod.*, p.name as product_name, p.code as product_code, p.unit
                FROM {$this->table} pod
                LEFT JOIN products p ON pod.product_id = p.id
                WHERE pod.purchase_order_id = ?
                ORDER BY pod.id";
        return $this->query($sql, [$purchaseOrderId]);
    }

    public function deleteByPurchaseOrder($purchaseOrderId)
    {
        $sql = "DELETE FROM {$this->table} WHERE purchase_order_id = ?";
        return $this->query($sql, [$purchaseOrderId]);
    }

    public function updateReceivedQuantity($id, $quantity)
    {
        $sql = "UPDATE {$this->table} SET received_quantity = ? WHERE id = ?";
        return $this->query($sql, [$quantity, $id]);
    }
}
