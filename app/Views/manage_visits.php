<?= $this->extend('layouts/main') ?>
<?= helper(['form', 'visit']) ?>
<?= $this->section('content') ?>

<?= $this->section('styles') ?>
<style>
    /* Page polish */
    .card {
        border-radius: .75rem
    }

    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, .05)
    }

    .input-group-text {
        border-right: 0
    }

    .input-group .form-control,
    .input-group .form-select {
        border-left: 0;
        box-shadow: none
    }

    thead.bg-light th,
    thead th {
        font-weight: 600
    }

    /* Wizard + modals */
    .modal .card {
        border-radius: .75rem
    }

    .modal .form-label {
        font-weight: 500
    }

    .nav-pills .nav-link {
        border: 1px solid rgba(0, 0, 0, .08)
    }

    .nav-pills .nav-link.active {
        border-color: transparent
    }

    .modal-footer.sticky-bottom {
        position: sticky;
        bottom: 0;
        z-index: 10
    }

    /* Faster modal feeling */
    @media (prefers-reduced-motion:no-preference) {
        .modal.fade .modal-dialog {
            transition-duration: .12s
        }
    }

    /* Filters */
    .filters-card .form-label {
        font-size: .825rem;
        color: #6c757d
    }

    /* Mobile buttons */
    @media (max-width: 767.98px) {
        .filters-card .btn {
            width: 100%
        }
    }

    /* Print */
    @media print {

        .d-print-none,
        .dropdown,
        button,
        .modal {
            display: none !important
        }

        .print-report {
            display: block !important;
            margin: 0
        }
    }

    /* Filters card polish */
    .filters-card {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 1px 6px rgba(16, 24, 40, .06)
    }

    .filters-card .form-label {
        font-size: .82rem;
        font-weight: 600;
        color: var(--bs-secondary-color);
        margin-bottom: .35rem
    }

    /* Icons on the left of inputs */
    .filters-card .input-group-text {
        background: #fff;
        color: var(--bs-secondary-color);
        border-right: 0;
        display: flex;
        align-items: center
    }

    .filters-card iconify-icon {
        font-size: 1.05rem;
        line-height: 1
    }

    /* Keep all controls the same height */
    .filters-card .input-group .form-control,
    .filters-card .input-group .form-select {
        height: 44px;
        border-left: 0
    }

    .filters-card .form-control:focus,
    .filters-card .form-select:focus {
        box-shadow: 0 0 0 .15rem rgba(13, 110, 253, .12);
        border-color: #86b7fe
    }

    /* Hide the native date picker icon so you don't see two calendars.
   (still clickable; we keep its space and click target) */
    .filters-card input[type="date"]::-webkit-calendar-picker-indicator {
        opacity: 0
    }

    .filters-card input[type="date"]::-webkit-inner-spin-button {
        display: none
    }

    @supports selector(:-moz-ui-valid) {
        /* Firefox keeps its UI separate; nothing fancy needed */
    }

    /* Buttons area spacing on small screens */
    @media (max-width: 767.98px) {
        .filters-card .btn {
            width: 100%
        }
    }

    /* ===== Unified Visit (Pinterest-inspired) ===== */
    .vu-modal {
        border: 0;
        border-radius: 20px;
        overflow: hidden;
        background: #fff
    }

    .vu-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 18px;
        background: linear-gradient(135deg, #eef2ff 0%, #fdf2f8 100%);
        border-bottom: 1px solid rgba(16, 24, 40, .06)
    }

    .vu-header h5 {
        font-weight: 700;
        margin: 0
    }

    .vu-avatar {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        background: #fff;
        display: grid;
        place-items: center;
        font-size: 20px;
        font-weight: 700;
        box-shadow: 0 4px 14px rgba(16, 24, 40, .08);
        color: #1e293b
    }

    /* sticky segmented tabs */
    .vu-tabs {
        position: sticky;
        top: 0;
        z-index: 1;
        background: #fff;
        padding: 10px 16px;
        border-bottom: 1px solid rgba(16, 24, 40, .06)
    }

    .vu-tabs .nav-pills {
        gap: .5rem;
        justify-content: center
    }

    .vu-tabs .nav-link {
        border-radius: 999px;
        border: 1px solid rgba(16, 24, 40, .12);
        background: #fff;
        padding: .5rem 1rem;
        font-weight: 600;
        color: #334155
    }

    .vu-tabs .nav-link:hover {
        border-color: rgba(16, 24, 40, .24)
    }

    .vu-tabs .nav-link.active {
        background: #1d4ed8;
        border-color: #1d4ed8;
        color: #fff;
        box-shadow: 0 6px 18px rgba(29, 78, 216, .25)
    }

    /* compact body padding */
    .vu-body {
        padding-top: 10px
    }

    /* key-value pills row */
    .vu-kv {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 8px
    }

    @media (max-width: 991.98px) {
        .vu-kv {
            grid-template-columns: repeat(2, 1fr)
        }
    }

    .vu-kv .kv {
        background: #fff;
        border: 1px solid rgba(16, 24, 40, .08);
        border-radius: 12px;
        padding: 12px 14px
    }

    .vu-kv .label {
        font-size: .75rem;
        color: #64748b;
        margin-bottom: 4px
    }

    .vu-kv .value {
        font-weight: 600;
        color: #0f172a
    }

    /* vitals chips */
    .vu-chips {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
        margin: 10px 0 4px
    }

    .vu-chip {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .38rem .65rem;
        border-radius: 999px;
        font-weight: 600;
        background: #f8fafc;
        border: 1px solid rgba(16, 24, 40, .08);
        color: #0f172a
    }

    .vu-chip iconify-icon {
        font-size: 1rem
    }

    /* card tiles (Pinterest-y) */
    .vu-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px
    }

    @media (max-width: 991.98px) {
        .vu-grid {
            grid-template-columns: 1fr
        }
    }

    .vu-card {
        border: 1px solid rgba(16, 24, 40, .08);
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 6px 24px rgba(16, 24, 40, .06)
    }

    .vu-card .vu-card-body {
        padding: 16px
    }

    .vu-card h6 {
        font-weight: 700;
        margin: 0 0 8px
    }

    /* lists inside tabs */
    .vu-list .list-group-item {
        padding: .65rem 1rem;
        border: none;
        border-bottom: 1px dashed rgba(16, 24, 40, .08)
    }

    .vu-list .list-group-item:last-child {
        border-bottom: none
    }

    /* small helpers */
    .btn-icon {
        display: inline-flex;
        align-items: center;
        gap: .4rem
    }

    /* ---------- Report (Pinterest / lab look) ---------- */
    :root {
        --rp-bg: #f5f7fb;
        --rp-card: #ffffff;
        --rp-border: rgba(16, 24, 40, .10);
        --rp-soft: rgba(16, 24, 40, .06);
        --rp-muted: #64748b;
        --rp-title: #0f172a;
        --rp-accent: #1d4ed8;
        /* blue */
        --rp-accent-2: #ec4899;
        /* pink */
    }

    .report-shell {
        max-width: 1120px;
        margin: 0 auto;
        padding: 16px;
        background: var(--rp-bg);
        color: var(--rp-title);
    }

    /* toolbar (screen only) */
    .report-toolbar .btn {
        border-radius: 999px;
    }

    /* --- header --- */
    .report-header {
        background: #fff;
        border: 1px solid var(--rp-border);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 14px 34px var(--rp-soft);
        margin-bottom: 14px;
    }

    .report-header::before {
        content: "";
        display: block;
        height: 8px;
        background: linear-gradient(135deg, var(--rp-accent) 0%, var(--rp-accent-2) 100%);
    }

    .report-header>div {
        padding: 16px 18px;
    }

    .report-id {
        display: flex;
        gap: 12px;
        align-items: center;
        border-bottom: 1px solid var(--rp-border);
    }

    .report-avatar {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: grid;
        place-items: center;
        font-weight: 800;
        font-size: 20px;
        background: #fff;
        box-shadow: 0 10px 24px var(--rp-soft);
        color: #1e293b;
    }

    .report-chips {
        display: flex;
        flex-wrap: wrap;
        gap: .4rem;
        margin-top: 6px;
    }

    .report-chip {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .35rem .6rem;
        border-radius: 999px;
        font-weight: 600;
        background: #f8fafc;
        color: #0f172a;
        border: 1px solid var(--rp-border);
        font-size: .9rem;
    }

    .report-chip iconify-icon {
        font-size: 1rem;
    }

    /* brand centered */
    .report-brand {
        display: grid;
        place-items: center;
        border-bottom: 1px dashed var(--rp-border);
    }

    .report-brand img {
        height: 56px;
        object-fit: contain;
        opacity: .95;
    }

    .report-meta {
        display: grid;
        grid-auto-flow: column;
        grid-auto-columns: 1fr;
        gap: 12px;
        font-size: .95rem;
        color: #334155;
    }

    /* --- “Vitals” pill card --- */
    .report-card {
        background: var(--rp-card);
        border: 1px solid var(--rp-border);
        border-radius: 16px;
        box-shadow: 0 10px 28px var(--rp-soft);
        break-inside: avoid;
    }

    .report-card .body {
        padding: 14px 16px;
    }

    .report-title {
        margin: 0 0 8px;
        font-weight: 800;
        letter-spacing: .02em;
        color: #111827;
        text-transform: uppercase;
        font-size: .9rem;
    }

    /* --- masonry-ish content grid --- */
    .report-grid {
        display: grid;
        gap: 12px;
        grid-template-columns: 1.2fr 1fr;
        /* heavier left column */
    }

    @media (max-width: 992px) {
        .report-meta {
            grid-auto-flow: row;
            grid-auto-columns: auto;
        }

        .report-grid {
            grid-template-columns: 1fr;
        }
    }

    /* lists */
    .report-list {
        margin: 0;
        padding-left: 1.1rem;
    }

    .report-list li {
        margin: .2rem 0;
    }

    /* tables */
    .report-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid var(--rp-border);
        border-radius: 12px;
        overflow: hidden;
    }

    .report-table th,
    .report-table td {
        padding: .6rem .8rem;
        vertical-align: top;
    }

    .report-table th {
        width: 220px;
        background: #f8fafc;
        font-weight: 700;
        color: #334155;
        border-right: 1px dashed var(--rp-border);
    }

    .report-table tr+tr th,
    .report-table tr+tr td {
        border-top: 1px dashed var(--rp-border);
    }

    /* print */
    @media print {
        .report-shell {
            padding: 0;
            background: #fff;
        }

        .report-toolbar {
            display: none !important;
        }

        .report-header,
        .report-card {
            box-shadow: none;
        }

        @page {
            margin: 12mm
        }

        * {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
<?= $this->endSection() ?>

<?php
// Harden all inputs coming from the controller so the view never errors on nulls.
$patients       = isset($patients) && is_array($patients) ? $patients : [];
$visits         = isset($visits) && is_array($visits) ? $visits : [];
$filters        = isset($filters) && is_array($filters) ? $filters : [];

$prescriptions  = isset($prescriptions) && is_array($prescriptions) ? $prescriptions : [];
$supplies_used  = isset($supplies_used) && is_array($supplies_used) ? $supplies_used : [];
$outcome        = isset($outcome) && is_array($outcome) ? $outcome : null;

$currentDoctor  = isset($currentDoctor) && is_array($currentDoctor) ? $currentDoctor : [];
?>


<?php
// Fetch “?report=” safely in a view
$request    = service('request');
$reportId   = $request->getGet('report');
$isPrinting = ! empty($reportId) && isset($reportData);
?>

<?php
/** @var array $currentDoctor */
$loggedInDoctorName = trim(($currentDoctor['first_name'] ?? '') . ' ' . ($currentDoctor['last_name'] ?? ''));
$loggedInDoctorId   = (int) ($currentDoctor['doctor_id'] ?? 0);
?>

<?php if (! $isPrinting): ?>
    <!-- Sidebar -->
    <?= view('partials/sidenav') ?>
    <main class="dashboard-main">
        <?= view('partials/topbar') ?>

        <div class="dashboard-main-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h4 class="mb-0">Manage Visits</h4>
                <nav class="small text-muted">
                    <a href="<?= base_url('dashboard') ?>" class="text-muted text-decoration-none">Dashboard</a>
                    <span class="mx-1">•</span>
                    <span>Manage Visits</span>
                </nav>
            </div>

            <!-- Success Flash -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>


            <div class="card basic-data-table">
                <div class="card shadow-sm mb-3 border-0 filters-card">
                    <form method="get" action="<?= current_url() ?>" class="card-body py-3">
                        <div class="row g-2 align-items-end"><!-- g-2 tightens column gaps -->

                            <!-- Patient -->
                            <div class="col-12 col-md-4 col-lg-3">
                                <label for="filterPatient" class="form-label">Patient</label>
                                <div class="input-group">
                                    <span class="input-group-text"><iconify-icon icon="mdi:account-outline"></iconify-icon></span>
                                    <select name="patient_id" id="filterPatient" class="form-select">
                                        <option value="">All Patients</option>
                                        <?php foreach ($patients as $p): ?>
                                            <option value="<?= esc($p['patient_id']) ?>"
                                                <?= (string)($filters['patient_id'] ?? '') === (string)$p['patient_id'] ? 'selected' : '' ?>>
                                                <?= esc($p['first_name'] . ' ' . $p['last_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- From -->
                            <div class="col-6 col-md-3 col-lg-2">
                                <label for="filterDateFrom" class="form-label">From</label>
                                <div class="input-group">
                                    <span class="input-group-text"><iconify-icon icon="mdi:calendar-outline"></iconify-icon></span>
                                    <input type="date" name="date_from" id="filterDateFrom" value="<?= esc($filters['date_from'] ?? '') ?>" class="form-control">
                                </div>
                            </div>

                            <!-- To -->
                            <div class="col-6 col-md-3 col-lg-2">
                                <label for="filterDateTo" class="form-label">To</label>
                                <div class="input-group">
                                    <span class="input-group-text"><iconify-icon icon="mdi:calendar-outline"></iconify-icon></span>
                                    <input type="date" name="date_to" id="filterDateTo" value="<?= esc($filters['date_to'] ?? '') ?>" class="form-control">
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="col-12 col-md-4 col-lg-2">
                                <label for="filterCategory" class="form-label">Category</label>
                                <div class="input-group">
                                    <span class="input-group-text"><iconify-icon icon="mdi:label-outline"></iconify-icon></span>
                                    <select name="category" id="filterCategory" class="form-select">
                                        <option value="">All Categories</option>
                                        <option value="in-patient" <?= ($filters['category'] ?? '') === 'in-patient'  ? 'selected' : '' ?>>In-Patient</option>
                                        <option value="out-patient" <?= ($filters['category'] ?? '') === 'out-patient' ? 'selected' : '' ?>>Out-Patient</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Actions (remove ms-auto so buttons sit right after Category) -->
                            <div class="col-auto d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-dark">Apply</button>
                                <a href="<?= base_url('visits') ?>" class="btn btn-primary">Clear</a>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVisitModal">New Visit</button>
                            </div>

                        </div>
                    </form>
                </div>



                <div class="card-body">
                    <!-- VISITS TABLE -->
                    <table class="table bordered-table mb-0">
                        <thead>
                            <tr>
                                <th class="px-3 py-2">Patient</th>
                                <th class="px-3 py-2">Doctor</th>
                                <th class="px-3 py-2">Visit Date</th>
                                <th class="px-3 py-2">Weight (kg)</th>
                                <th class="px-3 py-2 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($visits as $v): ?>
                                <tr>
                                    <td class="px-3 py-2"><?= esc($v['patient_name']) ?></td>
                                    <td class="px-3 py-2"><?= esc($v['doctor_name']) ?></td>
                                    <td class="px-3 py-2"><?= esc(date('Y-m-d', strtotime($v['visit_date']))) ?></td>
                                    <td class="px-3 py-2"><?= esc($v['weight']) ?></td>
                                    <td class="px-3 py-2 text-center">
                                        <div class="d-inline-flex gap-1">
                                            <button type="button" class="btn btn-sm btn-primary view-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#visitUnifiedModal"
                                                data-id="<?= esc($v['visit_id'] ?? '') ?>"
                                                data-fetch-url="<?= base_url('visits/details/' . ($v['visit_id'] ?? '')) ?>"
                                                data-patient="<?= esc($v['patient_name'] ?? '') ?>"
                                                data-doctor="<?= esc($v['doctor_name'] ?? '') ?>"
                                                data-date="<?= esc($v['visit_date'] ?? '') ?>"
                                                data-weight="<?= esc($v['weight'] ?? '') ?>"
                                                data-bp="<?= esc($v['blood_pressure'] ?? '') ?>"
                                                data-pulse="<?= esc($v['pulse'] ?? '') ?>"
                                                data-temp="<?= esc($v['temperature'] ?? '') ?>"
                                                data-spo2="<?= esc($v['sp02'] ?? '') ?>"
                                                data-respiration="<?= esc($v['respiration_rate'] ?? '') ?>"
                                                data-category="<?= esc($v['visit_category'] ?? '') ?>"
                                                data-admission="<?= esc($v['admission_time'] ?? '') ?>"
                                                data-patient-complaints="<?= esc($v['patient_complaints'] ?? '') ?>"
                                                data-examination-notes="<?= esc($v['examination_notes'] ?? '') ?>"
                                                data-investigations="<?= esc($v['investigations'] ?? '') ?>"
                                                data-diagnosis="<?= esc($v['diagnosis'] ?? '') ?>">
                                                View
                                            </button>

                                            <!-- Edit / Delete Dropdown -->
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light icon-only"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <iconify-icon icon="mdi:dots-vertical"></iconify-icon>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                    <li class="dropdown-header small text-muted">Actions</li>
                                                    <li>
                                                        <button class="dropdown-item edit-visit-btn d-flex align-items-center gap-2"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editVisitModal"
                                                            data-id="<?= $v['visit_id'] ?>"
                                                            data-patient_id="<?= $v['patient_id'] ?>"
                                                            data-date="<?= esc($v['visit_date']) ?>"
                                                            data-weight="<?= esc($v['weight']) ?>"
                                                            data-bp="<?= esc($v['blood_pressure']) ?>"
                                                            data-pulse="<?= esc($v['pulse']) ?>"
                                                            data-temp="<?= esc($v['temperature']) ?>"
                                                            data-spo2="<?= esc($v['sp02']) ?>"
                                                            data-respiration="<?= esc($v['respiration_rate']) ?>"
                                                            data-category="<?= esc($v['visit_category']) ?>"
                                                            data-admission="<?= esc($v['admission_time']) ?>"
                                                            data-patient_complaints="<?= esc($v['patient_complaints']) ?>"
                                                            data-examination_notes="<?= esc($v['examination_notes']) ?>"
                                                            data-investigations="<?= esc($v['investigations']) ?>"
                                                            data-diagnosis="<?= esc($v['diagnosis']) ?>">
                                                            <iconify-icon icon="mdi:pencil-outline"></iconify-icon>
                                                            Edit Visit
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item edit-details-btn d-flex align-items-center gap-2"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#visitDetailsModal"
                                                            data-visit-id="<?= $v['visit_id'] ?>">
                                                            <iconify-icon icon="mdi:clipboard-text-outline"></iconify-icon>
                                                            Treatment & Outcomes
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item edit-visit-details-btn d-flex align-items-center gap-2"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#editVisitDetailsModal"
                                                            data-visit-id="<?= $v['visit_id'] ?>">
                                                            <iconify-icon icon="mdi:clipboard-edit-outline"></iconify-icon>
                                                            Edit Visit Details
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a
                                                            class="dropdown-item text-primary report-btn d-flex align-items-center gap-2"
                                                            href="<?= base_url('visits') ?>?report=<?= $v['visit_id'] ?>">
                                                            <iconify-icon icon="mdi:printer" class="text-dark"></iconify-icon>
                                                            <span>Report</span>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <button class="dropdown-item text-danger delete-btn d-flex align-items-center gap-2"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteVisitModal"
                                                            data-id="<?= $v['visit_id'] ?>"
                                                            data-patient="<?= esc($v['patient_name']) ?>">
                                                            <iconify-icon icon="mdi:trash-can-outline"></iconify-icon>
                                                            Delete Visit
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?php
                    // Only if pager exists (list mode)
                    if (isset($pager) && $pager):
                        $details   = $pager->getDetails('visits'); // ['currentPage','perPage','total','pageCount']
                        $current   = (int)($details['currentPage'] ?? 1);
                        $pageCount = max(1, (int)($details['pageCount'] ?? 1));

                        $pad = strlen((string)$pageCount);
                        $fmt = fn($n) => str_pad((string)$n, $pad, '0', STR_PAD_LEFT);

                        $hasPrev = $current > 1;
                        $hasNext = $current < $pageCount;

                        $prevUri = $hasPrev ? $pager->getPreviousPageURI('visits') : '#';
                        $nextUri = $hasNext ? $pager->getNextPageURI('visits')     : '#';

                        $rq = \Config\Services::request();
                        // Use the perPage the controller sent down (fallback to details/perPage)
                        $perPage = (int)($perPage ?? ($details['perPage'] ?? 10));
                    ?>
                        <?php if ($pageCount > 1): ?>
                            <div class="card-footer d-flex align-items-center justify-content-between flex-wrap gap-3">
                                <style>
                                    .page-chip {
                                        background: var(--bs-light);
                                        border-radius: 999px;
                                        padding: .35rem .75rem;
                                        display: inline-flex;
                                        align-items: center;
                                        gap: .35rem;
                                        font-weight: 600;
                                        box-shadow: 0 1px 1px rgba(0, 0, 0, .04) inset
                                    }

                                    .page-chip .current {
                                        color: var(--bs-success);
                                        font-variant-numeric: tabular-nums
                                    }

                                    .page-chip .total,
                                    .page-chip .slash {
                                        color: #6c757d;
                                        font-variant-numeric: tabular-nums
                                    }

                                    .page-nav .btn-icon {
                                        width: 36px;
                                        height: 36px;
                                        display: inline-flex;
                                        align-items: center;
                                        justify-content: center;
                                        border-radius: 50%;
                                        padding: 0
                                    }

                                    .page-nav .btn-icon.disabled,
                                    .page-nav .btn-icon:disabled {
                                        pointer-events: none;
                                        opacity: .5
                                    }
                                </style>

                                <div class="page-chip">
                                    <span class="current"><?= $fmt($current) ?></span>
                                    <span class="slash">/</span>
                                    <span class="total"><?= $fmt($pageCount) ?></span>
                                </div>

                                <nav class="page-nav d-flex align-items-center gap-2" aria-label="Visits pagination">
                                    <a class="btn btn-light btn-icon<?= $hasPrev ? '' : ' disabled' ?>"
                                        href="<?= esc($prevUri) ?>" <?= $hasPrev ? 'rel="prev"' : 'aria-disabled="true" tabindex="-1"' ?>
                                        aria-label="Previous page"><span aria-hidden="true">&lsaquo;</span></a>

                                    <a class="btn btn-light btn-icon<?= $hasNext ? '' : ' disabled' ?>"
                                        href="<?= esc($nextUri) ?>" <?= $hasNext ? 'rel="next"' : 'aria-disabled="true" tabindex="-1"' ?>
                                        aria-label="Next page"><span aria-hidden="true">&rsaquo;</span></a>
                                </nav>

                                <!-- Rows per page -->
                                <form method="get" class="d-flex align-items-center gap-2 ms-auto">
                                    <?php
                                    // Preserve current filters, but reset pagination params so we start from page 1
                                    $skipKeys = ['per_page', 'page', 'page_visits'];
                                    foreach ($rq->getGet() as $k => $v):
                                        if (in_array($k, $skipKeys, true)) continue;
                                    ?>
                                        <input type="hidden" name="<?= esc($k) ?>" value="<?= esc(is_array($v) ? implode(',', $v) : $v) ?>">
                                    <?php endforeach; ?>

                                    <label class="text-muted small">Rows</label>
                                    <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <?php foreach ([10, 25, 50, 100] as $opt): ?>
                                            <option value="<?= $opt ?>" <?= ($perPage === $opt) ? 'selected' : '' ?>><?= $opt ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                </div>
            </div>

            <!-- Your existing modals, JS & @media print styles all remain exactly as they are… -->

            <?php /* =====================  MODALS (Bootstrap-only)  ===================== */ ?>
            <!-- ADD VISIT MODAL -->
            <div class="modal fade" id="addVisitModal"
                tabindex="-1"
                aria-labelledby="addVisitLabel"
                aria-hidden="true"
                data-bs-backdrop="static"
                data-bs-keyboard="false"
                data-bs-focus="false">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                        <form action="<?= base_url('visits/store') ?>" method="post" class="needs-validation" novalidate autocomplete="off">
                            <?= csrf_field() ?>

                            <!-- Header -->
                            <div class="modal-header border-0 pb-0 align-items-start">
                                <div>
                                    <h5 class="modal-title fw-semibold" id="addVisitLabel">Add Visit</h5>
                                    <small class="text-muted">Fill in basic info, vitals & assessment</small>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <!-- Stepper -->
                            <div class="px-4 pt-3">
                                <div class="progress" style="height: 4px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 33%"></div>
                                </div>
                                <ul class="nav nav-pills nav-fill gap-2 my-3" id="visitSteps" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active rounded-pill" id="step1-tab" data-bs-toggle="pill" data-bs-target="#step1" type="button" role="tab">
                                            <span class="badge bg-primary rounded-circle me-2">1</span> Basic Info
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link rounded-pill" id="step2-tab" data-bs-toggle="pill" data-bs-target="#step2" type="button" role="tab">
                                            <span class="badge bg-primary rounded-circle me-2">2</span> Vitals
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link rounded-pill" id="step3-tab" data-bs-toggle="pill" data-bs-target="#step3" type="button" role="tab">
                                            <span class="badge bg-primary rounded-circle me-2">3</span> Assessment
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <!-- Body -->
                            <div class="modal-body pt-0">
                                <div class="tab-content" id="visitStepsContent">

                                    <!-- STEP 1 -->
                                    <div class="tab-pane fade show active" id="step1" role="tabpanel" aria-labelledby="step1-tab">
                                        <div class="card border-0 shadow-sm mb-3">
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <!-- Patient -->
                                                    <div class="col-md-6">
                                                        <label for="addPatientId" class="form-label small text-muted mb-1">Patient *</label>
                                                        <select id="addPatientId" name="patient_id" class="form-select" required>
                                                            <option value=""></option>
                                                            <?php foreach ($patients as $p): ?>
                                                                <option value="<?= $p['patient_id'] ?>">
                                                                    <?= esc($p['first_name'] . ' ' . $p['last_name']) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                        <?= validation_show_error('patient_id', '<div class="text-danger small mt-1">', '</div>') ?>
                                                    </div>

                                                    <!-- Doctor (read-only) -->
                                                    <div class="col-md-6">
                                                        <label class="form-label small text-muted mb-1">Doctor</label>
                                                        <input type="text" class="form-control" value="<?= esc($loggedInDoctorName) ?>" disabled>
                                                        <input type="hidden" name="doctor_id" value="<?= $loggedInDoctorId ?>"><!-- server also enforces -->
                                                        <div class="form-text">This visit will be recorded under your account.</div>
                                                    </div>

                                                    <!-- Date -->
                                                    <div class="col-md-6">
                                                        <label for="addVisitDate" class="form-label small text-muted mb-1">Visit Date *</label>
                                                        <input id="addVisitDate" type="date" name="visit_date" class="form-control" required>
                                                        <?= validation_show_error('visit_date', '<div class="text-danger small mt-1">', '</div>') ?>
                                                    </div>

                                                    <!-- Category -->
                                                    <div class="col-md-6">
                                                        <label for="addVisitCategory" class="form-label small text-muted mb-1">Category *</label>
                                                        <select id="addVisitCategory" name="visit_category" class="form-select" required>
                                                            <option value=""></option>
                                                            <option value="in-patient">In-Patient</option>
                                                            <option value="out-patient">Out-Patient</option>
                                                        </select>
                                                        <?= validation_show_error('visit_category', '<div class="text-danger small mt-1">', '</div>') ?>
                                                    </div>

                                                    <!-- Admission (conditional) -->
                                                    <div class="col-md-6 d-none" id="addAdmissionContainer">
                                                        <label for="addAdmissionTime" class="form-label small text-muted mb-1">Admission Time</label>
                                                        <input id="addAdmissionTime" type="datetime-local" name="admission_time" class="form-control">
                                                        <?= validation_show_error('admission_time', '<div class="text-danger small mt-1">', '</div>') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- STEP 2 -->
                                    <div class="tab-pane fade" id="step2" role="tabpanel" aria-labelledby="step2-tab">
                                        <div class="card border-0 shadow-sm mb-3">
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-sm-6 col-md-4">
                                                        <label for="weight" class="form-label small text-muted mb-1">Weight (kg)</label>
                                                        <input id="weight" name="weight" type="number" step="0.1" class="form-control" inputmode="decimal">
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <label for="blood_pressure" class="form-label small text-muted mb-1">Blood Pressure</label>
                                                        <input id="blood_pressure" name="blood_pressure" type="text" class="form-control" placeholder="e.g. 120/80">
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <label for="pulse" class="form-label small text-muted mb-1">Pulse</label>
                                                        <input id="pulse" name="pulse" type="number" class="form-control" inputmode="numeric">
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <label for="temperature" class="form-label small text-muted mb-1">Temperature (°C)</label>
                                                        <input id="temperature" name="temperature" type="number" step="0.1" class="form-control" inputmode="decimal">
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <label for="sp02" class="form-label small text-muted mb-1">SpO₂ (%)</label>
                                                        <input id="sp02" name="sp02" type="number" step="0.1" class="form-control" min="0" max="100" inputmode="decimal">
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <label for="respiration_rate" class="form-label small text-muted mb-1">Respiration Rate</label>
                                                        <input id="respiration_rate" name="respiration_rate" type="number" class="form-control" inputmode="numeric">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- STEP 3 -->
                                    <div class="tab-pane fade" id="step3" role="tabpanel" aria-labelledby="step3-tab">
                                        <div class="card border-0 shadow-sm mb-2">
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label for="patient_complaints" class="form-label small text-muted mb-1">Patient Complaints</label>
                                                        <textarea id="patient_complaints" name="patient_complaints" class="form-control" rows="4"></textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="examination_notes" class="form-label small text-muted mb-1">Examination Notes</label>
                                                        <textarea id="examination_notes" name="examination_notes" class="form-control" rows="4"></textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="investigations" class="form-label small text-muted mb-1">Investigations</label>
                                                        <textarea id="investigations" name="investigations" class="form-control" rows="4"></textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="diagnosis" class="form-label small text-muted mb-1">Diagnosis</label>
                                                        <textarea id="diagnosis" name="diagnosis" class="form-control" rows="4"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div><!-- /.tab-content -->
                            </div><!-- /.modal-body -->

                            <!-- Footer (persistent controls) -->
                            <div class="modal-footer border-0 bg-white sticky-bottom">
                                <button type="button" class="btn btn-outline-secondary me-auto wizard-prev d-none">Back</button>
                                <button type="button" class="btn btn-primary wizard-next">Next</button>
                                <button type="submit" class="btn btn-success d-none wizard-submit">Save Visit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- EDIT VISIT MODAL -->
            <div class="modal fade" id="editVisitModal"
                tabindex="-1"
                aria-labelledby="editVisitLabel"
                aria-hidden="true"
                data-bs-backdrop="static"
                data-bs-keyboard="false"
                data-bs-focus="false">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                        <form id="editVisitForm" method="post" class="needs-validation" novalidate autocomplete="off">
                            <?= csrf_field() ?>
                            <input type="hidden" name="visit_id" id="editVisitId">

                            <!-- Header -->
                            <div class="modal-header border-0 pb-0 align-items-start">
                                <div>
                                    <h5 class="modal-title fw-semibold" id="editVisitLabel">Edit Visit</h5>
                                    <small class="text-muted">Update visit info, vitals & assessment</small>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <!-- Stepper -->
                            <div class="px-4 pt-3">
                                <div class="progress" style="height:4px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width:33%"></div>
                                </div>
                                <ul class="nav nav-pills nav-fill gap-2 my-3" id="editVisitSteps" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active rounded-pill" data-bs-toggle="pill" data-bs-target="#editStep1" type="button" role="tab">
                                            <span class="badge bg-primary rounded-circle me-2">1</span> Info
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link rounded-pill" data-bs-toggle="pill" data-bs-target="#editStep2" type="button" role="tab">
                                            <span class="badge bg-primary rounded-circle me-2">2</span> Vitals
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link rounded-pill" data-bs-toggle="pill" data-bs-target="#editStep3" type="button" role="tab">
                                            <span class="badge bg-primary rounded-circle me-2">3</span> Assessment
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <!-- Body -->
                            <div class="modal-body pt-0">
                                <div class="tab-content">

                                    <!-- STEP 1 -->
                                    <div class="tab-pane fade show active" id="editStep1" role="tabpanel">
                                        <div class="card border-0 shadow-sm mb-3">
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label for="editPatientId" class="form-label small text-muted mb-1">Patient *</label>
                                                        <select id="editPatientId" name="patient_id" class="form-select" required>
                                                            <option value=""></option>
                                                            <?php foreach ($patients as $patient): ?>
                                                                <option value="<?= esc($patient['patient_id']) ?>">
                                                                    <?= esc($patient['first_name'] . ' ' . $patient['last_name']) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label small text-muted mb-1">Doctor</label>
                                                        <input type="text" class="form-control" value="<?= esc($loggedInDoctorName) ?>" disabled>
                                                        <input type="hidden" name="doctor_id" value="<?= $loggedInDoctorId ?>">
                                                        <div class="form-text">This visit is recorded under your account.</div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="editVisitDate" class="form-label small text-muted mb-1">Visit Date *</label>
                                                        <input type="date" id="editVisitDate" name="visit_date" class="form-control" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="editVisitCategory" class="form-label small text-muted mb-1">Category *</label>
                                                        <select id="editVisitCategory" name="visit_category" class="form-select" required>
                                                            <option value="in-patient">In-Patient</option>
                                                            <option value="out-patient">Out-Patient</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6 d-none" id="editAdmissionFields">
                                                        <label for="editAdmissionTime" class="form-label small text-muted mb-1">Admission Time</label>
                                                        <input type="datetime-local" id="editAdmissionTime" name="admission_time" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- STEP 2 -->
                                    <div class="tab-pane fade" id="editStep2" role="tabpanel">
                                        <div class="card border-0 shadow-sm mb-3">
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-sm-6 col-md-4">
                                                        <label for="editWeight" class="form-label small text-muted mb-1">Weight (kg) *</label>
                                                        <input type="number" step="0.1" id="editWeight" name="weight" class="form-control" inputmode="decimal" required>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <label for="editBP" class="form-label small text-muted mb-1">Blood Pressure</label>
                                                        <input type="text" id="editBP" name="blood_pressure" class="form-control" placeholder="e.g. 120/80">
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <label for="editPulse" class="form-label small text-muted mb-1">Pulse</label>
                                                        <input type="number" id="editPulse" name="pulse" class="form-control" inputmode="numeric">
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <label for="editTemp" class="form-label small text-muted mb-1">Temperature (°C)</label>
                                                        <input type="number" step="0.1" id="editTemp" name="temperature" class="form-control" inputmode="decimal">
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <label for="editSpO2" class="form-label small text-muted mb-1">SpO₂ (%)</label>
                                                        <input type="number" step="0.1" id="editSpO2" name="sp02" class="form-control" min="0" max="100" inputmode="decimal">
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <label for="editRespiration" class="form-label small text-muted mb-1">Respiration Rate</label>
                                                        <input type="number" id="editRespiration" name="respiration_rate" class="form-control" inputmode="numeric">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- STEP 3 -->
                                    <div class="tab-pane fade" id="editStep3" role="tabpanel">
                                        <div class="card border-0 shadow-sm mb-2">
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label for="editComplaints" class="form-label small text-muted mb-1">Patient Complaints *</label>
                                                        <textarea id="editComplaints" name="patient_complaints" class="form-control" rows="4" required></textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="editExamination" class="form-label small text-muted mb-1">Examination Notes</label>
                                                        <textarea id="editExamination" name="examination_notes" class="form-control" rows="4"></textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="editInvestigations" class="form-label small text-muted mb-1">Investigations</label>
                                                        <textarea id="editInvestigations" name="investigations" class="form-control" rows="4"></textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="editDiagnosis" class="form-label small text-muted mb-1">Diagnosis *</label>
                                                        <textarea id="editDiagnosis" name="diagnosis" class="form-control" rows="4" required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div><!-- /.tab-content -->
                            </div><!-- /.modal-body -->

                            <!-- Footer -->
                            <div class="modal-footer border-0 bg-white sticky-bottom">
                                <button type="button" class="btn btn-outline-secondary me-auto wizard-prev d-none">Back</button>
                                <button type="button" class="btn btn-primary wizard-next">Next</button>
                                <button type="submit" class="btn btn-success d-none wizard-submit">Update Visit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- UNIFIED VISIT VIEW MODAL (Pinterest-inspired) -->
            <div class="modal fade" id="visitUnifiedModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content vu-modal">

                        <!-- Gradient header -->
                        <div class="vu-header">
                            <div class="d-flex align-items-center gap-3">
                                <div class="vu-avatar" id="uAvatar">🩺</div>
                                <div>
                                    <h5 class="fw-semibold mb-0">Visit Summary & Actions</h5>
                                    <small class="text-muted">Quick glance, details & follow-up</small>
                                </div>
                            </div>
                            <button type="button" class="btn btn-light btn-sm rounded-pill btn-icon" data-bs-dismiss="modal">
                                <iconify-icon icon="mdi:close"></iconify-icon> Close
                            </button>
                        </div>

                        <!-- Segmented tabs (sticky) -->
                        <div class="vu-tabs">
                            <ul class="nav nav-pills" id="visitTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overviewTab" type="button" role="tab">Overview</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="prescriptions-tab-vu" data-bs-toggle="tab" data-bs-target="#prescriptionsTab" type="button" role="tab">Prescriptions</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="supplies-tab-vu" data-bs-toggle="tab" data-bs-target="#suppliesTab" type="button" role="tab">Supplies</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="outcomes-tab-vu" data-bs-toggle="tab" data-bs-target="#outcomesTab" type="button" role="tab">Outcome</button>
                                </li>
                            </ul>
                        </div>

                        <!-- Body -->
                        <div class="modal-body vu-body">
                            <div class="tab-content" id="visitTabsContent">

                                <!-- OVERVIEW -->
                                <div class="tab-pane fade show active" id="overviewTab" role="tabpanel">
                                    <div class="vu-card mb-3">
                                        <div class="vu-card-body">
                                            <!-- key values -->
                                            <div class="vu-kv">
                                                <div class="kv">
                                                    <div class="label">Patient</div>
                                                    <div class="value" id="uPatient"></div>
                                                </div>
                                                <div class="kv">
                                                    <div class="label">Doctor</div>
                                                    <div class="value" id="uDoctor"></div>
                                                </div>
                                                <div class="kv">
                                                    <div class="label">Category</div>
                                                    <div class="value" id="uCategory"></div>
                                                </div>
                                                <div class="kv">
                                                    <div class="label">Admission</div>
                                                    <div class="value" id="uAdmission"></div>
                                                </div>
                                            </div>

                                            <!-- vitals chips -->
                                            <div class="vu-chips">
                                                <span class="vu-chip"><iconify-icon icon="mdi:weight-kilogram"></iconify-icon> Weight: <span id="uWeight"></span> kg</span>
                                                <span class="vu-chip"><iconify-icon icon="mdi:heart-pulse"></iconify-icon> BP: <span id="uBP"></span></span>
                                                <span class="vu-chip"><iconify-icon icon="mdi:pulse"></iconify-icon> Pulse: <span id="uPulse"></span> bpm</span>
                                                <span class="vu-chip"><iconify-icon icon="mdi:thermometer"></iconify-icon> Temp: <span id="uTemp"></span> °C</span>
                                                <span class="vu-chip"><iconify-icon icon="mdi:water-percent"></iconify-icon> SpO₂: <span id="uSpO2"></span> %</span>
                                                <span class="vu-chip"><iconify-icon icon="mdi:lungs"></iconify-icon> Resp: <span id="uRespiration"></span> bpm</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- narrative tiles -->
                                    <div class="vu-grid">
                                        <div class="vu-card">
                                            <div class="vu-card-body">
                                                <h6>Complaints</h6>
                                                <p id="uComplaints" class="mb-0 text-secondary"></p>
                                            </div>
                                        </div>
                                        <div class="vu-card">
                                            <div class="vu-card-body">
                                                <h6>Examination Notes</h6>
                                                <p id="uExamination" class="mb-0 text-secondary"></p>
                                            </div>
                                        </div>
                                        <div class="vu-card">
                                            <div class="vu-card-body">
                                                <h6>Investigations</h6>
                                                <p id="uInvestigations" class="mb-0 text-secondary"></p>
                                            </div>
                                        </div>
                                        <div class="vu-card">
                                            <div class="vu-card-body">
                                                <h6>Diagnosis</h6>
                                                <p id="uDiagnosis" class="mb-0 text-secondary"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- PRESCRIPTIONS -->
                                <div class="tab-pane fade" id="prescriptionsTab" role="tabpanel">
                                    <div class="vu-card vu-list">
                                        <div class="vu-card-body p-0">
                                            <ul id="uPrescriptions" class="list-group list-group-flush"></ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- SUPPLIES -->
                                <div class="tab-pane fade" id="suppliesTab" role="tabpanel">
                                    <div class="vu-card vu-list">
                                        <div class="vu-card-body p-0">
                                            <ul id="uSupplies" class="list-group list-group-flush"></ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- OUTCOME -->
                                <div class="tab-pane fade" id="outcomesTab" role="tabpanel">
                                    <div class="vu-card">
                                        <div class="vu-card-body">
                                            <p class="mb-2"><strong>Outcome:</strong> <span id="uOutcome"></span></p>
                                            <p class="mb-2"><strong>Treatment Notes:</strong> <span id="uTreatmentNotes"></span></p>
                                            <p class="mb-2"><strong>Discharge Time:</strong> <span id="uDischargeTime"></span></p>
                                            <p class="mb-0"><strong>Referral Reason:</strong> <span id="uReferralReason"></span></p>
                                        </div>
                                    </div>
                                </div>

                            </div><!-- /tab-content -->
                        </div><!-- /body -->
                    </div>
                </div>
            </div>

            <!-- DELETE VISIT MODAL -->
            <div class="modal fade" id="deleteVisitModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="<?= base_url('visits/delete') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="visit_id" id="deleteVisitId">
                        <div class="modal-content border-0 shadow rounded-4">
                            <div class="modal-header">
                                <h5 class="modal-title">Delete Visit</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>
                                    Are you sure you want to delete the visit for
                                    <strong id="deletePatientName">____</strong>?
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- VISIT DETAILS MODAL -->
            <div class="modal fade" id="visitDetailsModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-semibold">Visit Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body pt-3">
                            <ul class="nav nav-tabs mb-3" role="tablist">
                                <li class="nav-item"><button class="nav-link active" id="vd-presc-tab" data-bs-toggle="tab" data-bs-target="#vd-presc" type="button" role="tab">Prescriptions</button></li>
                                <li class="nav-item"><button class="nav-link" id="vd-supp-tab" data-bs-toggle="tab" data-bs-target="#vd-supp" type="button" role="tab">Supplies</button></li>
                                <li class="nav-item"><button class="nav-link" id="vd-out-tab" data-bs-toggle="tab" data-bs-target="#vd-out" type="button" role="tab">Outcomes</button></li>
                            </ul>

                            <div class="tab-content">
                                <!-- Prescriptions -->
                                <div class="tab-pane fade show active" id="vd-presc" role="tabpanel">
                                    <?php foreach ($prescriptions as $p): ?>
                                        <div class="card mb-2" id="prescription-card-<?= $p['prescription_id'] ?>">
                                            <div class="card-body">
                                                <strong><?= esc($p['drug_name']) ?></strong><br>
                                                Dosage: <?= esc($p['dosage']) ?> | Qty: <?= esc($p['quantity']) ?><br>
                                                Duration: <?= esc($p['duration']) ?> days | Route: <?= esc($p['route']) ?><br>
                                                <?= $p['instructions'] ? "Instructions: " . esc($p['instructions']) : '' ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                    <?= view('partials/forms/add_prescription_form') ?>
                                </div>

                                <!-- Supplies -->
                                <div class="tab-pane fade" id="vd-supp" role="tabpanel">
                                    <?php foreach ($supplies_used as $s): ?>
                                        <div class="card mb-2" id="supply-card-<?= $s['supply_usage_id'] ?>">
                                            <div class="card-body">
                                                <strong><?= esc($s['supply_name']) ?></strong><br>
                                                Quantity: <?= esc($s['quantity_used']) ?> | Type: <?= esc($s['usage_type']) ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                    <?= view('partials/forms/add_supply_form') ?>
                                </div>

                                <!-- Outcomes -->
                                <div class="tab-pane fade" id="vd-out" role="tabpanel">
                                    <?php if ($outcome): ?>
                                        <div class="card mb-2" id="outcome-card-<?= $outcome['outcome_id'] ?>">
                                            <div class="card-body">
                                                <strong>Outcome:</strong> <?= esc($outcome['summary']) ?><br>
                                                <strong>Treatment:</strong> <?= esc($outcome['treatment']) ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <?= view('partials/forms/add_outcome_form') ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- EDIT VISIT DETAILS MODAL -->
            <div class="modal fade" id="editVisitDetailsModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-semibold">Edit Visit Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body pt-3">
                            <ul class="nav nav-tabs mb-3" role="tablist">
                                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#edit-prescriptions" type="button">Prescriptions</button></li>
                                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#edit-supplies" type="button">Supplies</button></li>
                                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#edit-outcomes" type="button">Outcomes</button></li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="edit-prescriptions">
                                    <div id="edit-prescription-body">
                                        <?php foreach ($prescriptions as $p): ?>
                                            <?= view('partials/cards/prescription_card', ['p' => $p, 'drugs' => $drugs]) ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="edit-supplies">
                                    <div id="edit-supply-body">
                                        <?php foreach ($supplies_used as $s): ?>
                                            <?= view('partials/cards/supply_card', ['supply' => $s, 'supplies' => $supplies]) ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="edit-outcomes">
                                    <div id="edit-outcome-body">
                                        <?php if ($outcome): ?>
                                            <?= view('partials/cards/outcome_card', ['outcome' => $outcome]) ?>
                                        <?php else: ?>
                                            <div class="alert alert-warning">No outcome data to edit.</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div><!-- /tab-content -->
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

<?php else: ?>
    <?php if ($reportId && $reportData): ?>
        <div class="report-shell print-report">

            <!-- Toolbar (screen only) -->
            <div class="d-flex justify-content-between align-items-center mb-3 d-print-none report-toolbar">
                <button class="btn btn-light" onclick="history.back()">← Back</button>
                <button class="btn btn-primary" onclick="window.print()">Print</button>
            </div>

            <!-- Header -->
            <section class="report-header">
                <div class="report-brand text-center">
                    <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo">
                </div>
                <div class="report-id">
                    <div class="report-avatar d-flex justify-content-center" id="rAvatar">🩺</div>
                    <div>
                        <div class="fw-bold">
                            <?= esc(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? '')) ?>
                        </div>
                        <div class="text-muted small">
                            <strong>Doctor:</strong> <?= esc($visit['doctor_name'] ?? '–') ?>
                        </div>
                        <div class="text-muted small">
                            <strong>DOB:</strong> <?= esc($patient['dob'] ?? $patient['date_of_birth'] ?? '–') ?>
                        </div>
                        <div class="report-chips">
                            <?php if (!empty($visit['visit_category'])): ?>
                                <span class="report-chip"><iconify-icon icon="mdi:label-outline"></iconify-icon><?= esc($visit['visit_category']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($patient['phone'] ?? $patient['contact_info'])): ?>
                                <span class="report-chip"><iconify-icon icon="mdi:phone"></iconify-icon><?= esc($patient['phone'] ?? $patient['contact_info']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="report-meta">
                    <div><strong>Visit Date:</strong> <?= esc($visit['visit_date'] ?? '–') ?></div>
                    <?php if (!empty($visit['admission_time'])): ?>
                        <div><strong>Admission:</strong> <?= esc($visit['admission_time']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($outcome['follow_up_date'])): ?>
                        <div><strong>Follow-up:</strong> <?= esc($outcome['follow_up_date']) ?></div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Vitals (chips row inside a card) -->
            <section class="report-card mt-2">
                <div class="body">
                    <h6 class="report-title">Vitals</h6>
                    <div class="report-chips">
                        <span class="report-chip"><iconify-icon icon="mdi:weight-kilogram"></iconify-icon><?= esc($vitals['weight'] ?? '–') ?> kg</span>
                        <span class="report-chip"><iconify-icon icon="mdi:heart-pulse"></iconify-icon><?= esc($vitals['bp'] ?? '–') ?></span>
                        <span class="report-chip"><iconify-icon icon="mdi:pulse"></iconify-icon><?= esc($vitals['pulse'] ?? '–') ?> bpm</span>
                        <span class="report-chip"><iconify-icon icon="mdi:thermometer"></iconify-icon><?= esc($vitals['temp'] ?? '–') ?> °C</span>
                        <span class="report-chip"><iconify-icon icon="mdi:water-percent"></iconify-icon><?= esc($vitals['sp02'] ?? '–') ?> %</span>
                        <span class="report-chip"><iconify-icon icon="mdi:lungs"></iconify-icon><?= esc($vitals['resp_rate'] ?? '–') ?> /min</span>
                    </div>
                </div>
            </section>

            <!-- Masonry-ish grid -->
            <section class="report-grid">

                <!-- Left column (narrative) -->
                <article class="report-card">
                    <div class="body">
                        <h6 class="report-title">Complaints</h6>
                        <?php if (!empty($complaints)): ?>
                            <ul class="report-list">
                                <?php foreach ($complaints as $c): ?>
                                    <li><?= esc($c) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="text-muted">None recorded.</div>
                        <?php endif; ?>
                    </div>
                </article>

                <article class="report-card">
                    <div class="body">
                        <h6 class="report-title">Examination Notes</h6>
                        <div><?= esc($visit['examination_notes'] ?? '—') ?></div>
                    </div>
                </article>

                <article class="report-card">
                    <div class="body">
                        <h6 class="report-title">Diagnosis</h6>
                        <?php if (!empty($diagnoses)): ?>
                            <ul class="report-list">
                                <?php foreach ($diagnoses as $d): ?>
                                    <li><?= esc($d) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="text-muted">None recorded.</div>
                        <?php endif; ?>
                    </div>
                </article>

                <article class="report-card">
                    <div class="body">
                        <h6 class="report-title">Investigations</h6>
                        <?php if (!empty($investigations)): ?>
                            <ul class="report-list">
                                <?php foreach ($investigations as $i): ?>
                                    <li><?= esc($i) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <div class="text-muted">None recorded.</div>
                        <?php endif; ?>
                    </div>
                </article>

                <!-- Right column (structured tables) -->
                <?php if (!empty($prescriptions)): ?>
                    <article class="report-card">
                        <div class="body">
                            <h6 class="report-title">Medications</h6>
                            <table class="report-table">
                                <tbody>
                                    <?php foreach ($prescriptions as $p): ?>
                                        <tr>
                                            <th><?= esc($p['drug']) ?></th>
                                            <td>
                                                Dosage: <?= esc($p['dosage']) ?> &nbsp;|&nbsp;
                                                Qty: <?= esc($p['quantity']) ?> &nbsp;|&nbsp;
                                                Duration: <?= esc($p['duration']) ?> days &nbsp;|&nbsp;
                                                Route: <?= esc($p['route']) ?>
                                                <?php if (!empty($p['instructions'])): ?>
                                                    <div class="text-muted small mt-1"><?= esc($p['instructions']) ?></div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </article>
                <?php endif; ?>

                <?php if (!empty($outcome)): ?>
                    <article class="report-card">
                        <div class="body">
                            <h6 class="report-title">Outcome</h6>
                            <table class="report-table">
                                <tbody>
                                    <?php foreach (['type', 'reason', 'condition', 'follow_up_date', 'notes'] as $f): ?>
                                        <?php if (!empty($outcome[$f])): ?>
                                            <tr>
                                                <th><?= esc(ucwords(str_replace('_', ' ', $f))) ?></th>
                                                <td><?= esc($outcome[$f]) ?></td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </article>
                <?php endif; ?>

            </section>
        </div>

        <!-- Avatar initial -->
        <script>
            (function() {
                const name = "<?= esc(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? '')) ?>".trim();
                document.getElementById('rAvatar').textContent = name ? name.charAt(0).toUpperCase() : '🩺';
            })();
        </script>
    <?php endif; ?>
<?php endif; ?>


<script>
    (() => {
        'use strict';

        /** ======================================
         * Tiny utilities
         * ====================================== */
        const qs = (s, r = document) => r.querySelector(s);
        const qsa = (s, r = document) => Array.from(r.querySelectorAll(s));
        const byId = (id) => document.getElementById(id);

        // Hide any open modal, show target, and scroll body to top
        const openModal = (id) => {
            qsa('.modal.show').forEach(m => bootstrap.Modal.getInstance(m)?.hide());
            const el = byId(id);
            if (!el) return;
            el.querySelector('.modal-body')?.scrollTo(0, 0);
            bootstrap.Modal.getOrCreateInstance(el).show();
        };

        // Read data-* with support for both hyphen and underscore conventions
        const getDataAttr = (el, logicalKey) => {
            if (!el) return '';
            const k = String(logicalKey);
            const hyphenAttr = 'data-' + k.replace(/_/g, '-');
            const underscoreAttr = 'data-' + k.replace(/-/g, '_');
            return el.getAttribute(hyphenAttr) ?? el.getAttribute(underscoreAttr) ?? '';
        };

        // Set text content with fallback
        const setText = (id, val) => {
            const el = byId(id);
            if (!el) return;
            const v = (val ?? '').toString().trim();
            el.textContent = v ? v : '–';
        };

        // Render a UL list from items using tpl(), show empty state when needed
        const renderList = (container, items, tpl, emptyText) => {
            if (!container) return;
            container.innerHTML = (items && items.length) ?
                items.map(tpl).join('') :
                `<li class="list-group-item text-muted">${emptyText}</li>`;
        };

        // CSRF refresh (for nested forms opened in modals)
        async function refreshCsrfToken() {
            try {
                const res = await fetch("<?= site_url('getCsrfToken') ?>");
                const data = await res.json();
                const name = "<?= csrf_token() ?>";
                qsa(`input[name="${name}"]`).forEach(el => el.value = data.token);
            } catch (e) {
                console.warn('CSRF refresh failed', e);
            }
        }

        /** ======================================
         * Wizards (add/edit)
         * ====================================== */
        function setupWizard(modalRoot) {
            if (!modalRoot) return;
            const tabs = qsa('[data-bs-toggle="pill"]', modalRoot);
            const bar = qs('.progress-bar', modalRoot);
            const prev = qs('.wizard-prev', modalRoot);
            const next = qs('.wizard-next', modalRoot);
            const submit = qs('.wizard-submit', modalRoot);

            const update = () => {
                const idx = tabs.findIndex(t => t.classList.contains('active'));
                const total = Math.max(1, tabs.length);
                if (bar) bar.style.width = `${((idx + 1) / total) * 100}%`;
                prev?.classList.toggle('d-none', idx === 0);
                next?.classList.toggle('d-none', idx === total - 1);
                submit?.classList.toggle('d-none', idx !== total - 1);
            };

            tabs.forEach(t => t.addEventListener('shown.bs.tab', update));
            prev?.addEventListener('click', () => {
                const idx = tabs.findIndex(t => t.classList.contains('active'));
                if (idx > 0) tabs[idx - 1].click();
            });
            next?.addEventListener('click', () => {
                const idx = tabs.findIndex(t => t.classList.contains('active'));
                if (idx < tabs.length - 1) tabs[idx + 1].click();
            });
            update();
        }

        const toggleAdmission = (selectEl, containerEl) => {
            if (!selectEl || !containerEl) return;
            containerEl.classList.toggle('d-none', selectEl.value !== 'in-patient');
        };

        function wireAddVisitModal() {
            const modal = byId('addVisitModal');
            if (!modal) return;

            modal.addEventListener('show.bs.modal', () => {
                // reset stepper
                qs('#step1-tab', modal)?.click();
                const bar = qs('.progress-bar', modal);
                if (bar) bar.style.width = '33%';
                qs('.wizard-prev', modal)?.classList.add('d-none');
                qs('.wizard-next', modal)?.classList.remove('d-none');
                qs('.wizard-submit', modal)?.classList.add('d-none');

                // admission toggle
                const cat = byId('addVisitCategory');
                const wrap = byId('addAdmissionContainer');
                toggleAdmission(cat, wrap);
                cat?.addEventListener('change', () => toggleAdmission(cat, wrap), {
                    once: true
                });
            });

            setupWizard(modal);
        }

        function wireEditVisitModal() {
            const modal = byId('editVisitModal');
            if (!modal) return;

            modal.addEventListener('show.bs.modal', (ev) => {
                const btn = ev.relatedTarget; // .edit-visit-btn
                const form = qs('form', modal);
                if (!btn || !form) return;

                // reset stepper
                qs('[data-bs-target="#editStep1"]', modal)?.click();
                const bar = qs('.progress-bar', modal);
                if (bar) bar.style.width = '33%';
                qs('.wizard-prev', modal)?.classList.add('d-none');
                qs('.wizard-next', modal)?.classList.remove('d-none');
                qs('.wizard-submit', modal)?.classList.add('d-none');

                // fill fields
                const set = (sel, val) => {
                    const el = qs(sel, form);
                    if (el) el.value = (val ?? '');
                };
                const id = getDataAttr(btn, 'id');

                form.action = `/visits/update/${id}`;
                set('#editVisitId', id);
                set('#editPatientId', getDataAttr(btn, 'patient_id'));
                set('#editVisitDate', (getDataAttr(btn, 'date') || '').split(' ')[0]);
                set('#editWeight', getDataAttr(btn, 'weight'));
                set('#editBP', getDataAttr(btn, 'bp'));
                set('#editPulse', getDataAttr(btn, 'pulse'));
                set('#editTemp', getDataAttr(btn, 'temp'));
                set('#editSpO2', getDataAttr(btn, 'spo2'));
                set('#editRespiration', getDataAttr(btn, 'respiration'));
                set('#editComplaints', getDataAttr(btn, 'patient_complaints'));
                set('#editExamination', getDataAttr(btn, 'examination_notes'));
                set('#editInvestigations', getDataAttr(btn, 'investigations'));
                set('#editDiagnosis', getDataAttr(btn, 'diagnosis'));

                const category = getDataAttr(btn, 'category') || '';
                set('#editVisitCategory', category);

                const admitWrap = byId('editAdmissionFields');
                toggleAdmission(qs('#editVisitCategory', form), admitWrap);
                if (category === 'in-patient') set('#editAdmissionTime', getDataAttr(btn, 'admission') || '');

                form.classList.remove('was-validated');
            });

            setupWizard(modal);
        }

        /** ======================================
         * Unified Visit View (Pinterest-style)
         * ====================================== */
        async function fetchVisitDetails(url) {
            if (!url) return {
                prescriptions: [],
                supplies: [],
                outcome: null
            };
            const res = await fetch(url, {
                headers: {
                    'Accept': 'application/json'
                }
            });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        }

        function fillOverviewFromButton(btn) {
            const map = {
                patient: 'uPatient',
                doctor: 'uDoctor',
                date: 'uDate', // ok if #uDate is absent
                category: 'uCategory',
                weight: 'uWeight',
                bp: 'uBP',
                pulse: 'uPulse',
                temp: 'uTemp',
                spo2: 'uSpO2',
                respiration: 'uRespiration',
                patient_complaints: 'uComplaints',
                examination_notes: 'uExamination',
                investigations: 'uInvestigations',
                diagnosis: 'uDiagnosis',
                admission: 'uAdmission'
            };
            Object.entries(map).forEach(([k, id]) => setText(id, getDataAttr(btn, k)));
        }

        function wireUnifiedViewModal() {
            const modal = byId('visitUnifiedModal');
            if (!modal) return;

            // Fill + fetch each time the modal is about to open
            modal.addEventListener('show.bs.modal', async (ev) => {
                const btn = ev.relatedTarget; // the .view-btn that opened the modal
                if (!btn) return;

                // 1) Fill from data-*
                fillOverviewFromButton(btn);

                // 2) Reset list sections
                const presEl = byId('uPrescriptions');
                const suppEl = byId('uSupplies');
                if (presEl) presEl.innerHTML = '<li class="list-group-item text-muted">Loading…</li>';
                if (suppEl) suppEl.innerHTML = '';
                ['uOutcome', 'uTreatmentNotes', 'uDischargeTime', 'uReferralReason'].forEach(id => setText(id, ''));

                // 3) Fetch JSON
                const url = getDataAttr(btn, 'fetch_url');
                try {
                    const data = await fetchVisitDetails(url);

                    renderList(
                        presEl,
                        data.prescriptions || [],
                        p => `<li class="list-group-item">${p.drug_name} — ${p.dosage}, ${p.duration} days</li>`,
                        'No prescriptions'
                    );

                    renderList(
                        suppEl,
                        data.supplies || [],
                        s => `<li class="list-group-item">${s.supply_name} — ${s.quantity_used} (${s.usage_type})</li>`,
                        'No supplies'
                    );

                    // Outcome fields: tolerate varying column names from the DB
                    const out = data.outcome || {};
                    setText('uOutcome', out.outcome ?? out.summary ?? out.type);
                    setText('uTreatmentNotes', out.treatment_notes ?? out.treatment ?? out.notes);
                    setText('uDischargeTime', out.discharge_time ?? out.follow_up_date);
                    setText('uReferralReason', out.referral_reason ?? out.reason);
                } catch (err) {
                    console.error('Visit details fetch failed:', err);
                    if (presEl) presEl.innerHTML = '<li class="list-group-item text-danger">Error loading details.</li>';
                }
            });

            // Avatar initial once the modal is visible
            modal.addEventListener('shown.bs.modal', () => {
                const name = (byId('uPatient')?.textContent || '').trim();
                byId('uAvatar').textContent = name ? name[0].toUpperCase() : '🩺';
            });
        }

        /** ======================================
         * Misc wires
         * ====================================== */
        function wireDeleteButtons() {
            document.addEventListener('click', (e) => {
                const delBtn = e.target.closest('.delete-btn');
                if (!delBtn) return;
                setText('deletePatientName', getDataAttr(delBtn, 'patient') || 'Unknown');
                const idInput = byId('deleteVisitId');
                if (idInput) idInput.value = getDataAttr(delBtn, 'id') || '';
                openModal('deleteVisitModal');
            });
        }

        function wireVisitDetailsModalCsrf() {
            byId('visitDetailsModal')?.addEventListener('show.bs.modal', refreshCsrfToken);
        }

        function wireAssignVisitIdDelegation() {
            document.addEventListener('click', (e) => {
                const host = e.target.closest('[data-visit-id]');
                if (!host) return;
                const vid = host.getAttribute('data-visit-id');
                qsa('.set-visit-id').forEach(i => (i.value = vid));
            });
        }

        function wireBootstrapValidation() {
            qsa('form.needs-validation').forEach(form => {
                form.addEventListener('submit', function(ev) {
                    if (!this.checkValidity()) {
                        ev.preventDefault();
                        ev.stopPropagation();
                    }
                    this.classList.add('was-validated');
                });
            });
        }

        /** ======================================
         * Init (robust: runs now or on DOMContentLoaded)
         * ====================================== */
        function initVisitsPage() {
            wireAddVisitModal();
            wireEditVisitModal();
            wireUnifiedViewModal();
            wireDeleteButtons();
            wireVisitDetailsModalCsrf();
            wireAssignVisitIdDelegation();
            wireBootstrapValidation();
            console.debug('[visits] init bound');
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initVisitsPage);
        } else {
            initVisitsPage();
        }
    })();
</script>

<?= $this->endSection() ?>