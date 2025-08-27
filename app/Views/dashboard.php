<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php
// ===================== DB-DRIVEN SHAPE GUARDS =====================
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
  'patientsByMonth'     => [],
  // NEW: DB may provide this as an assoc array or a flat result set
  // Expected keys per band: total, delta (signed), percent (0-100), blurb, href (optional), prev (optional)
  'ageBands'            => [],   // e.g. ['child'=>['total'=>..,'delta'=>..,'percent'=>..], ...]
];
foreach ($defaults as $k => $v) {
  if (!isset($$k)) $$k = $v;
}
$inventory  = is_array($inventory) ? array_merge(['items'=>0,'qty'=>0], $inventory) : ['items'=>0,'qty'=>0];
$JSON_FLAGS = JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_QUOT;
$locale     = isset($locale) ? preg_replace('/[^a-zA-Z\-]/', '', (string)$locale) : 'en-UG';

// ---------- Helper to normalize age bands from DB rows ----------
/*
Supports either:
1) Associative array by band slug:
   $ageBands = [
     'child' => ['total'=>250,'delta'=>19,'percent'=>15,'blurb'=>'...', 'href'=>'...'],
     ...
   ];
2) Flat array of rows (from query):
   $ageBandsRows = [
     ['band'=>'child','total'=>250,'delta'=>19,'percent'=>15,'blurb'=>'...'],
     ...
   ];
*/
function normalizeAgeBands($ageBands): array {
  $result = [
    'child' => ['label'=>'Child', 'total'=>0,'delta'=>null,'percent'=>null,'blurb'=>'','href'=>null],
    'teen'  => ['label'=>'Teen',  'total'=>0,'delta'=>null,'percent'=>null,'blurb'=>'','href'=>null],
    'adult' => ['label'=>'Adult', 'total'=>0,'delta'=>null,'percent'=>null,'blurb'=>'','href'=>null],
    'older' => ['label'=>'Older', 'total'=>0,'delta'=>null,'percent'=>null,'blurb'=>'','href'=>null],
  ];

  // If it's a flat list, map by band
  if (array_is_list($ageBands)) {
    foreach ($ageBands as $row) {
      if (!is_array($row) || empty($row['band'])) continue;
      $key = strtolower((string)$row['band']);
      if (!isset($result[$key])) continue;
      $result[$key] = array_merge($result[$key], [
        'total'   => isset($row['total'])   ? (float)$row['total']   : 0,
        'delta'   => isset($row['delta'])   ? (float)$row['delta']   : null,
        'percent' => isset($row['percent']) ? (float)$row['percent'] : null,
        'blurb'   => (string)($row['blurb'] ?? ''),
        'href'    => $row['href'] ?? null,
        'prev'    => isset($row['prev']) ? (float)$row['prev'] : null,
      ]);
    }
  } else {
    // Associative by band
    foreach ($result as $key => $seed) {
      $src = $ageBands[$key] ?? [];
      if (!is_array($src)) $src = [];
      $result[$key] = array_merge($seed, [
        'total'   => isset($src['total'])   ? (float)$src['total']   : 0,
        'delta'   => isset($src['delta'])   ? (float)$src['delta']   : null,
        'percent' => isset($src['percent']) ? (float)$src['percent'] : null,
        'blurb'   => (string)($src['blurb'] ?? ''),
        'href'    => $src['href'] ?? null,
        'prev'    => isset($src['prev']) ? (float)$src['prev'] : null,
      ]);
    }
  }

  // If delta is missing but prev is present, compute delta as absolute change (not %)
  foreach ($result as $k => $v) {
    if ($v['delta'] === null && isset($v['prev']) && is_numeric($v['prev']) && $v['prev'] != 0) {
      $result[$k]['delta'] = $v['total'] - (float)$v['prev']; // absolute change
    }
  }

  // Provide default blurbs if DB didn’t send any
  $result['child']['blurb'] = $result['child']['blurb'] ?: 'Common conditions: asthma, common cold, immunizations.';
  $result['teen']['blurb']  = $result['teen']['blurb']  ?: 'Common conditions: acne, sports injuries, mental health.';
  $result['adult']['blurb'] = $result['adult']['blurb'] ?: 'Common conditions: hypertension, diabetes, pregnancy.';
  $result['older']['blurb'] = $result['older']['blurb'] ?: 'Common conditions: arthritis, heart disease, dementia.';

  return $result;
}
$age = normalizeAgeBands($ageBands);
$nf  = fn($n) => is_numeric($n) ? number_format((float)$n) : esc((string)$n);
?>

