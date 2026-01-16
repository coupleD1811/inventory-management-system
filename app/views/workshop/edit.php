<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sửa phân xưởng</h3>
                <div class="card-actions">
                    <a href="<?= BASE_URL ?>workshop" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>workshop/edit/<?= $workshop['id'] ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Mã phân xưởng</label>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($workshop['code']) ?>" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Tên phân xưởng</label>
                                <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($workshop['name']) ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Vị trí</label>
                                <input type="text" name="location" class="form-control" value="<?= htmlspecialchars($workshop['location'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Người quản lý</label>
                                <input type="text" name="manager_name" class="form-control" value="<?= htmlspecialchars($workshop['manager_name'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Điện thoại</label>
                                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($workshop['phone'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="active" <?= $workshop['status'] === 'active' ? 'selected' : '' ?>>Hoạt động</option>
                                    <option value="inactive" <?= $workshop['status'] === 'inactive' ? 'selected' : '' ?>>Không hoạt động</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="notes" class="form-control" rows="3"><?= htmlspecialchars($workshop['notes'] ?? '') ?></textarea>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i>Cập nhật
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
