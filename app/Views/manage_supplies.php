<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $hasReport = !empty($reportId) && !empty($reportData); ?>
<?php
// True when viewing /supplies/report (with or without query params)
$uri = service('uri');
$isReportPage = ($uri->getSegment(1) === 'supplies' && $uri->getSegment(2) === 'report');
?>

<style>
    /* ===========================
   Report / Print utilities
   =========================== */

    /* Hide print-only blocks by default; show them in .report-mode or when printing */
    .print-report {
        display: none !important;
    }

    .report-mode .no-report {
        display: none !important;
    }

    .report-mode .print-report {
        display: block !important;
    }

    @media print {

        .no-report,
        .d-print-none {
            display: none !important;
        }

        .print-report {
            display: block !important;
        }

        @page {
            size: A4;
            margin: 16mm;
        }
    }

    /* ===========================
   Filters: uniform heights
   =========================== */

    /* Single height token for inputs/selects/buttons in filter rows */
    :root {
        --filter-h: 3rem;
    }

    /* Floating controls: keep labels readable while matching height */
    .filters-row .form-floating>.form-control,
    .filters-row .form-floating>.form-select {
        height: var(--filter-h);
        padding-top: 1.25rem;
        /* space for floating label */
        padding-bottom: .5rem;
    }

    /* Buttons that should match the input height */
    .filters-row .btn-eq {
        height: var(--filter-h);
        line-height: 1.25rem;
        /* centers text nicely */
    }

    /* Keep filters in one line on large screens (optional) */
    @media (min-width: 992px) {
        .filters-row {
            flex-wrap: nowrap;
        }
    }

    /* ---- Layout polish (kept minimal) ----------------------------------- */
    .report-wrap {
        max-width: 1200px;
    }

    .report-logo {
        height: 60px;
    }

    .kpi-card .card-title {
        font-size: .8rem;
        color: #6c757d;
    }

    .kpi-card .card-value {
        font-weight: 700;
    }

    /* Make table header subtly distinct and sticky within its scroll area */
    .table thead th {
        background: var(--bs-tertiary-bg);
    }

    .report-table thead th {
        position: sticky;
        top: 0;
        z-index: 2;
    }

    /* Print rules: hide UI chrome, ensure A4 with margins */
    .d-print-hide {
        display: none !important;
    }

    @media print {

        .d-print-none,
        .btn,
        .card-header,
        .alert,
        .breadcrumb,
        .d-print-hide {
            display: none !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .report-wrap {
            max-width: 100% !important;
        }

        @page {
            size: A4;
            margin: 14mm;
        }
    }

    /* Minimal, component-scoped styles */
    .kpi {
        background: #fff;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .02);
    }

    .kpi-top {
        min-height: 1.25rem;
    }

    .kpi-label {
        font-weight: 300;
        color: #111827;
        font-size: .95rem;
    }

    .kpi-icon {
        width: 28px;
        height: 28px;
        border: 2px solid var(--bs-border-color);
        border-radius: .5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .9rem;
        color: #6c757d;
        background: #fff;
    }

    .kpi-value {
        /* Responsive "big number" like the reference */
        font-weight: 300;
        line-height: 1;
        font-size: 1rem;
    }

    .kpi-trend {
        font-size: .875rem;
    }

    .kpi-trend .up {
        color: var(--bs-success);
    }

    .kpi-trend .down {
        color: var(--bs-danger);
    }

    /* Sticky header within the responsive wrapper */
    .table-sticky thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        background: var(--bs-body-bg);
    }

    /* Right-align numbers with tabular figures for better column scanning */
    .num {
        text-align: right;
        font-variant-numeric: tabular-nums;
    }

    /* Small status dot before the badge */
    .status-dot {
        width: .6rem;
        height: .6rem;
        border-radius: 50%;
        display: inline-block;
    }

    /* Prevent header wrapping for compactness */
    .nowrap {
        white-space: nowrap;
    }

    .page-chip {
        background: var(--bs-light);
        border-radius: 999px;
        padding: .35rem .75rem;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        font-weight: 600;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .04) inset;
    }

    .page-chip .current {
        color: var(--bs-success);
        font-variant-numeric: tabular-nums;
    }

    .page-chip .total {
        color: #6c757d;
        font-variant-numeric: tabular-nums;
    }

    .page-chip .slash {
        color: #6c757d;
        opacity: .9;
    }

    .page-nav .btn-icon {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        padding: 0;
    }

    .page-nav .btn-icon:disabled,
    .page-nav .btn-icon.disabled {
        pointer-events: none;
        opacity: .5;
    }