<style>
  /* ====== Base metric cards (existing) ====== */
  .metric-card{
    background:#fff;
    border:1px solid var(--bs-border-color);
    border-radius:1rem;
    box-shadow:0 1px 2px rgba(0,0,0,.04);
  }
  .metric-label{font-weight:600;color:#111827;font-size:.95rem;}
  .metric-value{font-weight:700;line-height:1;font-size:clamp(1.6rem,2.4vw + .4rem,2.8rem);}
  .metric-pill{display:inline-flex;align-items:center;gap:.35rem;padding:.25rem .6rem;border-radius:999px;font-weight:600;font-size:.8rem;background:var(--bs-light);color:inherit;}
  .metric-subtle{font-size:.8rem;color:#94a3b8;}

  /* ====== New age-band KPI cards (matches your reference) ====== */
  .kpi-age-card{background:#fff;border:1px solid var(--bs-border-color);border-radius:16px;box-shadow:0 1px 2px rgba(0,0,0,.04);padding:16px;height:100%;}
  .kpi-head{display:flex;align-items:flex-start;justify-content:space-between;gap:.5rem;margin-bottom:.25rem;}
  .kpi-title{font-weight:600;color:#0f172a;display:flex;align-items:center;gap:.35rem;}
  .kpi-title .info{color:#94a3b8;font-size:14px;}
  .kpi-cta{background:#fff;border:1px solid #e5e7eb;color:#111827;font-weight:600;padding:.35rem .7rem;border-radius:999px;line-height:1;font-size:.85rem;text-decoration:none;}
  .kpi-cta:hover{background:#f8fafc;}
  .kpi-figure{display:flex;align-items:baseline;gap:.5rem;margin:.25rem 0 .5rem;}
  .kpi-figure .avatar{width:30px;height:30px;border-radius:10px;border:2px solid #e5e7eb;display:inline-flex;align-items:center;justify-content:center;font-size:18px;color:#64748b;background:#fff;}
  .kpi-value{font-weight:800;font-size:clamp(1.6rem,2.2vw + .6rem,2.2rem);letter-spacing:.2px;}
  .kpi-delta{font-weight:700;font-size:.9rem;margin-left:.15rem;}
  .kpi-delta.up{color:#10b981;}
  .kpi-delta.down{color:#ef4444;}
  .kpi-foot{color:#6b7280;font-size:.9rem;}
  .kpi-foot b{color:#111827;font-weight:700;margin-right:.25rem;}

@media (prefers-color-scheme: dark){
  .metric-card,
  .kpi-age-card {
    background:#fff !important;
    border-color:#e5e7eb !important;
    color:#0f172a !important;
  }
  .kpi-cta {
    background:#fff !important;
    border-color:#e5e7eb !important;
    color:#111827 !important;
  }
  .kpi-figure .avatar {
    background:#fff !important;
    border-color:#e5e7eb !important;
    color:#64748b !important;
  }
  .kpi-title { color:#0f172a !important; }
  .kpi-foot  { color:#6b7280 !important; }
}

  @media (prefers-color-scheme: dark){
    .apexcharts-gridline,.apexcharts-xaxis line,.apexcharts-yaxis line{stroke:rgba(255,255,255,.08)!important;}
  }
</style>

<?= view('partials/sidenav') ?>
<main class="dashboard-main" data-locale="<?= esc($locale) ?>">
  <?= view('partials/topbar') ?>

  <div class="dashboard-main-body">

    <!-- Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
      <h6 class="fw-semibold mb-0">Healthcare Dashboard</h6>
    </div>

    <!-- ============== AGE-BAND CARDS (TOP) ============== -->
    <!-- <div class="row g-3 mb-4" role="region" aria-label="Age band metrics">
      <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3">
        <?php foreach (['child','teen','adult','older'] as $key):
          $c        = $age[$key];
          $delta    = $c['delta'];
          $hasDelta = is_numeric($delta);
          $cls      = $hasDelta ? ($delta >= 0 ? 'up' : 'down') : 'up';
          $sign     = !$hasDelta ? '' : ($delta >= 0 ? '+' : '−');
          $deltaTxt = $hasDelta ? ($sign . number_format(abs($delta))) : '';
          $pct      = is_numeric($c['percent']) ? (int)$c['percent'] : null;
          $href     = $c['href'] ?? base_url('analytics/'.$key);
        ?>
          <div class="col">
            <div class="kpi-age-card" role="group" aria-label="<?= esc($c['label']) ?> summary">
              <div class="kpi-head">
                <div class="kpi-title">
                  <span><?= esc($c['label']) ?></span>
                  <iconify-icon icon="mdi:information-outline" class="info" aria-hidden="true"></iconify-icon>
                </div>
                <a class="kpi-cta" href="<?= esc($href) ?>">See Details</a>
              </div>

              <div class="kpi-figure" aria-live="polite">
                <span class="avatar" aria-hidden="true">
                  <iconify-icon icon="mdi:account-outline"></iconify-icon>
                </span>
                <span class="kpi-value"><?= $nf($c['total']) ?></span>
                <?php if ($hasDelta): ?>
                  <span class="kpi-delta <?= $cls ?>"><?= esc($deltaTxt) ?></span>
                <?php endif; ?>
              </div>

              <div class="kpi-foot">
                <?php if ($pct !== null): ?><b><?= $pct ?>%</b><?php endif; ?>
                <span><?= esc($c['blurb']) ?></span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div> -->

    <?php
      // Existing KPI cards fed from DB (unchanged logic)
      $cards = [
        ['value'=>$admissions,            'label'=>'Admissions',                  'icon'=>'mdi:account-group-outline',     'delta'=>$admissionsDelta ?? null,  'deltaLabel'=>'Last Month'],
        ['value'=>$conditionsTotal,       'label'=>'Conditions Diagnosed',       'icon'=>'mdi:stethoscope',               'delta'=>$conditionsDelta ?? null,  'deltaLabel'=>'Last Month'],
        ['value'=>$outpatients,           'label'=>'Outpatients',                'icon'=>'mdi:account-heart-outline',     'delta'=>$outpatientsDelta ?? null, 'deltaLabel'=>'Last Month'],
        ['value'=>$referred,              'label'=>'Patients Referred',          'icon'=>'mdi:account-arrow-right',       'delta'=>$referredDelta ?? null,    'deltaLabel'=>'Last Month'],
        ['value'=>$totalAdmitOut,         'label'=>'Total Admit + Outpatients',  'icon'=>'mdi:human-male-board-poll',     'delta'=>null,                       'deltaLabel'=>''],
        ['value'=>$inventory['items']??0, 'label'=>'Inventory Items',            'icon'=>'mdi:package-variant-closed',    'delta'=>null,                       'deltaLabel'=>''],
        ['value'=>$inventory['qty']??0,   'label'=>'Inventory Quantity',         'icon'=>'mdi:warehouse',                 'delta'=>null,                       'deltaLabel'=>''],
      ];
    ?>

    <!-- ============== EXISTING KPI CARDS ============== -->
    <div class="row g-3 mb-8" role="region" aria-label="Key Metrics">
      <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3">
        <?php foreach ($cards as $c):
          $delta      = $c['delta'] ?? null;
          $hasDelta   = is_numeric($delta);
          $deltaUp    = $hasDelta ? ((float)$delta >= 0) : null;
          $deltaClass = $hasDelta ? ($deltaUp ? 'text-success' : 'text-danger') : 'text-muted';
          $deltaIcon  = $hasDelta ? ($deltaUp ? '↑' : '↓') : '•';
          $deltaText  = $hasDelta ? number_format(abs((float)$delta), 1) . '%' : '—';
          $deltaLabel = $c['deltaLabel'] ?? 'Last Month';
          $val        = $c['value'];
          $isInt      = is_int($val) || ctype_digit((string)$val);
          $displayVal = $isInt ? number_format((int)$val) : number_format((float)$val, 2);
        ?>
          <div class="col">
            <div class="metric-card p-3 h-100" role="group" aria-label="<?= esc($c['label']) ?>">
              <div class="d-flex justify-content-between align-items-start mb-2">
                <div class="d-flex align-items-center gap-2">
                  <span class="metric-label"><?= esc($c['label']) ?></span>
                </div>
                <div class="text-end">
                  <div class="metric-pill <?= $deltaClass ?>" aria-live="polite">
                    <span aria-hidden="true"><?= $deltaIcon ?></span>
                    <span><?= esc($deltaText) ?></span>
                  </div>
                  <?php if ($deltaLabel): ?>
                    <div class="metric-subtle"><?= esc($deltaLabel) ?></div>
                  <?php endif; ?>
                </div>
              </div>
              <div class="metric-value" data-number="<?= esc($displayVal) ?>"><?= esc($displayVal) ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- ============== CHARTS ============== -->
    <div class="row gy-4 mb-4">
      <div class="col-12 col-xl-6">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-body p-24">
            <div class="d-flex align-items-start justify-content-between mb-3">
              <h6 class="mb-0">Patients Trend</h6>
              <span class="text-muted small"><?= esc(ucfirst((string)$period)) ?></span>
            </div>
            <div id="patientsAreaChart" style="min-height:320px;" role="img" aria-label="Admissions and Outpatients trend over time"></div>
            <div class="visually-hidden" aria-live="polite" id="patientsAreaChartStatus"></div>
          </div>
        </div>
      </div>

      <div class="col-12 col-xl-6">
        <div class="card h-100 shadow-sm border-0">
          <div class="card-body p-24">
            <div class="d-flex align-items-start justify-content-between mb-3">
              <h6 class="mb-0">Conditions Diagnosed per <?= esc(ucfirst((string)$period)) ?></h6>
              <span class="text-muted small"><?= esc(ucfirst((string)$period)) ?></span>
            </div>
            <div id="conditionsPeriodChart" style="min-height:320px;" role="img" aria-label="Diagnoses per <?= esc($period) ?>"></div>
            <div class="visually-hidden" aria-live="polite" id="conditionsPeriodChartStatus"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- ==== DATA TO JS ==== -->
    <script>
      window.__DASH__ = Object.freeze({
        topConditions:       <?= json_encode($topConditions,       $JSON_FLAGS) ?>,
        patientsByMonth:     <?= json_encode($patientsByMonth,     $JSON_FLAGS) ?>,
        conditionsPerPeriod: <?= json_encode($conditionsPerPeriod, $JSON_FLAGS) ?>,
        period:              <?= json_encode($period,              $JSON_FLAGS) ?>,
        locale:              <?= json_encode($locale,              $JSON_FLAGS) ?>,
      });
    </script>

    <!-- ==== CHART BOOT (lazy + resilient) ==== -->
    <script>
      (function () {
        const D = window.__DASH__ || {};
        const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        function loadScript(src, { integrity, crossOrigin='anonymous' } = {}) {
          return new Promise((resolve, reject) => {
            const s = document.createElement('script');
            s.src = src; if (integrity){s.integrity=integrity;s.crossOrigin=crossOrigin;}
            s.async = true; s.onload = resolve; s.onerror = reject; document.head.appendChild(s);
          });
        }
        const $ = (sel, root=document) => root.querySelector(sel);
        const hasData = (arr) => Array.isArray(arr) && arr.length > 0;
        const noData = (sel, msg='No data available') => {
          const el = $(sel); if (!el) return;
          el.innerHTML = `<p class="text-muted text-center py-5 mb-0">${msg}</p>`;
          const status = $(`${sel}Status`); status && (status.textContent = msg);
        };

        const nf = new Intl.NumberFormat(D.locale || 'en-UG', { maximumFractionDigits: 2 });

        document.querySelectorAll('.metric-value[data-number]').forEach(n => {
          const raw = n.getAttribute('data-number');
          const num = Number(String(raw).replace(/,/g,''));
          if (!Number.isNaN(num)) n.textContent = nf.format(num);
        });

        function baseChartOpts(){
          return {
            chart:{animations:{enabled:!prefersReducedMotion},toolbar:{show:false},height:320},
            dataLabels:{enabled:false},
            grid:{strokeDashArray:3,borderColor:getComputedStyle(document.documentElement).getPropertyValue('--bs-border-color')||'#edf0f3'},
            legend:{position:'top',horizontalAlign:'center',fontSize:'13px',markers:{radius:12}},
            tooltip:{y:{formatter:(v)=>nf.format(v)}}
          };
        }

        function renderPatientsArea(){
          const data = Array.isArray(D.patientsByMonth) ? D.patientsByMonth : [];
          if (!hasData(data)) return noData('#patientsAreaChart');
          const labels = data.map(r=>r.label);
          const admits = data.map(r=>Number(r.admits)||0);
          const outs   = data.map(r=>Number(r.outs)||0);
          const opts = Object.assign({}, baseChartOpts(), {
            series:[{name:'Admissions',data:admits},{name:'Outpatients',data:outs}],
            chart:{type:'area',height:320,toolbar:{show:false},animations:{enabled:!prefersReducedMotion}},
            xaxis:{categories:labels,axisBorder:{show:false},axisTicks:{show:false}},
            yaxis:{labels:{formatter:(v)=>nf.format(v)}},
            stroke:{curve:'smooth',width:3},
            fill:{type:'gradient',gradient:{shade:'light',shadeIntensity:.35,opacityFrom:.4,opacityTo:.05,stops:[0,90,100]}},
            colors:['#3d7ff9','#ff9f29'],
          });
          new ApexCharts(document.querySelector('#patientsAreaChart'), opts).render();
        }

        function renderConditionsPerPeriod(){
          const data = Array.isArray(D.conditionsPerPeriod) ? D.conditionsPerPeriod : [];
          if (!hasData(data)) return noData('#conditionsPeriodChart');
          const labels = data.map(r=>r.label);
          const vals   = data.map(r=>Number(r.c)||0);
          const opts = Object.assign({}, baseChartOpts(), {
            series:[{name:'Diagnoses',data:vals}],
            chart:{type:'bar',height:320,toolbar:{show:false},animations:{enabled:!prefersReducedMotion}},
            xaxis:{categories:labels,axisBorder:{show:false},axisTicks:{show:false}},
            plotOptions:{bar:{columnWidth:'40%',borderRadius:4}},
            colors:['#16a34a'],
          });
          new ApexCharts(document.querySelector('#conditionsPeriodChart'), opts).render();
        }

        const targets = ['#patientsAreaChart','#conditionsPeriodChart'].map(sel=>$(sel)).filter(Boolean);
        if (targets.length===0) return;

        let apexLoaded = false;
        const once = new Set();
        const io = new IntersectionObserver(async (entries)=>{
          for (const entry of entries){
            if (!entry.isIntersecting) continue;
            const el = entry.target; if (once.has(el)) continue;
            if (!apexLoaded){
              await loadScript('https://cdn.jsdelivr.net/npm/apexcharts@3.49.1', {
                integrity:'sha384-0y2x3C2Z2v6r6fJ8oWZ3o7QmGx8o4J8cZ5r7l3j1a4jXJ0C0T8w0rjv3b6x2cHqP',
              }).catch(()=>{ noData('#patientsAreaChart','Failed to load chart library'); noData('#conditionsPeriodChart','Failed to load chart library'); io.disconnect();});
              apexLoaded = true;
            }
            if (el.id==='patientsAreaChart') renderPatientsArea();
            if (el.id==='conditionsPeriodChart') renderConditionsPerPeriod();
            once.add(el); io.unobserve(el);
          }
        }, { rootMargin:'120px 0px' });
        targets.forEach(t=>io.observe(t));
      })();
    </script>

<?= $this->endSection() ?>
