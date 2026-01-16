<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Báo cáo tồn kho</h3>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <select name="warehouse_id" class="form-select">
                                <option value="">-- Tất cả kho --</option>
                                <?php foreach ($warehouses as $wh): ?>
                                    <option value="<?= $wh['id'] ?>" <?= ($warehouseId == $wh['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($wh['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Tìm theo mã hoặc tên sản phẩm..." 
                                   value="<?= htmlspecialchars($search ?? '') ?>">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-search"></i> Lọc
                            </button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Kho</th>
                                <th>Mã SP</th>
                                <th>Tên sản phẩm</th>
                                <th>Đơn vị</th>
                                <th>Tồn kho</th>
                                <th>Min/Max</th>
                                <th>Trạng thái</th>
                                <th>Nhập cuối</th>
                                <th>Xuất cuối</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($inventory)): ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted">
                                        <i class="ti ti-clipboard-off fs-1 mb-2"></i>
                                        <p>Không có dữ liệu</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($inventory as $index => $item): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><strong><?= htmlspecialchars($item['warehouse_name']) ?></strong></td>
                                        <td><?= htmlspecialchars($item['product_code']) ?></td>
                                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                                        <td><?= htmlspecialchars($item['unit']) ?></td>
                                        <td><strong><?= number_format($item['quantity']) ?></strong></td>
                                        <td>
                                            <small class="text-muted">
                                                <?= number_format($item['min_stock'] ?? 0) ?> / 
                                                <?= number_format($item['max_stock'] ?? 0) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <?php
                                            $qty = $item['quantity'];
                                            $min = $item['min_stock'] ?? 0;
                                            $max = $item['max_stock'] ?? 0;
                                            
                                            if ($qty < $min && $min > 0):
                                            ?>
                                                <span class="badge bg-danger">Dưới mức tối thiểu</span>
                                            <?php elseif ($qty > $max && $max > 0): ?>
                                                <span class="badge bg-warning">Vượt mức tối đa</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Bình thường</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($item['last_import_date']): ?>
                                                <small><?= date('d/m/Y', strtotime($item['last_import_date'])) ?></small>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($item['last_export_date']): ?>
                                                <small><?= date('d/m/Y', strtotime($item['last_export_date'])) ?></small>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
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
