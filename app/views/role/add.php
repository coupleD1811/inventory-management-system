<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thêm vai trò mới</h3>
                <div class="card-actions">
                    <a href="<?= BASE_URL ?>role" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>role/add">
                    <div class="mb-3">
                        <label class="form-label required">Tên vai trò (slug)</label>
                        <input type="text" name="name" class="form-control" 
                               placeholder="VD: warehouse_manager" 
                               required>
                        <small class="form-hint">Chỉ sử dụng chữ thường, số và dấu gạch dưới</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Tên hiển thị</label>
                        <input type="text" name="display_name" class="form-control" 
                               placeholder="VD: Quản lý kho" 
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3" 
                                  placeholder="Mô tả về vai trò này..."></textarea>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-2"></i>Lưu vai trò
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
