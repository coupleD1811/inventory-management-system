<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quản lý vai trò</h3>
                <div class="card-actions">
                    <?php if ($canCreate): ?>
                        <a href="<?= BASE_URL ?>role/add" class="btn btn-primary">
                            <i class="ti ti-plus me-2"></i>Thêm vai trò
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên vai trò</th>
                                <th>Tên hiển thị</th>
                                <th>Mô tả</th>
                                <th>Số người dùng</th>
                                <th>Số quyền</th>
                                <th class="w-1">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($roles)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        <i class="ti ti-shield-off fs-1 mb-2"></i>
                                        <p>Không có dữ liệu</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($roles as $index => $role): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><strong><?= htmlspecialchars($role['name']) ?></strong></td>
                                        <td><?= htmlspecialchars($role['display_name']) ?></td>
                                        <td><?= htmlspecialchars($role['description'] ?? '-') ?></td>
                                        <td>
                                            <span class="badge bg-azure-lt">
                                                <?= $role['user_count'] ?? 0 ?> người
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-green-lt">
                                                <?= $role['permission_count'] ?? 0 ?> quyền
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <?php if ($canEdit): ?>
                                                    <a href="<?= BASE_URL ?>role/permissions/<?= $role['id'] ?>" 
                                                       class="btn btn-sm btn-info" 
                                                       title="Phân quyền">
                                                        <i class="ti ti-lock"></i>
                                                    </a>
                                                    <a href="<?= BASE_URL ?>role/edit/<?= $role['id'] ?>" 
                                                       class="btn btn-sm btn-primary" 
                                                       title="Sửa">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if ($canDelete): ?>
                                                    <a href="<?= BASE_URL ?>role/delete/<?= $role['id'] ?>" 
                                                       class="btn btn-sm btn-danger" 
                                                       title="Xóa"
                                                       onclick="return confirm('Bạn có chắc chắn muốn xóa vai trò này?')">
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
