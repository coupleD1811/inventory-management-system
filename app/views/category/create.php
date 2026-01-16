<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thêm danh mục mới</h3>
                <div class="card-actions">
                    <a href="<?= BASE_URL ?>category" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>category/create">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Mã danh mục</label>
                                <input type="text" name="code" class="form-control" 
                                       placeholder="VD: DM001" 
                                       value="<?= htmlspecialchars($old['code'] ?? '') ?>" 
                                       required>
                                <small class="form-hint">Mã danh mục phải là duy nhất</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Tên danh mục</label>
                                <input type="text" name="name" class="form-control" 
                                       placeholder="VD: Điện tử" 
                                       value="<?= htmlspecialchars($old['name'] ?? '') ?>" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3" 
                                  placeholder="Mô tả về danh mục..."><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="active" <?= ($old['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>
                                Hoạt động
                            </option>
                            <option value="inactive" <?= ($old['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>
                                Không hoạt động
                            </option>
                        </select>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i>Lưu danh mục
                        </button>
                        <a href="<?= BASE_URL ?>category" class="btn btn-secondary">
                            <i class="ti ti-x me-2"></i>Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
