<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách sản phẩm</h3>
                <div class="card-actions">
                    <?php if (Auth::hasPermission('product.create')): ?>
                        <a href="<?= BASE_URL ?>product/create" class="btn btn-primary">
                            <i class="ti ti-plus me-2"></i>Thêm sản phẩm
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-5">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Tìm kiếm theo mã hoặc tên..." 
                                   value="<?= htmlspecialchars($search ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <select name="warehouse_id" class="form-select">
                                <option value="">-- Tất cả kho --</option>
                                <?php foreach ($warehouses as $wh): ?>
                                    <option value="<?= $wh['id'] ?>" <?= ($warehouseId == $wh['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($wh['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-search"></i> Tìm kiếm
                            </button>
                            <?php if (!empty($search) || !empty($warehouseId)): ?>
                                <a href="<?= BASE_URL ?>product" class="btn btn-secondary">
                                    <i class="ti ti-x"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Ảnh</th>
                                <th>Mã SP</th>
                                <th>Tên sản phẩm</th>
                                <th>Loại mặt hàng</th>
                                <th>Kho</th>
                                <th>Đơn vị</th>
                                <th>Tồn kho</th>
                                <th>Trạng thái</th>
                                <th class="w-1">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted">
                                        <i class="ti ti-box-off fs-1 mb-2"></i>
                                        <p>Không có dữ liệu</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $index => $product): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <?php if ($product['image']): ?>
                                                <img src="<?= BASE_URL ?>public/uploads/products/<?= $product['image'] ?>" 
                                                     alt="<?= htmlspecialchars($product['name']) ?>" 
                                                     class="avatar avatar-sm">
                                            <?php else: ?>
                                                <span class="avatar avatar-sm">
                                                    <i class="ti ti-photo"></i>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td><strong><?= htmlspecialchars($product['code']) ?></strong></td>
                                        <td><?= htmlspecialchars($product['name']) ?></td>
                                        <td><?= htmlspecialchars($product['latest_product_type_name'] ?? '-') ?></td>
                                        <td>
                                            <?php if (!empty($product['warehouse_name'])): ?>
                                                <span class="badge bg-azure-lt"><?= htmlspecialchars($product['warehouse_name']) ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($product['unit']) ?></td>
                                        <td>
                                            <span class="text-muted">
                                                Min: <?= $product['min_stock'] ?> | Max: <?= $product['max_stock'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($product['status'] === 'active'): ?>
                                                <span class="badge bg-success">Hoạt động</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Không hoạt động</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <?php if (Auth::hasPermission('product.edit')): ?>
                                                    <a href="<?= BASE_URL ?>product/edit/<?= $product['id'] ?>" 
                                                       class="btn btn-sm btn-primary" 
                                                       title="Sửa">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (Auth::hasPermission('product.delete')): ?>
                                                    <a href="<?= BASE_URL ?>product/delete/<?= $product['id'] ?>" 
                                                       class="btn btn-sm btn-danger" 
                                                       title="Xóa"
                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
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
