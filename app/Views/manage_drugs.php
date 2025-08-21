<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php $hasReport = !empty($reportId) && !empty($reportData); ?>

<style>
  .table thead th {
    white-space: nowrap
  }

  .table tbody td {
    vertical-align: middle
  }

  .table .dropdown-toggle::after {
    display: none
  }

  .basic-data-table .card-header {
    position: sticky;
    top: 0;
    z-index: 2;
    background: #fff
  }

  @media (max-width: 991.98px) {
    .table-responsive {
      overflow-x: auto
    }
  }

  .report-mode .no-report {
    display: none !important
  }

  .report-mode .print-report {
    display: block !important
  }

  @media print {

    .no-report,
    .d-print-none {
      display: none !important
    }

    .print-report {
      display: block !important
    }

    @page {
      size: A4;
      margin: 16mm
    }
  }

  /* Pager chip + buttons */
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

  .page-chip .total,
  .page-chip .slash {
    color: #6c757d;
    font-variant-numeric: tabular-nums;
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

  .page-nav .btn-icon.disabled,
  .page-nav .btn-icon:disabled {
    pointer-events: none;
    opacity: .5;
  }
</style>

<?= view('partials/sidenav') ?>

<main class="dashboard-main">
  <?= view('partials/topbar') ?>

  <div class="dashboard-main-body">
    <div class="no-report">
      <!-- Breadcrumb -->
      <div class="d-print-none d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <h6 class="fw-semibold mb-0">Manage Drugs</h6>
        <ul class="d-flex align-items-center gap-2 mb-0">
          <li class="fw-medium">
            <a href="<?= base_url('dashboard') ?>" class="d-flex align-items-center gap-1 hover-text-primary">
              <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>Dashboard
            </a>
          </li>
          <li>-</li>
          <li class="fw-medium">Manage Drugs</li>
        </ul>
      </div>

      <!-- Low stock -->
      <?php if (!empty($lowStock)): ?>
        <div class="alert alert-warning d-print-none">
          <strong>Low stock alert:</strong>
          <?php foreach ($lowStock as $ls): ?>
            <span class="badge bg-warning text-dark me-2">
              <?= esc($ls['name']) ?> (<?= (int)$ls['quantity_in_stock'] ?>/<?= (int)($ls['reorder_level'] ?? 0) ?>)
            </span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- Filters -->
      <div class="card mb-3 shadow-sm d-print-none">
        <div class="card-body overflow-auto"><!-- enables horizontal scroll if needed -->
          <form method="get" class="row g-2 align-items-end flex-nowrap"><!-- force one line -->

            <!-- Search (grows) -->
            <div class="col flex-grow-1 min-w-0">
              <label class="form-label small mb-1">Search</label>
              <div class="input-group input-group-sm">
                <span class="input-group-text bg-light">
                  <iconify-icon icon="mage:search"></iconify-icon>
                </span>
                <input
                  type="text"
                  name="q"
                  value="<?= esc($filters['q'] ?? '') ?>"
                  class="form-control"
                  placeholder="Name, batch or manufacturer"
                  autocomplete="off">
              </div>
            </div>

            <!-- Exp From -->
            <div class="col-auto">
              <label class="form-label small mb-1">Exp From</label>
              <div class="input-group input-group-sm" style="width: 170px;">
                <span class="input-group-text bg-light">
                  <iconify-icon icon="mdi:calendar"></iconify-icon>
                </span>
                <input type="date" name="exp_from" value="<?= esc($filters['exp_from'] ?? '') ?>" class="form-control">
              </div>
            </div>

            <!-- Exp To -->
            <div class="col-auto">
              <label class="form-label small mb-1">Exp To</label>
              <div class="input-group input-group-sm" style="width: 170px;">
                <span class="input-group-text bg-light">
                  <iconify-icon icon="mdi:calendar"></iconify-icon>
                </span>
                <input type="date" name="exp_to" value="<?= esc($filters['exp_to'] ?? '') ?>" class="form-control">
              </div>
            </div>

            <!-- Status -->
            <div class="col-auto" style="width: 200px;">
              <label class="form-label small mb-1">Status</label>
              <select name="status" class="form-select form-select-sm">
                <option value="">All Statuses</option>
                <?php foreach (['Available', 'Low Stock', 'Out of Stock', 'Expired'] as $s): ?>
                  <option value="<?= esc($s) ?>" <?= (($filters['status'] ?? '') === $s) ? 'selected' : '' ?>><?= esc($s) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Usability -->
            <div class="col-auto" style="width: 160px;">
              <label class="form-label small mb-1">Usability</label>
              <select name="usable" class="form-select form-select-sm">
                <option value="">All</option>
                <option value="Usable" <?= (($filters['usable'] ?? '') === 'Usable')     ? 'selected' : '' ?>>Usable</option>
                <option value="Non-usable" <?= (($filters['usable'] ?? '') === 'Non-usable') ? 'selected' : '' ?>>Non-usable</option>
              </select>
            </div>

            <!-- Actions -->
            <div class="col-auto d-flex gap-2">
              <button type="submit" class="btn btn-secondary btn-sm">Apply</button>
              <a href="<?= base_url('drugs') ?>" class="btn btn-link btn-sm text-decoration-none">Clear</a>
            </div>

          </form>
        </div>
      </div>


      <!-- Table -->
      <div class="card basic-data-table mb-5">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="card-title mb-0">Drugs List</h5>
          <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDrugModal">+ New Drug</button>
        </div>

        <div class="table-responsive p-3">
          <table class="table bordered-table mb-0" id="dataTable">
            <thead>
              <tr>
                <th>Name</th>
                <th>Dosage</th>
                <th class="text-end">Qty</th>
                <th class="text-end">Reorder</th>
                <th>Stock</th>
                <th>Batch No</th>
                <th>Manufacture</th>
                <th>Expiry</th>
                <th>Status / Usability</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($drugs as $d):
                $status   = $d['effective_status']  ?? $d['status'];
                $usable   = (int)($d['effective_usable'] ?? $d['is_usable'] ?? 1);
                $qty      = (int)$d['quantity_in_stock'];
                $reorder  = (int)($d['reorder_level'] ?? 0);
                $pct      = $reorder > 0 ? min(100, (int)round(($qty / max($reorder, 1)) * 100)) : 100;
                $meterCls = $qty <= 0 ? 'bg-danger' : ($qty <= $reorder ? 'bg-warning' : 'bg-success');
                $badge = [
                  'Available'    => 'bg-success',
                  'Low Stock'    => 'bg-warning text-dark',
                  'Out of Stock' => 'bg-secondary',
                  'Expired'      => 'bg-danger',
                ][$status] ?? 'bg-secondary';
                $useBadge = $usable ? 'bg-primary' : 'bg-dark';
              ?>
                <tr>
                  <td><?= esc($d['name']) ?></td>
                  <td><?= esc($d['dosage']) ?></td>
                  <td class="text-end"><?= $qty ?></td>
                  <td class="text-end"><?= $reorder ?></td>
                  <td style="min-width:110px">
                    <div class="progress" style="height:6px;">
                      <div class="progress-bar <?= $meterCls ?>" role="progressbar" style="width: <?= $pct ?>%;"></div>
                    </div>
                    <small class="text-muted"><?= $qty ?>/<?= $reorder ?: '—' ?></small>
                  </td>
                  <td><?= esc($d['batch_no']) ?></td>
                  <td><?= esc($d['manufacture_date']) ?></td>
                  <td><?= esc($d['expiration_date']) ?></td>
                  <td>
                    <div class="d-flex flex-wrap gap-1">
                      <span class="badge <?= $badge ?>"><?= esc($status) ?></span>
                      <span class="badge <?= $useBadge ?>"><?= $usable ? 'Usable' : 'Non-usable' ?></span>
                    </div>
                  </td>
                  <td class="text-center">
                    <div class="d-inline-flex align-items-center justify-content-center gap-2">
                      <button type="button" class="btn btn-sm btn-outline-primary edit-btn"
                        data-bs-toggle="modal" data-bs-target="#editDrugModal"
                        data-id="<?= esc($d['drug_id']) ?>"
                        data-name="<?= esc($d['name']) ?>"
                        data-dosage="<?= esc($d['dosage']) ?>"
                        data-quantity="<?= esc($d['quantity_in_stock']) ?>"
                        data-batch="<?= esc($d['batch_no']) ?>"
                        data-manufacture="<?= esc($d['manufacture_date']) ?>"
                        data-expiry="<?= esc($d['expiration_date']) ?>"
                        data-status="<?= esc($d['status']) ?>"
                        data-usable="<?= (int)($d['is_usable'] ?? 1) ?>"
                        data-reorder-level="<?= esc($d['reorder_level'] ?? 0) ?>"
                        data-reorder-quantity="<?= esc($d['reorder_quantity'] ?? '') ?>">
                        <iconify-icon icon="lucide:pencil"></iconify-icon>
                      </button>

                      <button type="button" class="btn btn-sm btn-outline-danger delete-btn"
                        data-bs-toggle="modal" data-bs-target="#deleteDrugModal"
                        data-id="<?= esc($d['drug_id']) ?>">
                        <iconify-icon icon="lucide:trash-2"></iconify-icon>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>

          <?php if (isset($pager)): ?>
            <?php
            $rq     = \Config\Services::request();
            $group  = 'drugs';

            // Robust CI4 pager getters
            $current   = (int)($pager->getCurrentPage($group) ?? 1);
            $pageCount = (int)($pager->getPageCount($group) ?? 1);
            $perPage   = (int)($rq->getGet('per_page') ?? ($pager->getPerPage($group) ?? 10));

            $hasPrev = $current > 1;
            $hasNext = $current < max(1, $pageCount);

            // Keep current filters; reset CI page params; keep per_page
            $keep = $rq->getGet();
            unset($keep['page'], $keep['page_' . $group]); // e.g. page_drugs
            $keep['per_page'] = $perPage;
            $qs = http_build_query($keep);

            $withQuery = static function (?string $uri, string $qs) {
              if (!$uri) return '#';
              return strpos($uri, '?') !== false ? "$uri&$qs" : "$uri?$qs";
            };

            $prevUri = $hasPrev ? $withQuery($pager->getPreviousPageURI($group), $qs) : '#';
            $nextUri = $hasNext ? $withQuery($pager->getNextPageURI($group),     $qs) : '#';

            // 01 / 12 style counter
            $pad = strlen((string)max(1, $pageCount));
            $fmt = static fn($n) => str_pad((string)$n, $pad, '0', STR_PAD_LEFT);
            ?>

            <div class="card-footer d-flex align-items-center justify-content-between flex-wrap gap-3">
              <!-- Counter -->
              <div class="page-chip">
                <span class="current"><?= $fmt($current) ?></span>
                <span class="slash">/</span>
                <span class="total"><?= $fmt($pageCount) ?></span>
              </div>

              <!-- Prev / Next -->
              <nav class="page-nav d-flex align-items-center gap-2" aria-label="Drugs pagination">
                <a class="btn btn-light btn-icon<?= $hasPrev ? '' : ' disabled' ?>"
                  href="<?= esc($prevUri) ?>"
                  <?= $hasPrev ? 'rel="prev"' : 'aria-disabled="true" tabindex="-1"' ?>
                  aria-label="Previous page"><span aria-hidden="true">&lsaquo;</span></a>

                <a class="btn btn-light btn-icon<?= $hasNext ? '' : ' disabled' ?>"
                  href="<?= esc($nextUri) ?>"
                  <?= $hasNext ? 'rel="next"' : 'aria-disabled="true" tabindex="-1"' ?>
                  aria-label="Next page"><span aria-hidden="true">&rsaquo;</span></a>
              </nav>

              <!-- Rows per page -->
              <form method="get" class="d-flex align-items-center gap-2 ms-auto">
                <?php
                // Preserve filters, reset CI pager params (so we jump to page 1 cleanly)
                $skip = ['per_page', 'page', 'page_' . $group];
                foreach ($rq->getGet() as $k => $v) {
                  if (in_array($k, $skip, true)) continue;
                  $val = is_array($v) ? implode(',', $v) : $v;
                  echo '<input type="hidden" name="' . esc($k) . '" value="' . esc($val) . '">';
                }
                ?>
                <label class="text-muted small mb-0">Rows</label>
                <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                  <?php foreach ([10, 25, 50, 100] as $opt): ?>
                    <option value="<?= $opt ?>" <?= ($perPage === $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                  <?php endforeach; ?>
                </select>
              </form>
            </div>
          <?php endif; ?>

        </div>
      </div>

      <!-- Report builder (unchanged aside from the controller’s computed fields) -->
      <div class="card mb-4 shadow-sm d-print-none">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h6 class="mb-0">General Drugs Report</h6>
          <div class="btn-group btn-group-sm">
            <a href="<?= base_url('drugs/report?from=' . date('Y-m-d', strtotime('-6 days')) . '&to=' . date('Y-m-d')) ?>" class="btn btn-outline-secondary">Last 7 days</a>
            <a href="<?= base_url('drugs/report?from=' . date('Y-m-01') . '&to=' . date('Y-m-d')) ?>" class="btn btn-outline-secondary">This month</a>
            <a href="<?= base_url('drugs/report?from=' . date('Y-m-d', strtotime('-29 days')) . '&to=' . date('Y-m-d')) ?>" class="btn btn-outline-secondary">Last 30 days</a>
          </div>
        </div>
        <div class="card-body">
          <form method="get" action="<?= base_url('drugs/report') ?>" class="row g-2 align-items-end mb-0">
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
    </div><!-- /.no-report -->

    <?php if ($hasReport): ?>
      <div class="print-report d-none">
        <div class="d-print-none d-flex justify-content-end gap-2 mb-3">
          <button type="button" class="btn btn-primary btn-sm" id="btnPrintReport">Print</button>
          <a href="<?= base_url('drugs') ?>" class="btn btn-outline-secondary btn-sm">Back to List</a>
        </div>

        <div class="text-center mb-4">
          <h5 class="fw-bold mb-2">
            <a href="<?= base_url('dashboard') ?>" class="sidebar-logo d-flex flex-column align-items-center text-center">
              <img src="<?= base_url('assets/images/logo.png') ?>" alt="site logo" class="light-logo mb-2" style="max-width: 120px;">
              <img src="<?= base_url('assets/images/logo-light.png') ?>" alt="site logo" class="dark-logo mb-2" style="max-width: 120px;">
              <img src="<?= base_url('assets/images/logo-icon.png') ?>" alt="site logo" class="logo-icon" style="max-width: 60px;">
            </a>
          </h5>
          <h5 class="mb-0">GENERAL DRUGS REPORT</h5>
          <div class="small text-muted">Report ID: <?= esc($reportId) ?></div>
        </div>

        <hr class="my-3">

        <div class="row mb-4 gy-2 fs-6">
          <div class="col-6">
            <p class="mb-1"><strong>Generated:</strong> <?= esc(date('Y-m-d H:i')) ?></p>
            <?php if (!empty($period['from']) || !empty($period['to'])): ?>
              <p class="mb-0"><strong>Period:</strong> <?= esc($period['from'] ?: '…') ?> — <?= esc($period['to'] ?: '…') ?></p>
            <?php else: ?>
              <p class="mb-0"><strong>Period:</strong> All-time</p>
            <?php endif; ?>
          </div>
          <div class="col-6 text-end">
            <p class="mb-1"><strong>Total Used (period):</strong> <?= (int)$totals['used'] ?></p>
            <p class="mb-0"><strong>Total Remaining (now):</strong> <?= (int)$totals['remaining'] ?></p>
          </div>
        </div>

        <h5 class="fw-semibold mb-2 fs-6">Drugs Usage Summary</h5>
        <div class="table-responsive mb-4">
          <table class="table table-sm fs-6">
            <thead>
              <tr>
                <th class="fw-normal">Drug</th>
                <th class="fw-normal">Dosage</th>
                <th class="fw-normal">Batch</th>
                <th class="fw-normal">Qty Used<?= (!empty($period['from']) || !empty($period['to'])) ? ' (Period)' : '' ?></th>
                <th class="fw-normal">Qty Remaining</th>
                <th class="fw-normal">Reorder Level</th>
                <th class="fw-normal">Status</th>
                <th class="fw-normal">Usability</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($reportData as $r):
                $status  = $r['effective_status']  ?? $r['status'];
                $usable  = (int)($r['effective_usable'] ?? $r['is_usable'] ?? 1);
                $badge   = [
                  'Available' => 'bg-success',
                  'Low Stock' => 'bg-info text-dark',
                  'Out of Stock' => 'bg-warning text-dark',
                  'Expired' => 'bg-danger'
                ][$status] ?? 'bg-secondary';
              ?>
                <tr>
                  <td class="fw-normal"><?= esc($r['name']) ?></td>
                  <td class="fw-normal"><?= esc($r['dosage']) ?></td>
                  <td class="fw-normal"><?= esc($r['batch_no']) ?></td>
                  <td class="fw-normal"><?= (int)$r['qty_used'] ?></td>
                  <td class="fw-normal"><?= (int)$r['quantity_in_stock'] ?></td>
                  <td class="fw-normal"><?= (int)($r['reorder_level'] ?? 0) ?></td>
                  <td class="fw-normal"><span class="badge <?= $badge ?>"><?= esc($status) ?></span></td>
                  <td class="fw-normal"><span class="badge <?= $usable ? 'bg-primary' : 'bg-dark' ?>"><?= $usable ? 'Usable' : 'Non-usable' ?></span></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="3" class="text-end">Totals:</th>
                <th><?= (int)$totals['used'] ?></th>
                <th><?= (int)$totals['remaining'] ?></th>
                <th colspan="3"></th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    <?php endif; ?>
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

<!-- Add Drug Modal -->
<div class="modal fade" id="addDrugModal" tabindex="-1" aria-labelledby="addDrugModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="<?= base_url('drugs/store') ?>">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addDrugModalLabel">Add New Drug</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Name</label>
              <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Dosage</label>
              <input type="text" name="dosage" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Quantity In Stock</label>
              <input type="number" name="quantity_in_stock" class="form-control" min="0" required>
            </div>
            <div class="col-12">
              <label class="form-label">Batch Number</label>
              <input type="text" name="batch_no" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Manufacture Date</label>
              <input type="date" name="manufacture_date" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Expiration Date</label>
              <input type="date" id="addExpiry" name="expiration_date" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Status</label>
              <select id="addStatus" name="status" class="form-select" required>
                <option value="Available">Available</option>
                <option value="Out of Stock">Out of Stock</option>
                <option value="Expired">Expired</option>
              </select>
            </div>
            <div class="col-12">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="addUsable" name="is_usable" value="1" checked>
                <label class="form-check-label" for="addUsable">Usable?</label>
              </div>
              <div class="form-text">If the expiry date is in the past, this will be forced to Non-usable.</div>
            </div>
            <div class="col-12">
              <label class="form-label">Reorder Level</label>
              <input type="number" name="reorder_level" class="form-control" min="0" value="0">
            </div>
            <div class="col-12">
              <label class="form-label">Suggested Reorder Quantity (optional)</label>
              <input type="number" name="reorder_quantity" class="form-control" min="1" placeholder="e.g. 100">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Drug</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Edit Drug Modal -->
<div class="modal fade" id="editDrugModal" tabindex="-1" aria-labelledby="editDrugModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="<?= base_url('drugs/update') ?>">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editDrugModalLabel">Edit Drug</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="editDrugId">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Name</label>
              <input type="text" id="editName" name="name" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Dosage</label>
              <input type="text" id="editDosage" name="dosage" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Quantity In Stock</label>
              <input type="number" id="editQuantity" name="quantity_in_stock" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Batch Number</label>
              <input type="text" id="editBatch" name="batch_no" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Manufacture Date</label>
              <input type="date" id="editManufacture" name="manufacture_date" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Expiration Date</label>
              <input type="date" id="editExpiry" name="expiration_date" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Status</label>
              <select id="editStatus" name="status" class="form-select" required>
                <option value="Available">Available</option>
                <option value="Out of Stock">Out of Stock</option>
                <option value="Expired">Expired</option>
              </select>
            </div>
            <div class="col-12">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="editUsable" name="is_usable" value="1">
                <label class="form-check-label" for="editUsable">Usable?</label>
              </div>
              <div class="form-text">If the expiry date is in the past, this will be forced to Non-usable.</div>
            </div>
            <div class="col-12">
              <label class="form-label">Reorder Level</label>
              <input type="number" id="editReorder_level" name="reorder_level" class="form-control" min="0">
            </div>
            <div class="col-12">
              <label class="form-label">Suggested Reorder Quantity (optional)</label>
              <input type="number" id="editReorder_quantity" name="reorder_quantity" class="form-control" min="1">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Drug</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Delete Drug Modal -->
<div class="modal fade" id="deleteDrugModal" tabindex="-1" aria-labelledby="deleteDrugModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" action="<?= base_url('drugs/delete') ?>">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteDrugModalLabel">Confirm Delete</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this drug record?
          <input type="hidden" name="id" id="deleteDrugId">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Scripts -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Report mode
    <?php if ($hasReport): ?>
      document.documentElement.classList.add('report-mode');
      document.querySelector('.print-report')?.classList.remove('d-none');
    <?php endif; ?>

    // Print
    document.getElementById('btnPrintReport')?.addEventListener('click', () => window.print());

    // Populate Edit
    document.querySelectorAll('.edit-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const map = {
          id: 'editDrugId',
          name: 'editName',
          dosage: 'editDosage',
          quantity: 'editQuantity',
          batch: 'editBatch',
          manufacture: 'editManufacture',
          expiry: 'editExpiry',
          status: 'editStatus',
          'reorder_level': 'editReorder_level',
          'reorder_quantity': 'editReorder_quantity'
        };
        Object.entries(map).forEach(([k, id]) => {
          const el = document.getElementById(id);
          if (el) el.value = btn.dataset[k] ?? '';
        });

        // usable switch
        const eu = document.getElementById('editUsable');
        eu.checked = (btn.dataset.usable ?? '1') === '1';

        enforceUsabilityByExpiry(document.getElementById('editExpiry'), eu, document.getElementById('editStatus'));
      });
    });

    // Delete
    document.querySelectorAll('.delete-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const del = document.getElementById('deleteDrugId');
        if (del) del.value = btn.dataset.id;
      });
    });

    // Force Non-usable if expiry < today
    function isPast(dateStr) {
      if (!dateStr) return false;
      const d = new Date(dateStr + 'T00:00:00');
      const t = new Date();
      t.setHours(0, 0, 0, 0);
      return d < t;
    }

    function enforceUsabilityByExpiry(expiryInput, usableSwitch, statusSelect) {
      const apply = () => {
        const expired = isPast(expiryInput.value) || statusSelect?.value === 'Expired';
        if (expired) {
          usableSwitch.checked = false;
          usableSwitch.disabled = true;
        } else {
          usableSwitch.disabled = false;
        }
      };
      apply();
      expiryInput?.addEventListener('change', apply);
      statusSelect?.addEventListener('change', apply);
    }

    enforceUsabilityByExpiry(
      document.getElementById('addExpiry'),
      document.getElementById('addUsable'),
      document.getElementById('addStatus')
    );
  });
</script>

<?= $this->endSection() ?>