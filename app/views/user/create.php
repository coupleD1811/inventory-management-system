<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thêm người dùng mới</h3>
                <div class="card-actions">
                    <a href="<?= BASE_URL ?>user" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>user/create">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Tên đăng nhập</label>
                                <input type="text" name="username" class="form-control" 
                                       placeholder="VD: nguyenvana" 
                                       value="<?= htmlspecialchars($old['username'] ?? '') ?>" 
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Họ tên</label>
                                <input type="text" name="full_name" class="form-control" 
                                       placeholder="VD: Nguyễn Văn A" 
                                       value="<?= htmlspecialchars($old['full_name'] ?? '') ?>" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Mật khẩu</label>
                                <input type="password" name="password" class="form-control" 
                                       placeholder="Tối thiểu 6 ký tự" 
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Xác nhận mật khẩu</label>
                                <input type="password" name="confirm_password" class="form-control" 
                                       placeholder="Nhập lại mật khẩu" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" 
                                       placeholder="VD: user@example.com" 
                                       value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Điện thoại</label>
                                <input type="text" name="phone" class="form-control" 
                                       placeholder="VD: 0901234567" 
                                       value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Vai trò</label>
                                <select name="role_id" class="form-select">
                                    <option value="">-- Chọn vai trò --</option>
                                    <?php foreach ($roles as $role): ?>
                                        <option value="<?= $role['id'] ?>" 
                                                <?= ($old['role_id'] ?? '') == $role['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($role['display_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php if (Auth::isAdmin()): ?>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kho quản lý</label>
                                <select name="warehouse_id" class="form-select">
                                    <option value="">-- Không gán kho (Admin) --</option>
                                    <?php foreach ($warehouses as $warehouse): ?>
                                        <option value="<?= $warehouse['id'] ?>" 
                                                <?= ($old['warehouse_id'] ?? '') == $warehouse['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($warehouse['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-hint">Nếu gán kho, người dùng chỉ thấy dữ liệu của kho này</small>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="active" <?= ($old['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Hoạt động</option>
                                    <option value="inactive" <?= ($old['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Không hoạt động</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i>Lưu
                        </button>
                        <a href="<?= BASE_URL ?>user" class="btn btn-secondary">
                            <i class="ti ti-x me-2"></i>Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
