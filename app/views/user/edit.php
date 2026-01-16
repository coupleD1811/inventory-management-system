<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sửa người dùng</h3>
                <div class="card-actions">
                    <a href="<?= BASE_URL ?>user" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>user/edit/<?= $user['id'] ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Tên đăng nhập</label>
                                <input type="text" name="username" class="form-control" 
                                       value="<?= htmlspecialchars($user['username']) ?>" 
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Họ tên</label>
                                <input type="text" name="full_name" class="form-control" 
                                       value="<?= htmlspecialchars($user['full_name']) ?>" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="change_password" id="change_password" 
                                   onchange="document.getElementById('password-fields').style.display = this.checked ? 'block' : 'none'">
                            <label class="form-check-label" for="change_password">
                                Đổi mật khẩu
                            </label>
                        </div>
                    </div>

                    <div id="password-fields" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu mới</label>
                                    <input type="password" name="new_password" class="form-control" 
                                           placeholder="Tối thiểu 6 ký tự">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Xác nhận mật khẩu</label>
                                    <input type="password" name="confirm_password" class="form-control" 
                                           placeholder="Nhập lại mật khẩu">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" 
                                       value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Điện thoại</label>
                                <input type="text" name="phone" class="form-control" 
                                       value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
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
                                                <?= $user['role_id'] == $role['id'] ? 'selected' : '' ?>>
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
                                                <?= ($user['warehouse_id'] ?? '') == $warehouse['id'] ? 'selected' : '' ?>>
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
                                    <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Hoạt động</option>
                                    <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Không hoạt động</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i>Cập nhật
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
