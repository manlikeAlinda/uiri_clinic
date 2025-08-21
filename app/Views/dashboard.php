<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
// -------- SAFE DEFAULTS --------
$defaults = [
  'admissions'          => 0,
  'conditionsTotal'     => 0,
  'conditionsPerPeriod' => [],
  'outpatients'         => 0,
  'inventory'           => ['items' => 0, 'qty' => 0],
  'referred'            => 0,
  'totalAdmitOut'       => 0,
  'topConditions'       => [],
  'patientsPerMonth'    => [],
  'period'              => 'month',
  'patientsByMonth' => [],
];
foreach ($defaults as $k => $v) {
  if (!isset($$k)) $$k = $v;
}
unset($defaults);
?>
<style>
  /* Scoped, minimal polish */
  .metric-card {
    background: #fff;
    border: 1px solid var(--bs-border-color);
    border-radius: 1rem;
    box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
  }

  .metric-icon {
    width: 36px;
    height: 36px;
    border-radius: .75rem;
    border: 2px solid var(--bs-border-color);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    color: #6c757d;
    background: #fff;
  }

  .metric-label {
    font-weight: 600;
    color: #111827;
    font-size: .95rem;
  }

  .metric-value {
    font-weight: 700;
    line-height: 1;
    font-size: clamp(1.6rem, 2.4vw + .4rem, 2.8rem);
  }

  .metric-pill {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    padding: .25rem .6rem;
    border-radius: 999px;
    font-weight: 600;
    font-size: .8rem;
    background: var(--bs-light);
    color: inherit;
  }

  .metric-subtle {
    font-size: .8rem;
    color: #94a3b8;
  }

  /* caption under pill */
</style>

