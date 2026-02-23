<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row row-cards mb-3">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Tồn đầu kỳ</div>
                <div class="h1 mb-0"><?= number_format($stats['opening'] ?? 0, 2) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Nhập trong kỳ</div>
                <div class="h1 mb-0 text-success">+<?= number_format($stats['import'] ?? 0, 2) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Xuất trong kỳ</div>
                <div class="h1 mb-0 text-danger">-<?= number_format($stats['export'] ?? 0, 2) ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="subheader">Tồn cuối kỳ</div>
                <div class="h1 mb-0 text-primary"><?= number_format($stats['closing'] ?? 0, 2) ?></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Báo cáo cân đối nhập xuất tồn</h3>
            </div>
            <div class="card-body">
                <form method="GET" class="mb-4">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-2">
                            <label class="form-label">Kỳ báo cáo</label>
                            <select name="period" class="form-select">
                                <?php
                                $periodOptions = [
                                    'day' => 'Ngày',
                                    'week' => 'Tuần',
                                    'month' => 'Tháng',
                                    'quarter' => 'Quý',
                                    'year' => 'Năm'
                                ];
                                foreach ($periodOptions as $value => $label):
                                ?>
                                    <option value="<?= $value ?>" <?= ($period === $value) ? 'selected' : '' ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Từ ngày</label>
                            <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate) ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Đến ngày</label>
                            <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate) ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kho</label>
                            <select name="warehouse_id" class="form-select">
                                <option value="">-- Tất cả kho --</option>
                                <?php foreach ($warehouses as $wh): ?>
                                    <option value="<?= $wh['id'] ?>" <?= ($warehouseId == $wh['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($wh['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tìm mặt hàng</label>
                            <input type="text" name="keyword" class="form-control" value="<?= htmlspecialchars($keyword) ?>" placeholder="Mã hoặc tên">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-filter"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="text-muted mb-2">
                    Giai đoạn báo cáo: <strong><?= date('d/m/Y', strtotime($startDate)) ?></strong> đến <strong><?= date('d/m/Y', strtotime($endDate)) ?></strong>
                    - Tổng mặt hàng: <strong><?= number_format($stats['items'] ?? 0) ?></strong>
                </div>

                <div class="table-responsive">
                    <table class="table table-vcenter card-table table-striped">
                        <thead>
                            <tr>
                                <th>Kho</th>
                                <th>Mã hàng</th>
                                <th>Tên hàng</th>
                                <th>ĐVT</th>
                                <th class="text-end">Tồn đầu kỳ</th>
                                <th class="text-end text-success">Nhập</th>
                                <th class="text-end text-danger">Xuất</th>
                                <th class="text-end">Tồn cuối kỳ</th>
                                <th class="text-end">Min/Max</th>
                                <th>Trạng thái</th>
                                <th>Truy vết</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($rows)): ?>
                                <tr>
                                    <td colspan="11" class="text-center text-muted">Không có dữ liệu</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($rows as $row): ?>
                                    <?php
                                    $statusClass = 'bg-success';
                                    if (in_array($row['status_detail'], ['over_min', 'over_max'], true)) {
                                        $statusClass = 'bg-danger';
                                    } elseif (in_array($row['status_detail'], ['near_min', 'near_max'], true)) {
                                        $statusClass = 'bg-warning';
                                    }
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['warehouse_name']) ?></td>
                                        <td><strong><?= htmlspecialchars($row['product_code']) ?></strong></td>
                                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                                        <td><?= htmlspecialchars($row['unit']) ?></td>
                                        <td class="text-end"><?= number_format($row['opening_qty'], 2) ?></td>
                                        <td class="text-end text-success"><?= number_format($row['import_qty'], 2) ?></td>
                                        <td class="text-end text-danger"><?= number_format($row['export_qty'], 2) ?></td>
                                        <td class="text-end"><strong><?= number_format($row['closing_qty'], 2) ?></strong></td>
                                        <td class="text-end text-muted"><?= number_format($row['min_stock'], 2) ?> / <?= number_format($row['max_stock'], 2) ?></td>
                                        <td>
                                            <span class="badge <?= $statusClass ?>"><?= htmlspecialchars($row['status_label']) ?></span>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-outline-primary"
                                               href="<?= BASE_URL ?>stock_card?warehouse_id=<?= (int)$row['warehouse_id'] ?>&product_id=<?= (int)$row['product_id'] ?>&start_date=<?= htmlspecialchars($startDate) ?>&end_date=<?= htmlspecialchars($endDate) ?>">
                                                Thẻ kho
                                            </a>
                                        </td>
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
