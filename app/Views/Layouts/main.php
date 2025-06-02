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