<?= view('partials/sidenav') ?>
<main class="dashboard-main">
  <?= view('partials/topbar') ?>

  <div class="dashboard-main-body">

    <!-- Header & Filter -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <h6 class="fw-semibold mb-0">Healthcare Dashboard</h6>
    </div>

    <?php
    $cards = [
      ['value' => $admissions,      'label' => 'Admissions',            'icon' => 'mdi:account-group-outline', 'delta' => $admissionsDelta ?? null,  'deltaLabel' => 'Last Month'],
      ['value' => $conditionsTotal, 'label' => 'Conditions Diagnosed',  'icon' => 'mdi:stethoscope',           'delta' => $conditionsDelta ?? null,  'deltaLabel' => 'Last Month'],
      ['value' => $outpatients,     'label' => 'Outpatients',           'icon' => 'mdi:account-heart-outline', 'delta' => $outpatientsDelta ?? null, 'deltaLabel' => 'Last Month'],
      ['value' => $referred,        'label' => 'Patients Referred',     'icon' => 'mdi:account-arrow-right',   'delta' => $referredDelta ?? null,    'deltaLabel' => 'Last Month'],
      ['value' => $totalAdmitOut,   'label' => 'Total Admit + Outpatients', 'icon' => 'mdi:human-male-board-poll', 'delta' => null,                    'deltaLabel' => ''],
      ['value' => $inventory['items'] ?? 0, 'label' => 'Inventory Items', 'icon' => 'mdi:package-variant-closed', 'delta' => null,                    'deltaLabel' => ''],
      ['value' => $inventory['qty'] ?? 0,   'label' => 'Inventory Quantity', 'icon' => 'mdi:warehouse',         'delta' => null,                    'deltaLabel' => ''],
    ];
    ?>


    <div class="row g-3 mb-8">
      <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3">
        <?php foreach ($cards as $c):
          $delta = $c['delta'] ?? null;
          $hasDelta = is_numeric($delta);
          $deltaUp = $hasDelta ? ((float)$delta >= 0) : null;
          $deltaClass = $hasDelta ? ($deltaUp ? 'text-success' : 'text-danger') : 'text-muted';
          $deltaIcon  = $hasDelta ? ($deltaUp ? '↑' : '↓') : '•';
          $deltaText  = $hasDelta ? number_format(abs((float)$delta), 1) . '%' : '';
          $deltaLabel = $c['deltaLabel'] ?? 'Last Month';
        ?>
          <div class="col">
            <div class="metric-card p-3 h-100">

              <!-- Header: icon + label on the left, change pill on the right -->
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="d-flex align-items-center gap-2">

                  <span class="metric-label"><?= esc($c['label']) ?></span>
                </div>

                <div class="text-end">
                  <div class="metric-pill <?= $deltaClass ?>">
                    <span><?= $deltaIcon ?></span>
                    <span><?= esc($deltaText) ?></span>
                  </div>
                  <div class="metric-subtle"><?= esc($deltaLabel) ?></div>
                </div>
              </div>

              <!-- Big number -->
              <div class="metric-value">
                <?php
                // If you want a trailing '+' like the reference for integers:
                $val = $c['value'];
                $isInt = is_int($val) || (ctype_digit((string)$val));
                echo $isInt ? number_format((int)$val) . '+' : esc(number_format((float)$val, 2));
                ?>
              </div>

            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- ===== CHARTS ===== -->
    <div class="row gy-4 mb-4">
      <!-- Radial multi ring + list -->
      <div class="col-12 col-xl-6">
        <div class="card shadow-sm border-0">
          <div class="card-body p-24">
            <div class="d-flex align-items-start justify-content-between mb-3">
              <h6 class="mb-0">Patients Trend</h6>
              <span class="text-muted small"><?= ucfirst($period) ?></span>
            </div>
            <div id="patientsAreaChart" style="min-height:320px;"></div>
          </div>
        </div>
      </div>

      <!-- Conditions per period bar -->
      <div class="col-12 col-xl-6">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-body p-24">
            <div class="d-flex align-items-start justify-content-between mb-3">
              <h6 class="mb-0">Conditions Diagnosed per <?= ucfirst($period) ?></h6>
              <span class="text-muted small"><?= ucfirst($period) ?></span>
            </div>
            <div id="conditionsPeriodChart" style="min-height:320px;"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- ==== DATA TO JS ==== -->
    <script>
      const topConditions = <?= json_encode($topConditions) ?>;
      const patientsByMonth = <?= json_encode($patientsByMonth ?? []) ?>; // [{label, admits, outs}]
      const conditionsPerPeriod = <?= json_encode($conditionsPerPeriod) ?>;
    </script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        // ====== Helpers ===========================================================
        const $ = sel => document.querySelector(sel);
        const fmtNum = v => Number(v) || 0;
        const empty = arr => !arr || !arr.length;
        const noData = (sel, msg = 'No data available') => {
          const el = $(sel);
          if (el) el.innerHTML = `<p class="text-muted text-center py-5 mb-0">${msg}</p>`;
        };
        const makeChart = (sel, opts) => {
          const el = $(sel);
          if (!el) return;
          return new ApexCharts(el, opts).render();
        };

        // Palettes (extend/modify as you wish)
        const palette = {
          blue: '#3d7ff9',
          orange: '#ff9f29',
          green: '#16a34a',
          purple: '#8b5cf6',
          red: '#ef4444',
          cyan: '#0ea5e9'
        };

        // ====== Source data from PHP (already defined globally) ===================
        const _patientsByMonth = Array.isArray(patientsByMonth) ? patientsByMonth : [];
        const _topConditions = Array.isArray(topConditions) ? topConditions : [];
        const _conditionsPerPeriod = Array.isArray(conditionsPerPeriod) ? conditionsPerPeriod : [];

        // ====== 1) PATIENTS TREND (Dual Area) ====================================
        (() => {
          if (empty(_patientsByMonth)) return noData('#patientsAreaChart');

          const labels = _patientsByMonth.map(r => r.label);
          const admits = _patientsByMonth.map(r => fmtNum(r.admits));
          const outs = _patientsByMonth.map(r => fmtNum(r.outs));

          makeChart('#patientsAreaChart', {
            chart: {
              type: 'area',
              height: 320,
              toolbar: {
                show: false
              }
            },
            series: [{
                name: 'Admissions',
                data: admits
              },
              {
                name: 'Outpatients',
                data: outs
              }
            ],
            xaxis: {
              categories: labels,
              axisBorder: {
                show: false
              },
              axisTicks: {
                show: false
              }
            },
            yaxis: {
              labels: {
                formatter: v => v.toString()
              }
            },
            dataLabels: {
              enabled: false
            },
            stroke: {
              curve: 'smooth',
              width: 3
            },
            fill: {
              type: 'gradient',
              gradient: {
                shade: 'light',
                shadeIntensity: .35,
                opacityFrom: .4,
                opacityTo: .05,
                stops: [0, 90, 100]
              }
            },
            colors: [palette.blue, palette.orange],
            legend: {
              position: 'top',
              horizontalAlign: 'center',
              fontSize: '13px',
              markers: {
                radius: 12
              }
            },
            grid: {
              strokeDashArray: 3,
              borderColor: '#edf0f3'
            }
          });
        })();

        // ====== 2) RADIAL MULTI (Top Conditions) ==================================
        (() => {
          if (empty(_topConditions)) {
            noData('#deptRadialChart');
            $('#deptRadialLegend') && ($('#deptRadialLegend').innerHTML = '');
            return;
          }

          const raw = _topConditions.slice(0, 6); // limit rings
          const labels = raw.map(r => r.diagnosis);
          const counts = raw.map(r => fmtNum(r.c));
          const maxVal = Math.max(...counts, 1);
          const series = counts.map(v => Math.round(v / maxVal * 100));
          const colors = [palette.blue, palette.orange, palette.green, palette.purple, palette.red, palette.cyan];

          makeChart('#deptRadialChart', {
            chart: {
              type: 'radialBar',
              height: 260
            },
            series,
            labels,
            colors: colors.slice(0, series.length),
            plotOptions: {
              radialBar: {
                track: {
                  background: 'rgba(240,240,240,.9)'
                },
                hollow: {
                  size: '28%'
                },
                dataLabels: {
                  show: false
                }
              }
            },
            stroke: {
              lineCap: 'round'
            },
            legend: {
              show: false
            }
          });

          const list = $('#deptRadialLegend');
          if (list) {
            list.innerHTML = labels.map((l, i) => `
        <li class="text-sm">
          ${l}: <span class="fw-semibold" style="color:${colors[i]}">${series[i]}%</span>
          <small class="text-muted">(${counts[i]})</small>
        </li>
      `).join('');
          }
        })();

        // ====== 3) CONDITIONS / PERIOD (Vertical Bar) =============================
        (() => {
          if (empty(_conditionsPerPeriod)) return noData('#conditionsPeriodChart');

          const labels = _conditionsPerPeriod.map(r => r.label);
          const data = _conditionsPerPeriod.map(r => fmtNum(r.c));

          makeChart('#conditionsPeriodChart', {
            chart: {
              type: 'bar',
              height: 320,
              toolbar: {
                show: false
              }
            },
            series: [{
              name: 'Diagnoses',
              data
            }],
            xaxis: {
              categories: labels,
              axisBorder: {
                show: false
              },
              axisTicks: {
                show: false
              }
            },
            plotOptions: {
              bar: {
                columnWidth: '40%',
                borderRadius: 4
              }
            },
            colors: [palette.green],
            dataLabels: {
              enabled: false
            },
            grid: {
              strokeDashArray: 3,
              borderColor: '#edf0f3'
            }
          });
        })();

      });
    </script>


    <?= $this->endSection() ?>