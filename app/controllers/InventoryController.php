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
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = 15;
        $offset = ($page - 1) * $perPage;
        
        if ($warehouseId || $search) {
            $inventory = $this->inventoryModel->searchPaged($warehouseId, $search, $perPage, $offset);
            $total = $this->inventoryModel->countSearch($warehouseId, $search);
        } else {
            $inventory = $this->inventoryModel->getAllPaged($perPage, $offset);
            $total = $this->inventoryModel->countAll();
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
            'isWarehouseManager' => $userWarehouseId !== null,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total
        ];

        $this->view('inventory/index', $data);
    }
}
