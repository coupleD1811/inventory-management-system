<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách phiếu nhập</h3>
                <div class="card-actions">
                    <?php if (Auth::hasPermission('import.create')): ?>
                        <a href="<?= BASE_URL ?>import/create" class="btn btn-primary">
                            <i class="ti ti-plus me-2"></i>Tạo phiếu nhập
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo mã phiếu hoặc nhà cung cấp..." value="<?= htmlspecialchars($search ?? '') ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-search"></i> Tìm kiếm
                        </button>
                        <?php if (!empty($search)): ?>
                            <a href="<?= BASE_URL ?>import" class="btn btn-secondary">
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
                                <th>Nhà cung cấp</th>
                                <th>Ngày nhập</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Người tạo</th>
                                <th class="w-1">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($imports)): ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted">
                                        <i class="ti ti-file-import-off fs-1 mb-2"></i>
                                        <p>Không có dữ liệu</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($imports as $index => $import): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><strong><?= htmlspecialchars($import['code']) ?></strong></td>
                                        <td><?= htmlspecialchars($import['warehouse_name'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($import['supplier_name'] ?? '-') ?></td>
                                        <td><?= date('d/m/Y', strtotime($import['import_date'])) ?></td>
                                        <td><?= number_format($import['total_amount']) ?> đ</td>
                                        <td>
                                            <?php if ($import['status'] === 'pending'): ?>
                                                <span class="badge bg-warning">Chờ duyệt</span>
                                            <?php elseif ($import['status'] === 'approved'): ?>
                                                <span class="badge bg-success">Đã duyệt</span>
                                            <?php elseif ($import['status'] === 'cancelled'): ?>
                                                <span class="badge bg-danger">Đã từ chối</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Nháp</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($import['created_by_name'] ?? '-') ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= BASE_URL ?>import/detail/<?= $import['id'] ?>" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Xem">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <?php if (Auth::hasPermission('import.delete') && $import['status'] !== 'approved'): ?>
                                                    <a href="<?= BASE_URL ?>import/delete/<?= $import['id'] ?>" 
                                                       class="btn btn-sm btn-danger" 
                                                       title="Xóa"
                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa phiếu nhập này?')">
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