</style>

<!-- Sidebar -->
<?= view('partials/sidenav') ?>

<div class="dashboard-main<?= $hasReport ? ' report-mode' : '' ?>">

    <?= view('partials/topbar') ?>

    <div class="dashboard-main-body">

        <!-- ====== MANAGE (hidden in report-mode) ====== -->
        <div class="no-report">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
                <h6 class="fw-semibold mb-0">Manage Supplies</h6>
                <ul class="d-flex align-items-center gap-2">
                    <li class="fw-medium">
                        <a href="<?= base_url('dashboard') ?>" class="d-flex align-items-center gap-1 hover-text-primary">
                            <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                            Dashboard
                        </a>
                    </li>
                    <li>-</li>
                    <li class="fw-medium">Manage Supplies</li>
                </ul>
            </div>

            <?php if (!empty($lowStock)): ?>
                <div class="alert alert-warning d-print-none">
                    <strong>Low stock alert:</strong>
                    <?php foreach ($lowStock as $ls): ?>
                        <span class="badge bg-warning text-dark me-2">
                            <?= esc($ls['name']) ?> (<?= (int)$ls['quantity_in_stock'] ?>/<?= (int)$ls['reorder_level'] ?>)
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-sm btn-outline-secondary" type="button"
                            data-bs-toggle="collapse" data-bs-target="#filterCard"
                            aria-expanded="true" aria-controls="filterCard">
                            Toggle Filters
                        </button>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplyModal">+ New Supply</button>
                </div>

                <!-- FILTERS -->
                <div class="collapse show" id="filterCard">
                    <div class="card-body border-bottom d-print-none">
                        <form method="get" class="row g-3 align-items-end filters-row">

                            <!-- Search (wider) -->
                            <div class="col-12 col-md-4">
                                <div class="form-floating">
                                    <input type="text" id="filterQ" name="q"
                                        value="<?= esc($filters['q'] ?? '') ?>"
                                        class="form-control" placeholder="Search…">
                                    <label for="filterQ" class="small">Search Name or Batch</label>
                                </div>
                            </div>

                            <!-- Exp From -->
                            <div class="col-6 col-md-2">
                                <div class="form-floating">
                                    <input type="date" id="filterExpFrom" name="exp_from"
                                        value="<?= esc($filters['exp_from'] ?? '') ?>"
                                        class="form-control" placeholder="Exp From">
                                    <label for="filterExpFrom" class="small">Exp From</label>
                                </div>
                            </div>

                            <!-- Exp To -->
                            <div class="col-6 col-md-2">
                                <div class="form-floating">
                                    <input type="date" id="filterExpTo" name="exp_to"
                                        value="<?= esc($filters['exp_to'] ?? '') ?>"
                                        class="form-control" placeholder="Exp To">
                                    <label for="filterExpTo" class="small">Exp To</label>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-12 col-md-2">
                                <div class="form-floating">
                                    <select id="filterStatus" name="status" class="form-select" aria-label="Status">
                                        <option value="">All Statuses</option>
                                        <option value="in_stock" <?= ($filters['status'] ?? '') === 'in_stock'  ? 'selected' : '' ?>>Available</option>
                                        <option value="low_stock" <?= ($filters['status'] ?? '') === 'low_stock' ? 'selected' : '' ?>>Low Stock</option>
                                        <option value="expired" <?= ($filters['status'] ?? '') === 'expired'   ? 'selected' : '' ?>>Expired</option>
                                    </select>
                                    <label for="filterStatus" class="small">Status</label>
                                </div>
                            </div>

                            <!-- Actions (buttons match input height via .btn-eq) -->
                            <div class="col-12 col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-secondary btn-eq flex-fill">Apply</button>
                                <a href="<?= base_url('supplies') ?>" class="btn btn-outline-secondary btn-eq flex-fill">Clear</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <div class="card basic-data-table mb-3">
                <!-- TABLE -->
                <div class="card-body p-0">
                    <table class="table bordered-table mb-0" id="dataTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end">Reorder</th>
                                <th>Suggest</th>
                                <th>Batch No</th>
                                <th>Manufacture</th>
                                <th>Expiry</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($supplies as $s):
                                $status = $s['effective_status'] ?? 'Available';
                                $qty    = (int)$s['quantity_in_stock'];
                                $reord  = (int)($s['reorder_level'] ?? 0);
                                $badge  = [
                                    'Available' => 'bg-success',
                                    'Low Stock' => 'bg-warning text-dark',
                                    'Out of Stock' => 'bg-secondary',
                                    'Expired' => 'bg-danger'
                                ][$status] ?? 'bg-secondary';
                            ?>
                                <tr>
                                    <td><?= esc($s['name']) ?></td>
                                    <td class="text-end"><?= $qty ?></td>
                                    <td class="text-end"><?= $reord ?></td>
                                    <td><?= $s['reorder_quantity'] ? (int)$s['reorder_quantity'] : '—' ?></td>
                                    <td><?= esc($s['batch_no']) ?></td>
                                    <td><?= esc($s['manufacture_date']) ?></td>
                                    <td><?= esc($s['expiration_date']) ?></td>
                                    <td><span class="badge <?= $badge ?>"><?= esc($status) ?></span></td>
                                    <td class="text-center">
                                        <div class="d-inline-flex align-items-center justify-content-center gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary edit-btn"
                                                data-bs-toggle="modal" data-bs-target="#editSupplyModal"
                                                data-id="<?= esc($s['supply_id']) ?>"
                                                data-name="<?= esc($s['name']) ?>"
                                                data-quantity="<?= esc($s['quantity_in_stock']) ?>"
                                                data-batch="<?= esc($s['batch_no']) ?>"
                                                data-manufacture="<?= esc($s['manufacture_date']) ?>"
                                                data-expiration="<?= esc($s['expiration_date']) ?>"
                                                data-reorder_level="<?= esc($s['reorder_level'] ?? 0) ?>"
                                                data-reorder_quantity="<?= esc($s['reorder_quantity'] ?? '') ?>">
                                                <iconify-icon icon="lucide:pencil"></iconify-icon>
                                            </button>

                                            <button type="button" class="btn btn-sm btn-outline-danger delete-btn"
                                                data-bs-toggle="modal" data-bs-target="#deleteSupplyModal"
                                                data-id="<?= esc($s['supply_id']) ?>">
                                                <iconify-icon icon="lucide:trash-2"></iconify-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php
                    // ---- Derive pagination numbers (fallbacks if controller didn't set them) ----
                    $perPage = isset($perPage) ? (int)$perPage : 10;
                    $total   = isset($total)   ? (int)$total   : (isset($totalSupplies) ? (int)$totalSupplies : (is_countable($supplies) ? count($supplies) : 0));
                    $page    = max(1, (int)($_GET['page'] ?? ($page ?? 1)));
                    $pages   = max(1, (int)ceil($total / max(1, $perPage)));

                    // Pad like 01 / 12
                    $pad   = strlen((string)$pages);
                    $fmt   = fn($n) => str_pad((string)$n, $pad, '0', STR_PAD_LEFT);

                    // Preserve current filters in the query string
                    $base  = current_url();
                    $qs    = $_GET;
                    unset($qs['page']);
                    $url   = function (int $p) use ($base, $qs) {
                        return $base . '?' . http_build_query(array_merge($qs, ['page' => $p]));
                    };

                    $prevDisabled = $page <= 1;
                    $nextDisabled = $page >= $pages;
                    ?>
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 p-3 border-top">
                        <div class="page-chip">
                            <span class="current"><?= $fmt($page) ?></span>
                            <span class="slash">/</span>
                            <span class="total"><?= $fmt($pages) ?></span>
                        </div>

                        <nav class="page-nav d-flex align-items-center gap-2" aria-label="Table pagination">
                            <a
                                class="btn btn-light btn-icon<?= $prevDisabled ? ' disabled' : '' ?>"
                                href="<?= $prevDisabled ? '#' : esc($url($page - 1)) ?>"
                                aria-label="Previous page">
                                <span aria-hidden="true">&lsaquo;</span>
                            </a>

                            <a
                                class="btn btn-light btn-icon<?= $nextDisabled ? ' disabled' : '' ?>"
                                href="<?= $nextDisabled ? '#' : esc($url($page + 1)) ?>"
                                aria-label="Next page">
                                <span aria-hidden="true">&rsaquo;</span>
                            </a>
                        </nav>
                    </div>

                </div>
            </div>
        </div><!-- /.no-report -->

        <!-- REPORT BUILDER -->
        <div class="card mb-4 shadow-sm d-print-none">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Supplies Report</h6>
                <div class="btn-group btn-group-sm" role="group">
                    <a class="btn btn-outline-secondary"
                        href="<?= base_url('supplies/report?from=' . date('Y-m-d', strtotime('-6 days')) . '&to=' . date('Y-m-d')) ?>">Last 7 days</a>
                    <a class="btn btn-outline-secondary"
                        href="<?= base_url('supplies/report?from=' . date('Y-m-01') . '&to=' . date('Y-m-d')) ?>">This month</a>
                    <a class="btn btn-outline-secondary"
                        href="<?= base_url('supplies/report?from=' . date('Y-m-d', strtotime('-29 days')) . '&to=' . date('Y-m-d')) ?>">Last 30 days</a>
                </div>
            </div>
            <div class="card-body">
                <form method="get" action="<?= base_url('supplies/report') ?>" class="row g-2 align-items-end mb-0">
                    <div class="col-md-3">
                        <label class="form-label small mb-1">From</label>
                        <input type="date" name="from" value="<?= esc($period['from'] ?? '') ?>" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small mb-1">To</label>
                        <input type="date" name="to" value="<?= esc($period['to'] ?? '') ?>" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3 ms-auto d-grid">
                        <button class="btn btn-primary btn-sm">Build Report</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Optional breadcrumb / page title -->
        <?php if ($hasReport): ?>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex gap-2 d-print-none">
                    <a href="<?= base_url('supplies') ?>" class="btn btn-outline-secondary">Back to List</a>
                    <button type="button" class="btn btn-primary" id="btnPrint">Print</button>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($isReportPage && !$hasReport): ?>
            <div class="alert alert-info mb-0">
                No report data to display. Adjust your filters or period and generate the report again.
            </div>
        <?php endif; ?>


        <?php if (!($hasReport ?? false)): ?>
        <?php else: ?>
            <div class="report-wrap mx-auto">

                <!-- Card wraps the entire report area for a tidy frame (header hidden in print) -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">

                        <!-- Report header: logo, title, meta -->
                        <div class="text-center mb-4">
                            <img class="report-logo mb-2" src="<?= base_url('assets/images/logo.png') ?>" alt="UIRI">
                            <h2 class="fw-bold mb-1">GENERAL SUPPLIES REPORT</h2>
                            <div class="text-secondary small">Report ID: <?= esc($reportId) ?></div>
                        </div>

                        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3 mb-3">
                            <!-- Generated -->
                            <div class="col">
                                <div class="kpi p-3 h-100">
                                    <div class="d-flex justify-content-between align-items-start kpi-top mb-1">
                                        <div class="kpi-label">Generated</div>
                                        <span class="kpi-icon">
                                            <iconify-icon icon="mdi:clock-outline" class="opacity-75"></iconify-icon>
                                        </span>
                                    </div>
                                    <div class="kpi-value"><?= esc(date('Y-m-d')) ?></div>
                                    <div class="kpi-trend text-muted"><span>as of now</span></div>
                                </div>
                            </div>

                            <!-- Period (From — To combined to match 4-card layout) -->
                            <div class="col">
                                <div class="kpi p-3 h-100">
                                    <div class="d-flex justify-content-between align-items-start kpi-top mb-1">
                                        <div class="kpi-label">Period</div>
                                        <span class="kpi-icon">📅</span>
                                    </div>
                                    <div class="kpi-value">
                                        <?php if (!empty($period['from']) || !empty($period['to'])): ?>
                                            <?= esc($period['from'] ?: '—') ?> — <?= esc($period['to'] ?: '—') ?>
                                        <?php else: ?>
                                            All-time
                                        <?php endif; ?>
                                    </div>
                                    <div class="kpi-trend text-muted">range</div>
                                </div>
                            </div>

                            <!-- Total Used -->
                            <div class="col">
                                <div class="kpi p-3 h-100">
                                    <div class="d-flex justify-content-between align-items-start kpi-top mb-1">
                                        <div class="kpi-label">Total Used</div>
                                        <span class="kpi-icon">
                                            <iconify-icon icon="mdi:trending-up" class="opacity-75"></iconify-icon>
                                        </span>

                                    </div>
                                    <div class="kpi-value"><?= number_format((int)($totals['used'] ?? 0)) ?></div>

                                    <?php if (isset($trends['used'])):
                                        $delta = (float)$trends['used'];
                                        $up = $delta >= 0; ?>
                                        <div class="kpi-trend">
                                            <span class="<?= $up ? 'up' : 'down' ?> fw-semibold">
                                                <?= $up ? '↑' : '↓' ?> <?= number_format(abs($delta), 1) ?>%
                                            </span>
                                            <span class="text-muted">since last period</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="kpi-trend text-muted">since last period</div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Total Remaining -->
                            <div class="col">
                                <div class="kpi p-3 h-100">
                                    <div class="d-flex justify-content-between align-items-start kpi-top mb-1">
                                        <div class="kpi-label">Total Remaining</div>
                                        <span class="kpi-icon">
                                            <iconify-icon icon="mdi:layers-outline" class="opacity-75"></iconify-icon>
                                        </span>

                                    </div>
                                    <div class="kpi-value"><?= number_format((int)($totals['remaining'] ?? 0)) ?></div>

                                    <?php if (isset($trends['remaining'])):
                                        $delta = (float)$trends['remaining'];
                                        $up = $delta >= 0; ?>
                                        <div class="kpi-trend">
                                            <span class="<?= $up ? 'up' : 'down' ?> fw-semibold">
                                                <?= $up ? '↑' : '↓' ?> <?= number_format(abs($delta), 1) ?>%
                                            </span>
                                            <span class="text-muted">since last period</span>
                                        </div>
                                    <?php else: ?>
                                        <div class="kpi-trend text-muted">since last period</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0 table-sticky">
                                <!-- Consistent column widths on larger screens -->
                                <colgroup>
                                    <col style="width:40%">
                                    <col style="width:15%">
                                    <col style="width:12%">
                                    <col style="width:12%">
                                    <col style="width:12%">
                                    <col style="width:9%">
                                </colgroup>

                                <thead class="table-light">
                                    <tr class="text-muted">
                                        <th class="nowrap">Supply</th>
                                        <th class="d-none d-md-table-cell nowrap">Batch</th>
                                        <th class="text-end nowrap">Qty Used (Period)</th>
                                        <th class="text-end nowrap">Qty Remaining</th>
                                        <th class="text-end nowrap">Reorder Level</th>
                                        <th class="nowrap">Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($reportData as $r):
                                        $status = $r['effective_status'] ?? 'Available';

                                        // Row tint by status (keep subtle)
                                        $rowClass = match ($status) {
                                            'Expired'      => 'table-danger',
                                            'Out of Stock' => 'table-secondary',
                                            'Low Stock'    => 'table-warning',
                                            default        => ''
                                        };

                                        // Badge & dot colors
                                        $badge = [
                                            'Available'    => 'bg-success',
                                            'Low Stock'    => 'bg-warning text-dark',
                                            'Out of Stock' => 'bg-secondary',
                                            'Expired'      => 'bg-danger',
                                        ][$status] ?? 'bg-secondary';

                                        $dot = [
                                            'Available'    => 'bg-success',
                                            'Low Stock'    => 'bg-warning',
                                            'Out of Stock' => 'bg-secondary',
                                            'Expired'      => 'bg-danger',
                                        ][$status] ?? 'bg-secondary';
                                    ?>
                                        <tr class="<?= $rowClass ?>">
                                            <!-- Supply name; show batch below on small screens -->
                                            <td class="fw-semibold">
                                                <?= esc($r['name']) ?>
                                                <div class="small text-muted d-md-none">Batch: <?= esc($r['batch_no']) ?></div>
                                            </td>

                                            <!-- Batch (hidden on < md; shown as subline above) -->
                                            <td class="d-none d-md-table-cell"><?= esc($r['batch_no']) ?></td>

                                            <!-- Numeric columns (right-aligned & tabular) -->
                                            <td class="num"><?= number_format((int)($r['qty_used'] ?? 0)) ?></td>
                                            <td class="num"><?= number_format((int)($r['quantity_in_stock'] ?? 0)) ?></td>
                                            <td class="num"><?= number_format((int)($r['reorder_level'] ?? 0)) ?></td>

                                            <!-- Status with dot + pill -->
                                            <td class="nowrap">
                                                <span class="d-inline-flex align-items-center gap-2">
                                                    <span class="status-dot <?= $dot ?>"></span>
                                                    <span class="badge rounded-pill <?= $badge ?>"><?= esc($status) ?></span>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>

                                <tfoot class="table-light">
                                    <tr class="fw-semibold">
                                        <th colspan="2" class="text-end">Totals:</th>
                                        <th class="num"><?= number_format((int)($totals['used'] ?? 0)) ?></th>
                                        <th class="num"><?= number_format((int)($totals['remaining'] ?? 0)) ?></th>
                                        <th colspan="2"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- Printed footer -->
                        <div class="text-muted small mt-3 d-none d-print-block text-center w-100">
                            Generated by UIRI Clinic IMS • <?= esc(date('Y-m-d')) ?>
                        </div>

                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- ====== MODALS (Add / Edit / Delete) ====== -->
        <!-- Add -->
        <div class="modal fade" id="addSupplyModal" tabindex="-1" aria-labelledby="addSupplyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" action="<?= base_url('supplies/store') ?>">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addSupplyModalLabel">Add New Supply</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Quantity In Stock</label>
                                    <input type="number" class="form-control" name="quantity_in_stock" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Batch No</label>
                                    <input type="text" class="form-control" name="batch_no">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Manufacture Date</label>
                                    <input type="date" class="form-control" name="manufacture_date">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Expiration Date</label>
                                    <input type="date" class="form-control" name="expiration_date">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Reorder Level</label>
                                    <input type="number" class="form-control" name="reorder_level" min="0" value="0">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Suggested Reorder Quantity (optional)</label>
                                    <input type="number" class="form-control" name="reorder_quantity" min="1" placeholder="e.g. 100">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Supply</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit -->
        <div class="modal fade" id="editSupplyModal" tabindex="-1" aria-labelledby="editSupplyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" action="<?= base_url('supplies/update') ?>">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editSupplyModalLabel">Edit Supply</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" id="editSupplyId">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" id="editSupplyName" name="name" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Quantity In Stock</label>
                                    <input type="number" class="form-control" id="editSupplyQuantity" name="quantity_in_stock" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Batch No</label>
                                    <input type="text" class="form-control" id="editSupplyBatch" name="batch_no">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Manufacture Date</label>
                                    <input type="date" class="form-control" id="editSupplyManufacture" name="manufacture_date">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Expiration Date</label>
                                    <input type="date" class="form-control" id="editSupplyExpiration" name="expiration_date">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Reorder Level</label>
                                    <input type="number" class="form-control" id="editReorder_level" name="reorder_level" min="0">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Suggested Reorder Quantity (optional)</label>
                                    <input type="number" class="form-control" id="editReorder_quantity" name="reorder_quantity" min="1">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Supply</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete -->
        <div class="modal fade" id="deleteSupplyModal" tabindex="-1" aria-labelledby="deleteSupplyModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="post" action="<?= base_url('supplies/delete') ?>">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteSupplyModalLabel">Confirm Delete</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this supply record?
                            <input type="hidden" name="id" id="deleteSupplyId">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div><!-- /.dashboard-main-body -->

    <footer class="d-footer">
        <div class="row align-items-center justify-content-between">
            <div class="col-auto">
                <p class="mb-0">© <?= date('Y') ?>. All Rights Reserved.</p>
            </div>
            <div class="col-auto">
                <p class="mb-0">Made by <span class="text-primary-600">UGANDA INDUSTRIAL RESEARCH INSTITUTE</span></p>
            </div>
        </div>
    </footer>
    </main>

    <!-- JS -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // report mode


            // print
            document.getElementById('btnPrint')?.addEventListener('click', () => window.print());

            // edit modal
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const map = {
                        id: 'editSupplyId',
                        name: 'editSupplyName',
                        quantity: 'editSupplyQuantity',
                        batch: 'editSupplyBatch',
                        manufacture: 'editSupplyManufacture',
                        expiration: 'editSupplyExpiration',
                        reorder_level: 'editReorder_level',
                        reorder_quantity: 'editReorder_quantity'
                    };
                    Object.entries(map).forEach(([dataKey, inputId]) => {
                        const el = document.getElementById(inputId);
                        if (el) el.value = btn.dataset[dataKey] ?? '';
                    });
                });
            });

            // delete modal
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const el = document.getElementById('deleteSupplyId');
                    if (el) el.value = btn.dataset.id;
                });
            });
        });
    </script>

    <?= $this->endSection() ?>