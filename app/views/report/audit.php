<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards mb-3">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Tổng thao tác</div>
                <div class="h1 mb-0"><?= number_format($stats['total_logs'] ?? 0) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Tạo mới</div>
                <div class="h1 mb-0 text-primary"><?= number_format($stats['create_actions'] ?? 0) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Duyệt/Từ chối</div>
                <div class="h1 mb-0 text-warning"><?= number_format(($stats['approve_actions'] ?? 0) + ($stats['reject_actions'] ?? 0)) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Người thao tác</div>
                <div class="h1 mb-0 text-success"><?= number_format($stats['active_users'] ?? 0) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Lịch sử thao tác hệ thống</h3>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-3">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label">Từ ngày</label>
                            <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate) ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Đến ngày</label>
                            <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate) ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Hành động</label>
                            <select name="action" class="form-select">
                                <option value="">-- Tất cả --</option>
                                <?php
                                $actions = [
                                    'create_import' => 'Tạo phiếu nhập',
                                    'create_export' => 'Tạo phiếu xuất',
                                    'approve_import' => 'Duyệt phiếu nhập',
                                    'approve_export' => 'Duyệt phiếu xuất',
                                    'reject_import' => 'Từ chối phiếu nhập',
                                    'reject_export' => 'Từ chối phiếu xuất',
                                    'delete_import' => 'Xóa phiếu nhập',
                                    'delete_export' => 'Xóa phiếu xuất'
                                ];
                                foreach ($actions as $key => $label):
                                ?>
                                    <option value="<?= $key ?>" <?= $action === $key ? 'selected' : '' ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Đối tượng</label>
                            <select name="entity_type" class="form-select">
                                <option value="">-- Tất cả --</option>
                                <option value="import" <?= $entityType === 'import' ? 'selected' : '' ?>>Phiếu nhập</option>
                                <option value="export" <?= $entityType === 'export' ? 'selected' : '' ?>>Phiếu xuất</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Từ khóa</label>
                            <input type="text" name="keyword" class="form-control" value="<?= htmlspecialchars($keyword) ?>" placeholder="Mã phiếu, mô tả, người dùng">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-filter"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>Thời gian</th>
                                <th>Người thao tác</th>
                                <th>Hành động</th>
                                <th>Đối tượng</th>
                                <th>Mã tham chiếu</th>
                                <th>Mô tả</th>
                                <th>IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($logs)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Không có dữ liệu</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($logs as $log): ?>
                                    <tr>
                                        <td><?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?></td>
                                        <td>
                                            <div><?= htmlspecialchars($log['full_name'] ?? '-') ?></div>
                                            <div class="text-muted small"><?= htmlspecialchars($log['username'] ?? '-') ?></div>
                                        </td>
                                        <td><span class="badge bg-blue-lt text-blue"><?= htmlspecialchars($log['action']) ?></span></td>
                                        <td><?= htmlspecialchars($log['entity_type'] ?? '-') ?><?= !empty($log['entity_id']) ? (' #' . (int)$log['entity_id']) : '' ?></td>
                                        <td>
                                            <?php if (!empty($log['reference_code']) && $log['entity_type'] === 'import'): ?>
                                                <a href="<?= BASE_URL ?>import/detail/<?= (int)$log['entity_id'] ?>"><?= htmlspecialchars($log['reference_code']) ?></a>
                                            <?php elseif (!empty($log['reference_code']) && $log['entity_type'] === 'export'): ?>
                                                <a href="<?= BASE_URL ?>export/detail/<?= (int)$log['entity_id'] ?>"><?= htmlspecialchars($log['reference_code']) ?></a>
                                            <?php else: ?>
                                                <?= htmlspecialchars($log['reference_code'] ?? '-') ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($log['description'] ?? '-') ?></td>
                                        <td class="text-muted"><?= htmlspecialchars($log['ip_address'] ?? '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>
