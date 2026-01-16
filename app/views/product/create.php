<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thêm sản phẩm mới</h3>
                <div class="card-actions">
                    <a href="<?= BASE_URL ?>product" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>product/create" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Mã sản phẩm</label>
                                <input type="text" name="code" class="form-control" 
                                       placeholder="VD: SP001" 
                                       value="<?= htmlspecialchars($old['code'] ?? '') ?>" 
                                       required>
                                <small class="form-hint">Mã sản phẩm phải là duy nhất</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Tên sản phẩm</label>
                                <input type="text" name="name" class="form-control" 
                                       placeholder="VD: Laptop Dell Inspiron" 
                                       value="<?= htmlspecialchars($old['name'] ?? '') ?>" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Kho</label>
                                <select name="warehouse_id" class="form-select" required>
                                    <option value="">-- Chọn kho --</option>
                                    <?php foreach ($warehouses as $warehouse): ?>
                                        <option value="<?= $warehouse['id'] ?>" 
                                                <?= ($old['warehouse_id'] ?? '') == $warehouse['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($warehouse['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Đơn vị tính</label>
                                <input type="text" name="unit" class="form-control" 
                                       placeholder="VD: cái, hộp, thùng..." 
                                       value="<?= htmlspecialchars($old['unit'] ?? 'cái') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tồn kho tối thiểu</label>
                                <input type="number" name="min_stock" class="form-control" 
                                       value="<?= htmlspecialchars($old['min_stock'] ?? '0') ?>" 
                                       min="0">
                                <small class="form-hint">Cảnh báo khi tồn kho dưới mức này</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tồn kho tối đa</label>
                                <input type="number" name="max_stock" class="form-control" 
                                       value="<?= htmlspecialchars($old['max_stock'] ?? '0') ?>" 
                                       min="0">
                                <small class="form-hint">Cảnh báo khi tồn kho vượt mức này</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3" 
                                  placeholder="Mô tả về sản phẩm..."><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hình ảnh</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="form-hint">Chấp nhận: JPG, PNG, GIF, WEBP. Tối đa 5MB</small>
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
                            <i class="ti ti-device-floppy me-2"></i>Lưu sản phẩm
                        </button>
                        <a href="<?= BASE_URL ?>product" class="btn btn-secondary">
                            <i class="ti ti-x me-2"></i>Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
