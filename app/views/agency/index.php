<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quản lý đại lý</h3>
                <div class="card-actions">
                    <?php if (Auth::hasPermission('agency.create')): ?>
                        <a href="<?= BASE_URL ?>agency/create" class="btn btn-primary">
                            <i class="ti ti-plus me-2"></i>Thêm đại lý
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo mã, tên, người đại diện..." value="<?= htmlspecialchars($search ?? '') ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-search"></i> Tìm kiếm
                        </button>
                        <?php if (!empty($search)): ?>
                            <a href="<?= BASE_URL ?>agency" class="btn btn-secondary">
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
                                <th>Mã đại lý</th>
                                <th>Tên đại lý</th>
                                <th>Người đại diện</th>
                                <th>Điện thoại</th>
                                <th>Hợp đồng</th>
                                <th>Chiết khấu</th>
                                <th>Trạng thái</th>
                                <th class="w-1">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($agencies)): ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted">
                                        <i class="ti ti-users-off fs-1 mb-2"></i>
                                        <p>Không có dữ liệu</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($agencies as $index => $agency): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><strong><?= htmlspecialchars($agency['code']) ?></strong></td>
                                        <td><?= htmlspecialchars($agency['name']) ?></td>
                                        <td><?= htmlspecialchars($agency['representative'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($agency['phone'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($agency['contract_number'] ?? '-') ?></td>
                                        <td><span class="badge bg-info"><?= number_format($agency['discount_percent'], 2) ?>%</span></td>
                                        <td>
                                            <?php if ($agency['status'] === 'active'): ?>
                                                <span class="badge bg-success">Hoạt động</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Ngừng</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= BASE_URL ?>agency/detail/<?= $agency['id'] ?>" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Xem">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <?php if (Auth::hasPermission('agency.edit')): ?>
                                                    <a href="<?= BASE_URL ?>agency/edit/<?= $agency['id'] ?>" 
                                                       class="btn btn-sm btn-primary" 
                                                       title="Sửa">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if (Auth::hasPermission('agency.delete')): ?>
                                                    <a href="<?= BASE_URL ?>agency/delete/<?= $agency['id'] ?>" 
                                                       class="btn btn-sm btn-danger" 
                                                       title="Xóa"
                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa đại lý này?')">
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
