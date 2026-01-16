<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách kiểm kê kho</h3>
                <div class="card-actions">
                    <?php if (Auth::hasPermission('stock_take.create')): ?>
                        <a href="<?= BASE_URL ?>stock_take/create" class="btn btn-primary">
                            <i class="ti ti-plus me-2"></i>Tạo phiếu kiểm kê
                        </a>
                    <?php endif; ?>
                </div>
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
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã phiếu</th>
                                <th>Kho</th>
                                <th>Ngày kiểm kê</th>
                                <th>Người tạo</th>
                                <th>Trạng thái</th>
                                <th class="w-1">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($stockTakes)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Không có dữ liệu</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($stockTakes as $index => $st): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><strong><?= htmlspecialchars($st['code']) ?></strong></td>
                                        <td><?= htmlspecialchars($st['warehouse_name']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($st['stock_take_date'])) ?></td>
                                        <td><?= htmlspecialchars($st['created_by_name'] ?? '-') ?></td>
                                        <td>
                                            <?php
                                            $statusBadges = [
                                                'draft' => '<span class="badge bg-secondary">Nháp</span>',
                                                'in_progress' => '<span class="badge bg-info">Đang kiểm kê</span>',
                                                'completed' => '<span class="badge bg-success">Hoàn thành</span>',
                                                'cancelled' => '<span class="badge bg-danger">Đã hủy</span>'
                                            ];
                                            echo $statusBadges[$st['status']] ?? '';
                                            ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= BASE_URL ?>stock_take/detail/<?= $st['id'] ?>" class="btn btn-sm btn-info" title="Xem">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <?php if (Auth::hasPermission('stock_take.edit') && $st['status'] !== 'completed'): ?>
                                                    <a href="<?= BASE_URL ?>stock_take/edit/<?= $st['id'] ?>" class="btn btn-sm btn-primary" title="Sửa">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (Auth::hasPermission('stock_take.approve') && $st['status'] !== 'completed'): ?>
                                                    <a href="<?= BASE_URL ?>stock_take/approve/<?= $st['id'] ?>" 
                                                       class="btn btn-sm btn-success" 
                                                       title="Duyệt"
                                                       onclick="return confirm('Bạn có chắc chắn muốn duyệt phiếu kiểm kê này? Tồn kho sẽ được điều chỉnh theo số lượng thực tế.')">
                                                        <i class="ti ti-check"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (Auth::hasPermission('stock_take.delete') && $st['status'] !== 'completed'): ?>
                                                    <a href="<?= BASE_URL ?>stock_take/delete/<?= $st['id'] ?>" 
                                                       class="btn btn-sm btn-danger" 
                                                       title="Xóa"
                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa phiếu kiểm kê này?')">
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
