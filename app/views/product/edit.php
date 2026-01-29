<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sửa sản phẩm</h3>
                <div class="card-actions">
                    <a href="<?= BASE_URL ?>product" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>product/edit/<?= $product['id'] ?>" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Mã sản phẩm</label>
                                <input type="text" name="code" class="form-control" 
                                       placeholder="VD: SP001" 
                                       value="<?= htmlspecialchars($product['code']) ?>" 
                                       required>
                                <small class="form-hint">Mã sản phẩm phải là duy nhất</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Tên sản phẩm</label>
                                <input type="text" name="name" class="form-control" 
                                       placeholder="VD: Laptop Dell Inspiron" 
                                       value="<?= htmlspecialchars($product['name']) ?>" 
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
                                                <?= $product['warehouse_id'] == $warehouse['id'] ? 'selected' : '' ?>>
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
                                       value="<?= htmlspecialchars($product['unit']) ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tồn kho tối thiểu</label>
                                <input type="number" name="min_stock" class="form-control" 
                                       value="<?= htmlspecialchars($product['min_stock']) ?>" 
                                       min="0">
                                <small class="form-hint">Cảnh báo khi tồn kho dưới mức này</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tồn kho tối đa</label>
                                <input type="number" name="max_stock" class="form-control" 
                                       value="<?= htmlspecialchars($product['max_stock']) ?>" 
                                       min="0">
                                <small class="form-hint">Cảnh báo khi tồn kho vượt mức này</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3" 
                                  placeholder="Mô tả về sản phẩm..."><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hình ảnh</label>
                        <?php if ($product['image']): ?>
                            <div class="mb-2">
                                <img src="<?= BASE_URL ?>public/uploads/products/<?= $product['image'] ?>" 
                                     alt="<?= htmlspecialchars($product['name']) ?>" 
                                     class="img-thumbnail" 
                                     style="max-width: 200px;">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="form-hint">Chấp nhận: JPG, PNG, GIF, WEBP. Tối đa 5MB. Để trống nếu không đổi ảnh.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="active" <?= $product['status'] === 'active' ? 'selected' : '' ?>>
                                Hoạt động
                            </option>
                            <option value="inactive" <?= $product['status'] === 'inactive' ? 'selected' : '' ?>>
                                Không hoạt động
                            </option>
                        </select>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i>Cập nhật
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
