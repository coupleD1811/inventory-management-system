<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards mb-3">
    <div class="col-md-2">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Tổng số mặt hàng</div>
                </div>
                <div class="h1 mb-0"><?= number_format($stats['totalProducts']) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Tổng số lượng</div>
                </div>
                <div class="h1 mb-0"><?= number_format($stats['totalQuantity']) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Dưới mức tối thiểu</div>
                </div>
                <div class="h1 mb-0 text-danger"><?= number_format($stats['lowStock']) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Vượt mức tối đa</div>
                </div>
                <div class="h1 mb-0 text-warning"><?= number_format($stats['overStock']) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Sắp chạm ngưỡng</div>
                </div>
                <div class="h1 mb-0 text-warning"><?= number_format($stats['nearStock'] ?? 0) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Báo cáo tồn kho chi tiết</h3>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-3">
                    <div class="row g-2">
                        <div class="col-md-10">
                            <select name="warehouse_id" class="form-select">
                                <option value="">-- Tất cả kho --</option>
                                <?php foreach ($warehouses as $wh): ?>
                                    <option value="<?= $wh['id'] ?>" <?= ($warehouseId == $wh['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($wh['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-filter"></i> Lọc
                            </button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>Kho</th>
                                <th>Sản phẩm</th>
                                <th>Đơn vị</th>
                                <th>Tồn kho</th>
                                <th class="text-end">Min/Max</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($inventory)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Không có dữ liệu</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($inventory as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['warehouse_name']) ?></td>
                                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                                        <td><?= htmlspecialchars($item['unit']) ?></td>
                                        <td><strong><?= number_format($item['quantity']) ?></strong></td>
                                        <td class="text-end text-muted"><?= number_format($item['min_stock'] ?? 0) ?> / <?= number_format($item['max_stock'] ?? 0) ?></td>
                                        <td>
                                            <?php
                                            $qty = $item['quantity'];
                                            $min = $item['min_stock'] ?? 0;
                                            $max = $item['max_stock'] ?? 0;
                                            
                                            if ($qty < $min && $min > 0):
                                            ?>
                                                <span class="badge bg-danger">Dưới mức</span>
                                            <?php elseif ($qty > $max && $max > 0): ?>
                                                <span class="badge bg-warning">Vượt mức</span>
                                            <?php elseif (($min > 0 && $qty <= $min * 1.1) || ($max > 0 && $qty >= $max * 0.9)): ?>
                                                <span class="badge bg-warning-lt text-warning">Sắp chạm ngưỡng</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Bình thường</span>
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
