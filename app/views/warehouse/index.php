<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách kho hàng</h3>
                <div class="card-actions">
                    <?php if (Auth::hasPermission('warehouse.create')): ?>
                        <a href="<?= BASE_URL ?>warehouse/create" class="btn btn-primary">
                            <i class="ti ti-plus me-2"></i>Thêm kho hàng
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo mã, tên hoặc người quản lý..." value="<?= htmlspecialchars($search ?? '') ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-search"></i> Tìm kiếm
                        </button>
                        <?php if (!empty($search)): ?>
                            <a href="<?= BASE_URL ?>warehouse" class="btn btn-secondary">
                                <i class="ti ti-x"></i> Xóa
                            </a>
                        <?php endif; ?>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã kho</th>
                                <th>Tên kho</th>
                                <th>Địa chỉ</th>
                                <th>Người quản lý</th>
                                <th>Điện thoại</th>
                                <th>Sức chứa (m²)</th>
                                <th>Trạng thái</th>
                                <th class="w-1">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($warehouses)): ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted">
                                        <i class="ti ti-building-warehouse-off fs-1 mb-2"></i>
                                        <p>Không có dữ liệu</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($warehouses as $index => $warehouse): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><strong><?= htmlspecialchars($warehouse['code']) ?></strong></td>
                                        <td><?= htmlspecialchars($warehouse['name']) ?></td>
                                        <td><?= htmlspecialchars($warehouse['address'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($warehouse['manager_full_name'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($warehouse['phone'] ?? '-') ?></td>
                                        <td><?= number_format($warehouse['capacity'], 2) ?></td>
                                        <td>
                                            <?php if ($warehouse['status'] === 'active'): ?>
                                                <span class="badge bg-success">Hoạt động</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Không hoạt động</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <?php if (Auth::hasPermission('warehouse.edit')): ?>
                                                    <a href="<?= BASE_URL ?>warehouse/edit/<?= $warehouse['id'] ?>" 
                                                       class="btn btn-sm btn-primary" 
                                                       title="Sửa">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (Auth::hasPermission('warehouse.delete')): ?>
                                                    <a href="<?= BASE_URL ?>warehouse/delete/<?= $warehouse['id'] ?>" 
                                                       class="btn btn-sm btn-danger" 
                                                       title="Xóa"
                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa kho này?')">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
