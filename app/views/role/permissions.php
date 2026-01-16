<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Phân quyền - <?= htmlspecialchars($role['display_name']) ?></h3>
                <div class="card-actions">
                    <a href="<?= BASE_URL ?>role" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>role/permissions/<?= $role['id'] ?>">
                    <?php if (!empty($permissions)): ?>
                        <?php foreach ($permissions as $module => $perms): ?>
                            <div class="mb-4">
                                <h4 class="mb-3">
                                    <i class="ti ti-folder me-2"></i>
                                    <?php
                                    $moduleNames = [
                                        'product' => 'Sản phẩm',
                                        'supplier' => 'Nhà cung cấp',
                                        'warehouse' => 'Kho hàng',
                                        'import' => 'Nhập kho',
                                        'export' => 'Xuất kho',
                                        'inventory' => 'Tồn kho',
                                        'report' => 'Báo cáo',
                                        'user' => 'Người dùng',
                                        'role' => 'Vai trò',
                                        'workshop' => 'Phân xưởng'
                                    ];
                                    echo $moduleNames[$module] ?? ucfirst($module);
                                    ?>
                                </h4>
                                <div class="row">
                                    <?php foreach ($perms as $perm): ?>
                                        <div class="col-md-3 mb-2">
                                            <label class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="permissions[]" 
                                                       value="<?= $perm['id'] ?>"
                                                       <?= in_array($perm['id'], $rolePermissionIds) ? 'checked' : '' ?>>
                                                <span class="form-check-label">
                                                    <?= htmlspecialchars($perm['display_name']) ?>
                                                </span>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <hr>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-2"></i>
                            Chưa có quyền nào trong hệ thống
                        </div>
                    <?php endif; ?>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i>Lưu phân quyền
                        </button>
                        <a href="<?= BASE_URL ?>role" class="btn btn-secondary">
                            <i class="ti ti-x me-2"></i>Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
