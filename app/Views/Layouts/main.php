<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title ?? 'UIRI CLINIC MANAGEMENT SYSTEM' ?></title>
  <link rel="icon" type="image/png" href="<?= base_url('assets/images/favicon.png') ?>" sizes="16x16">

  <!-- remix icon font css  -->
  <link rel="stylesheet" href="<?= base_url('assets/css/remixicon.css') ?>">
  <!-- BootStrap css -->
  <link rel="stylesheet" href="<?= base_url('assets/css/lib/bootstrap.min.css') ?>">
  <!-- Apex Chart css -->
  <link rel="stylesheet" href="<?= base_url('assets/css/lib/apexcharts.css') ?>">
  <!-- Data Table css -->
  <link rel="stylesheet" href="<?= base_url('assets/css/lib/dataTables.min.css') ?>">
  <!-- Text Editor css -->
  <link rel="stylesheet" href="<?= base_url('assets/css/lib/editor-katex.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/lib/editor.atom-one-dark.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/lib/editor.quill.snow.css') ?>">
  <!-- Date picker css -->
  <link rel="stylesheet" href="<?= base_url('assets/css/lib/flatpickr.min.css') ?>">
  <!-- Calendar css -->
  <link rel="stylesheet" href="<?= base_url('assets/css/lib/full-calendar.css') ?>">
  <!-- Vector Map css -->
  <link rel="stylesheet" href="<?= base_url('assets/css/lib/jquery-jvectormap-2.0.5.css') ?>">
  <!-- Popup css -->
  <link rel="stylesheet" href="<?= base_url('assets/css/lib/magnific-popup.css') ?>">
  <!-- Slick Slider css -->
  <link rel="stylesheet" href="<?= base_url('assets/css/lib/slick.css') ?>">
  <!-- prism css -->
  <link rel="stylesheet" href="<?= base_url('assets/css/lib/prism.css') ?>">
  <!-- file upload css -->
  <link rel="stylesheet" href="<?= base_url('assets/css/lib/file-upload.css') ?>">
  <!-- audioplayer -->
  <link rel="stylesheet" href="<?= base_url('assets/css/lib/audioplayer.css') ?>">
  <!-- main css -->
  <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
        <style>
            /* ---------- Uniform Spacing Scale ---------- */
            :root {
                --sp-0: 0;
                --sp-1: .5rem;
                --sp-2: 1rem;
                --sp-3: 1.5rem;
                --sp-4: 2rem;

                --vu-primary: #6759ff;
                --vu-primary-soft: rgba(103, 89, 255, .12);
                --vu-bg: #f6f8fb;
                --vu-text: #2e2e2e;
                --vu-muted: #7a7a7a;
                --vu-accent: #00c2a8;
                --chip-bg: #ffffffb0;
                --glass-blur: 18px;
            }

            /* Apply uniform padding containers */
            .visit-pad {
                padding: var(--sp-2) var(--sp-3) !important;
            }

            .visit-pad-sm {
                padding: var(--sp-2) !important;
            }

            /* inside cards & blocks */

            /* Modal Shell */
            .visit-unified.glassy {
                background: rgba(255, 255, 255, .6);
                backdrop-filter: blur(var(--glass-blur));
                -webkit-backdrop-filter: blur(var(--glass-blur));
                border-radius: 1.25rem;
                overflow: hidden;
            }

            #visitUnifiedModal .modal-dialog {
                max-width: 1140px;
            }

            #visitUnifiedModal .modal-header .modal-title {
                font-size: 1.1rem;
                letter-spacing: .3px;
            }

            #visitUnifiedModal .btn-close {
                filter: invert(.6);
            }

            /* Tabs */
            .sexy-tabs .nav-link {
                border: 0;
                border-radius: 30px;
                padding: .55rem 1.1rem;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: .4rem;
                color: var(--vu-muted);
                background: transparent;
                transition: all .18s ease;
            }

            .sexy-tabs .nav-link.active {
                background: var(--vu-primary-soft);
                color: var(--vu-primary);
            }

            .sexy-tabs .nav-link:hover {
                background: rgba(0, 0, 0, .05);
            }

            .tab-icon {
                font-size: 1.05rem;
                line-height: 1;
            }

            /* Info cards */
            .info-card {
                background: #fff;
                border-radius: 1rem;
                box-shadow: 0 3px 10px rgba(0, 0, 0, .04);
            }

            .info-card .label {
                font-size: .75rem;
                text-transform: uppercase;
                color: var(--vu-muted);
                letter-spacing: .5px;
            }

            .info-card .value {
                font-size: .95rem;
                color: var(--vu-text);
                font-weight: 500;
            }

            /* Vitals */
            .vitals-wrap {
                display: flex;
                flex-wrap: wrap;
                gap: .6rem;
            }

            .vital-chip {
                background: var(--chip-bg);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, .4);
                border-radius: 999px;
                padding: .35rem .9rem;
                display: flex;
                align-items: baseline;
                gap: .3rem;
                box-shadow: 0 2px 6px rgba(0, 0, 0, .05);
                font-size: .85rem;
            }

            .vital-chip span {
                color: var(--vu-muted);
                font-size: .72rem;
                text-transform: uppercase;
                letter-spacing: .4px;
            }

            .vital-chip strong {
                font-size: .95rem;
                font-weight: 600;
                color: var(--vu-text);
            }

            /* Narrative blocks */
            .narrative-block {
                background: #fff;
                border-radius: .9rem;
                box-shadow: 0 2px 8px rgba(0, 0, 0, .03);
            }

            .narrative-block h6 {
                margin: 0 0 .45rem;
                font-size: .9rem;
                letter-spacing: .25px;
                color: var(--vu-primary);
            }

            .narrative-block p {
                font-size: .92rem;
                line-height: 1.45;
                color: var(--vu-text);
            }

            /* List groups */
            .sexy-list .list-group-item {
                border: 0;
                margin-bottom: .6rem;
                border-radius: .75rem;
                box-shadow: 0 2px 6px rgba(0, 0, 0, .05);
                background: #fff;
                padding: var(--sp-2) var(--sp-3);
            }

            .sexy-list .list-group-item:last-child {
                margin-bottom: 0;
            }

            .action-cell .icon-only {
                padding: .3rem .45rem;
                border-radius: .45rem;
            }

            .action-cell .icon-only iconify-icon {
                font-size: 1.15rem;
            }

            .dropdown-menu .dropdown-item iconify-icon {
                font-size: 1rem;
            }

            .dropdown-menu {
                min-width: 220px;
                border-radius: .6rem;
            }
        </style>
  <?= $this->renderSection('styles') ?>
</head>

<body>


  <?= $this->renderSection('content') ?>

  <!-- JS Libraries -->
  <script src="<?= base_url('assets/js/lib/jquery-3.7.1.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/lib/bootstrap.bundle.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/lib/apexcharts.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/lib/dataTables.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/lib/iconify-icon.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/lib/jquery-ui.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/lib/jquery-jvectormap-2.0.5.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/lib/jquery-jvectormap-world-mill-en.js') ?>"></script>
  <script src="<?= base_url('assets/js/lib/magnifc-popup.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/lib/slick.min.js') ?>"></script>
  <script src="<?= base_url('assets/js/lib/prism.js') ?>"></script>
  <script src="<?= base_url('assets/js/lib/file-upload.js') ?>"></script>
  <script src="<?= base_url('assets/js/lib/audioplayer.js') ?>"></script>
  <script src="<?= base_url('assets/js/app.js') ?>"></script>
  <script src="<?= base_url('assets/js/homeOneChart.js') ?>"></script>

  <?= $this->renderSection('scripts') ?>
</body>

</html>