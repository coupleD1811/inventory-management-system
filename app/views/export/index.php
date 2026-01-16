<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách phiếu xuất</h3>
                <div class="card-actions">
                    <?php if (Auth::hasPermission('export.create')): ?>
                        <a href="<?= BASE_URL ?>export/create" class="btn btn-primary">
                            <i class="ti ti-plus me-2"></i>Tạo phiếu xuất
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo mã phiếu hoặc khách hàng..." value="<?= htmlspecialchars($search ?? '') ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-search"></i> Tìm kiếm
                        </button>
                        <?php if (!empty($search)): ?>
                            <a href="<?= BASE_URL ?>export" class="btn btn-secondary">
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
                                <th>Mã phiếu</th>
                                <th>Kho</th>
                                <th>Khách hàng</th>
                                <th>Loại xuất</th>
                                <th>Ngày xuất</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th class="w-1">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($exports)): ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted">
                                        <i class="ti ti-file-export-off fs-1 mb-2"></i>
                                        <p>Không có dữ liệu</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($exports as $index => $export): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><strong><?= htmlspecialchars($export['code']) ?></strong></td>
                                        <td><?= htmlspecialchars($export['warehouse_name'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($export['customer_name'] ?? '-') ?></td>
                                        <td>
                                            <?php
                                            $types = [
                                                'sale' => 'Bán hàng',
                                                'internal' => 'Nội bộ',
                                                'damaged' => 'Hư hỏng',
                                                'other' => 'Khác'
                                            ];
                                            echo $types[$export['export_type']] ?? $export['export_type'];
                                            ?>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($export['export_date'])) ?></td>
                                        <td><?= number_format($export['total_amount']) ?> đ</td>
                                        <td>
                                            <?php if ($export['status'] === 'pending'): ?>
                                                <span class="badge bg-warning">Chờ duyệt</span>
                                            <?php elseif ($export['status'] === 'approved'): ?>
                                                <span class="badge bg-success">Đã duyệt</span>
                                            <?php elseif ($export['status'] === 'cancelled'): ?>
                                                <span class="badge bg-danger">Đã từ chối</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Nháp</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= BASE_URL ?>export/detail/<?= $export['id'] ?>" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Xem">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <?php if (Auth::hasPermission('export.delete') && $export['status'] !== 'approved'): ?>
                                                    <a href="<?= BASE_URL ?>export/delete/<?= $export['id'] ?>" 
                                                       class="btn btn-sm btn-danger" 
                                                       title="Xóa"
                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa phiếu xuất này?')">
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
