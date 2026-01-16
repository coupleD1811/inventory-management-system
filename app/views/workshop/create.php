<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thêm phân xưởng</h3>
                <div class="card-actions">
                    <a href="<?= BASE_URL ?>workshop" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>workshop/create">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Tên phân xưởng</label>
                                <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($old['name'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Vị trí</label>
                                <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($old['location'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Người quản lý</label>
                                <input type="text" name="manager_name" class="form-control" value="<?= htmlspecialchars($old['manager_name'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Điện thoại</label>
                                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="active" <?= ($old['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Hoạt động</option>
                                    <option value="inactive" <?= ($old['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Không hoạt động</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($old['notes'] ?? '') ?></textarea>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i>Lưu
                        </button>
                        <a href="<?= BASE_URL ?>workshop" class="btn btn-secondary">
                            <i class="ti ti-x me-2"></i>Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
