<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
// ─────────────────────────────────────────────────────────
// 1) DEFAULT DATA SETUP
// ─────────────────────────────────────────────────────────
$__defaults = [
  'from'               => date('Y-m-01'),
  'to'                 => date('Y-m-d'),
  'period'             => 'month',
  'patient_id'         => null,
  'patients'           => [],
  'patient'            => null,
  'visit'              => [],
  'vitals'             => [],
  'complaints'         => [],
  'examNotes'          => '',
  'diagnoses'          => [],
  'investigations'     => [],
  'prescriptions'      => [],
  'supplies'           => [],
  'outcome'            => [],
  'admissions'         => 0,
  'conditionsTotal'    => 0,
  'conditionsPerPeriod' => [],
  'outpatients'        => 0,
  'inventory'          => ['items' => 0, 'qty' => 0],
  'referred'           => 0,
  'totalAdmitOut'      => 0,
  'topConditions'      => [],
  'patientsPerMonth'   => [],
];
foreach ($__defaults as $k => $v) {
  if (!isset($$k)) $$k = $v;
}
unset($__defaults);
?>

<!-- SIDEBAR (hidden in print) -->
<div class="d-print-none">
  <?= view('partials/sidenav') ?>
</div>

<main class="dashboard-main report-page d-flex flex-column min-vh-100">
  <!-- TOPBAR (hidden in print) -->
  <div class="d-print-none">
    <?= view('partials/topbar') ?>
  </div>

  <div class="dashboard-main-body container-fluid flex-grow-1 py-4">

    <!-- Breadcrumb (hidden in print) -->
    <div class="d-print-none d-flex align-items-center justify-content-between mb-4">
      <h6 class="fw-semibold mb-0">Manage Reports</h6>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
          <li class="breadcrumb-item active" aria-current="page">Generate Individual Reports</li>
        </ol>
      </nav>
    </div>

    <!-- FILTER CARD (hidden in print) -->
    <div class="card mb-5 shadow-sm d-print-none">
      <div class="card-body">
        <h5 class="card-title mb-3">Generate Report</h5>
        <form method="get" class="d-flex flex-column gap-3">
          <div>
            <label class="form-label small mb-1">From</label>
            <input type="date"
              name="from"
              value="<?= esc($from) ?>"
              class="form-control form-control-sm">
          </div>
          <div>
            <label class="form-label small mb-1">To</label>
            <input type="date"
              name="to"
              value="<?= esc($to) ?>"
              class="form-control form-control-sm">
          </div>
          <div>
            <label class="form-label small mb-1">Patient</label>
            <select name="patient_id" class="form-select form-select-sm">
              <option value="">— Select Patient —</option>
              <?php foreach ($patients as $p): ?>
                <option value="<?= esc($p['patient_id']) ?>"
                  <?= (string)$p['patient_id'] === (string)$patient_id ? 'selected' : '' ?>>
                  <?= esc($p['first_name'] . ' ' . $p['last_name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <button type="submit"
              class="btn btn-secondary btn-sm w-100">
              Print
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- PRINT REPORT -->
    <?php if ($patient_id): ?>
      <div class="print-report d-none">
        <!-- Report Header -->
        <div class="text-center mb-4">
          <h5 class="fw-bold mb-2">
            <a href="<?= base_url('dashboard') ?>" class="sidebar-logo d-flex flex-column align-items-center text-center">
              <img src="<?= base_url('assets/images/logo.png') ?>" alt="site logo" class="light-logo mb-2" style="max-width: 120px;">
              <img src="<?= base_url('assets/images/logo-light.png') ?>" alt="site logo" class="dark-logo mb-2" style="max-width: 120px;">
              <img src="<?= base_url('assets/images/logo-icon.png') ?>" alt="site logo" class="logo-icon" style="max-width: 60px;">
            </a>
          </h5>
        </div>
        <hr class="my-3">
        <!-- Patient & Visit Info -->
        <div class="row mb-4 gy-3 fs-6">
          <div class="col-6">
            <p class="fw-semibold mb-1">
              <?= esc(($patient['first_name'] ?? '') . ' ' . ($patient['last_name'] ?? '')) ?>
            <p class="mb-0"><strong>Doctor:</strong> <?= esc($visit['doctor_name'] ?? '–') ?></p>
            <p class="small mb-1">
              <strong>DOB:</strong>
              <?= esc($patient['dob'] ?? $patient['date_of_birth'] ?? '–') ?>
            </p>
            </p>
          </div>
          <div class="col-6 text-end">
            <p class="mb-1"><strong>Visit Date:</strong> <?= esc($visit['visit_date'] ?? '–') ?></p>
            <p class="mb-1"><strong>Type:</strong> <?= esc($visit['visit_category'] ?? '–') ?></p>
            <p class="small mb-1">
              <strong>Contact:</strong>
              <?= esc($patient['phone']   ?? $patient['contact_info'] ?? '–') ?>
            </p>
          </div>
        </div>

        <!-- Vitals -->
        <h5 class="fw-semibold mb-2 fs-6">Vitals</h5>
        <table class="table table-sm mb-4 fs-6">
          <tr>
            <th class="fw-normal">Weight</th>
            <td class="fw-normal"><?= esc($vitals['weight'] ?? '–') ?> kg</td>
            <th class="fw-normal">BP</th>
            <td class="fw-normal"><?= esc($vitals['bp'] ?? '–') ?></td>
          </tr>
          <tr>
            <th class="fw-normal">Pulse</th>
            <td class="fw-normal"><?= esc($vitals['pulse'] ?? '–') ?> bpm</td>
            <th class="fw-normal">Temp</th>
            <td class="fw-normal"><?= esc($vitals['temp'] ?? '–') ?> °C</td>
          </tr>
          <tr>
            <th class="fw-normal">SpO₂</th>
            <td class="fw-normal"><?= esc($vitals['sp02'] ?? '–') ?> %</td>
            <th class="fw-normal">Resp Rate</th>
            <td class="fw-normal"><?= esc($vitals['resp_rate'] ?? '–') ?> /min</td>
          </tr>
        </table>

        <!-- Complaints -->
        <?php if (! empty($complaints)): ?>
          <h5 class="fw-semibold mb-2 fs-6">Complaints</h5>
          <ul class="mb-4 fs-6">
            <?php foreach ($complaints as $c): ?>
              <li class="fw-normal"><?= esc($c) ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>

        <!-- Examination Notes -->
        <?php if ($examNotes): ?>
          <h5 class="fw-semibold mb-2 fs-6">Examination Notes</h5>
          <p class="mb-4 fw-normal fs-6"><?= esc($examNotes) ?></p>
        <?php endif; ?>

        <!-- Diagnosis -->
        <?php if (! empty($diagnoses)): ?>
          <h5 class="fw-semibold mb-2 fs-6">Diagnosis</h5>
          <ul class="mb-4 fs-6">
            <?php foreach ($diagnoses as $d): ?>
              <li class="fw-normal"><?= esc($d) ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>

        <!-- Investigations -->
        <?php if (! empty($investigations)): ?>
          <h5 class="fw-semibold mb-2 fs-6">Investigations</h5>
          <ul class="mb-4 fs-6">
            <?php foreach ($investigations as $i): ?>
              <li class="fw-normal"><?= esc($i) ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>

        <!-- Medications -->
        <?php if (! empty($prescriptions)): ?>
          <h5 class="fw-semibold mb-2 fs-6">Medications</h5>
          <div class="table-responsive mb-4">
            <table class="table table-sm fs-6">
              <thead>
                <tr>
                  <th class="fw-normal">Drug</th>
                  <th class="fw-normal">Dosage</th>
                  <th class="fw-normal">Qty</th>
                  <th class="fw-normal">Duration</th>
                  <th class="fw-normal">Route</th>
                  <th class="fw-normal">Notes</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($prescriptions as $p): ?>
                  <tr>
                    <td class="fw-normal"><?= esc($p['drug']) ?></td>
                    <td class="fw-normal"><?= esc($p['dosage']) ?></td>
                    <td class="fw-normal"><?= esc($p['quantity']) ?></td>
                    <td class="fw-normal"><?= esc($p['duration']) ?></td>
                    <td class="fw-normal"><?= esc($p['route']) ?></td>
                    <td class="fw-normal"><?= esc($p['instructions']) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>

        <!-- Outcome -->
        <?php if (! empty($outcome)): ?>
          <h5 class="fw-semibold mb-2 fs-6">Outcome</h5>
          <table class="table table-sm mb-4 fs-6">
            <?php foreach (['type', 'reason', 'condition', 'follow_up_date', 'notes'] as $f): ?>
              <?php if (! empty($outcome[$f])): ?>
                <tr>
                  <th class="fw-normal"><?= esc(ucwords(str_replace('_', ' ', $f))) ?></th>
                  <td class="fw-normal"><?= esc($outcome[$f]) ?></td>
                </tr>
              <?php endif; ?>
            <?php endforeach; ?>
          </table>
        <?php endif; ?>

      </div><!-- /.print-report -->
    <?php endif; ?>

  </div><!-- /.dashboard-main-body -->

  <!-- FOOTER (hidden in print) -->
  <footer class="d-footer mt-auto py-3 bg-light d-print-none">
    <div class="container-fluid d-flex flex-column flex-sm-row justify-content-between small">
      <span>© <?= date('Y') ?> HealthDash</span>
      <span>Made by <a href="#" class="text-decoration-none">YourTeam</a></span>
    </div>
  </footer>
</main>

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  @media print {
    .d-print-none {
      display: none !important;
    }

    .print-report {
      display: block !important;
    }
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  <?php if ($patient_id): ?>
    // auto‑launch print dialog when a patient is selected
    window.addEventListener('DOMContentLoaded', () => window.print());
  <?php endif; ?>
</script>
<?= $this->endSection() ?>