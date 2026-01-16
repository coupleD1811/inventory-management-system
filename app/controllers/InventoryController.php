<?php

class InventoryController extends Controller
{
    private $inventoryModel;
    private $warehouseModel;
    private $productModel;

    public function __construct()
    {
        Auth::requireLogin();
        $this->inventoryModel = $this->model('Inventory');
        $this->warehouseModel = $this->model('Warehouse');
        $this->productModel = $this->model('Product');
    }

    public function index()
    {
        Auth::requirePermission('inventory.view');

        // Get user's assigned warehouse (null for admin)
        $userWarehouseId = Auth::getWarehouseId();
        
        // If user has assigned warehouse, force filter to that warehouse
        $warehouseId = $userWarehouseId ?? ($_GET['warehouse_id'] ?? '');
        $search = $_GET['search'] ?? '';
        
        if ($warehouseId || $search) {
            $inventory = $this->inventoryModel->search($warehouseId, $search);
        } else {
            $inventory = $this->inventoryModel->getAllWithDetails();
            
            // Filter by warehouse if user is not admin
            if ($userWarehouseId) {
                $inventory = array_filter($inventory, function($item) use ($userWarehouseId) {
                    return $item['warehouse_id'] == $userWarehouseId;
                });
            }
        }

        // Get warehouses - admin sees all, others see only their warehouse
        if ($userWarehouseId) {
            $warehouses = [$this->warehouseModel->find($userWarehouseId)];
        } else {
            $warehouses = $this->warehouseModel->getAllActive();
        }
        
        $data = [
            'title' => 'Tồn kho',
            'inventory' => $inventory,
            'warehouses' => $warehouses,
            'warehouseId' => $warehouseId,
            'search' => $search,
            'isWarehouseManager' => $userWarehouseId !== null
        ];

        $this->view('inventory/index', $data);
    }
}
