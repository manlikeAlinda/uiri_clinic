<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<!-- Sidebar -->
<?= view('partials/sidenav') ?>
<main class="dashboard-main">
  <?= view('partials/topbar') ?>

  <div class="dashboard-main-body">
    <!-- Page Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <h6 class="fw-semibold mb-0">Healthcare Dashboard</h6>
      <ul class="d-flex align-items-center gap-2">
        <li class="fw-medium">
          <a href="<?= base_url('dashboard') ?>" class="d-flex align-items-center gap-1 hover-text-primary">
            <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
            Dashboard
          </a>
        </li>
        <li>-</li>
        <li class="fw-medium">Statistics</li>
      </ul>
    </div>

    <!-- Top Metrics -->
    <div class="row row-cols-xxxl-6 row-cols-lg-3 row-cols-sm-2 row-cols-1 gy-4">
      <!-- Admissions -->
      <div class="col">
        <div class="card shadow-none border bg-gradient-start-1 h-100">
          <div class="card-body p-20">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <p class="fw-medium text-primary-light mb-1">Number of Admissions</p>
                <h6 class="mb-0"><?= isset($admissions) ? $admissions : '125' ?></h6>
              </div>
              <div class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                <iconify-icon icon="fa-solid:bed" class="text-white text-2xl"></iconify-icon>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Conditions Diagnosed -->
      <div class="col">
        <div class="card shadow-none border bg-gradient-start-2 h-100">
          <div class="card-body p-20">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <p class="fw-medium text-primary-light mb-1">Conditions Diagnosed</p>
                <h6 class="mb-0"><?= isset($conditions) ? $conditions : '78' ?></h6>
              </div>
              <div class="w-50-px h-50-px bg-purple rounded-circle d-flex justify-content-center align-items-center">
                <iconify-icon icon="mdi:stethoscope" class="text-white text-2xl"></iconify-icon>
              </div>
            </div>
            <div class="mt-12">
              <select class="form-select form-select-sm w-auto bg-base radius-6">
                <option value="week">Weekly</option>
                <option value="month">Monthly</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <!-- Outpatients -->
      <div class="col">
        <div class="card shadow-none border bg-gradient-start-3 h-100">
          <div class="card-body p-20">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <p class="fw-medium text-primary-light mb-1">Number of Outpatients</p>
                <h6 class="mb-0"><?= isset($outpatients) ? $outpatients : '310' ?></h6>
              </div>
              <div class="w-50-px h-50-px bg-info rounded-circle d-flex justify-content-center align-items-center">
                <iconify-icon icon="mdi:account-group-outline" class="text-white text-2xl"></iconify-icon>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Drugs Inventory -->
      <div class="col">
        <div class="card shadow-none border bg-gradient-start-4 h-100">
          <div class="card-body p-20">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <p class="fw-medium text-primary-light mb-1">Drugs Inventory Summary</p>
                <h6 class="mb-0"><?= isset($inventory) ? $inventory.' Items' : '542 Items' ?></h6>
              </div>
              <div class="w-50-px h-50-px bg-success-main rounded-circle d-flex justify-content-center align-items-center">
                <iconify-icon icon="mdi:pill" class="text-white text-2xl"></iconify-icon>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Patients Referred -->
      <div class="col">
        <div class="card shadow-none border bg-gradient-start-5 h-100">
          <div class="card-body p-20">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <p class="fw-medium text-primary-light mb-1">Patients Referred</p>
                <h6 class="mb-0"><?= isset($referred) ? $referred : '27' ?></h6>
              </div>
              <div class="w-50-px h-50-px bg-red rounded-circle d-flex justify-content-center align-items-center">
                <iconify-icon icon="mdi:account-arrow-right-outline" class="text-white text-2xl"></iconify-icon>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Total Admissions & Outpatients -->
      <div class="col">
        <div class="card shadow-none border bg-gradient-start-6 h-100">
          <div class="card-body p-20">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <p class="fw-medium text-primary-light mb-1">Total Admissions & Outpatients</p>
                <h6 class="mb-0"><?= (isset($admissions) ? $admissions : 125) + (isset($outpatients) ? $outpatients : 310) ?></h6>
              </div>
              <div class="w-50-px h-50-px bg-yellow rounded-circle d-flex justify-content-center align-items-center">
                <iconify-icon icon="solar:sum" class="text-white text-2xl"></iconify-icon>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="row row-cols-xxl-2 row-cols-1 gy-4 mt-4">
      <!-- Most Observed Conditions Chart -->
      <div class="col">
        <div class="card h-100">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h6 class="text-lg mb-0">Most Observed Conditions</h6>
              <select class="form-select form-select-sm w-auto bg-base radius-8">
                <option value="week">Weekly</option>
                <option value="month">Monthly</option>
              </select>
            </div>
            <div id="conditionsChart" class="pt-4"></div>
          </div>
        </div>
      </div>
      <!-- Patients Per Month Chart -->
      <div class="col">
        <div class="card h-100">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h6 class="text-lg mb-0">Patients per Month</h6>
              <select class="form-select form-select-sm w-auto bg-base radius-8">
                <option value="month">Monthly</option>
                <option value="year">Yearly</option>
              </select>
            </div>
            <div id="patientsPerMonthChart" class="pt-4"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="d-footer">
    <div class="row align-items-center justify-content-between">
      <div class="col-auto">
        <p class="mb-0">© <?= date('Y') ?> HealthDash. All Rights Reserved.</p>
      </div>
      <div class="col-auto">
        <p class="mb-0">Made by <span class="text-primary-600">YourTeam</span></p>
      </div>
    </div>
  </footer>
</main>

<?= $this->endSection() ?>
