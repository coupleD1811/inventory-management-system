<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thêm kho hàng mới</h3>
                <div class="card-actions">
                    <a href="<?= BASE_URL ?>warehouse" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>warehouse/create">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Mã kho</label>
                                <input type="text" name="code" class="form-control" 
                                       placeholder="VD: KHO001" 
                                       value="<?= htmlspecialchars($old['code'] ?? '') ?>" 
                                       required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Tên kho</label>
                                <input type="text" name="name" class="form-control" 
                                       placeholder="VD: Kho trung tâm" 
                                       value="<?= htmlspecialchars($old['name'] ?? '') ?>" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Địa chỉ</label>
                        <textarea name="address" class="form-control" rows="2" 
                                  placeholder="Địa chỉ kho hàng..."><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Quản lý kho</label>
                                <select name="manager_id" class="form-select">
                                    <option value="">-- Chọn quản lý kho --</option>
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?= $user['id'] ?>" 
                                                <?= ($old['manager_id'] ?? '') == $user['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($user['full_name']) ?> (<?= htmlspecialchars($user['username']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="form-hint">Người chịu trách nhiệm quản lý kho này</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Điện thoại</label>
                                <input type="text" name="phone" class="form-control" 
                                       placeholder="VD: 0901234567" 
                                       value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Sức chứa (m²)</label>
                                <input type="number" step="0.01" name="capacity" class="form-control" 
                                       placeholder="VD: 1000" 
                                       value="<?= htmlspecialchars($old['capacity'] ?? '0') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="notes" class="form-control" rows="2" 
                                  placeholder="Ghi chú thêm..."><?= htmlspecialchars($old['notes'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="active" <?= ($old['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="inactive" <?= ($old['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Không hoạt động</option>
                        </select>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i>Lưu
                        </button>
                        <a href="<?= BASE_URL ?>warehouse" class="btn btn-secondary">
                            <i class="ti ti-x me-2"></i>Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
