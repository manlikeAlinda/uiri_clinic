<?php
/** Active-route helper (CI4) */
$uri      = service('uri');
$path     = $uri->getPath(); // e.g. "patients/edit/12"
$isActive = fn (string $prefix) => str_starts_with($path, trim($prefix, '/')) ? 'is-active' : '';
$ariaCur  = fn (string $prefix) => str_starts_with($path, trim($prefix, '/')) ? 'page' : null;
?>

<aside class="sidebar" role="complementary" aria-label="Primary sidebar">
  <!-- Close / collapse (mobile) -->
  <button
    type="button"
    class="sidebar-close-btn"
    data-js="sidebar-close"
    aria-label="Close sidebar"
    aria-controls="sidebar-menu"
  >
    <iconify-icon icon="radix-icons:cross-2" aria-hidden="true"></iconify-icon>
  </button>

  <!-- Brand -->
  <div class="sidebar-brand">
    <a href="<?= base_url('dashboard') ?>" class="sidebar-logo d-flex flex-column align-items-center text-center" aria-label="Go to dashboard">
      <!-- Automatic light/dark logo via <picture> -->
      <picture>
        <source srcset="<?= base_url('assets/images/logo-light.png') ?>" media="(prefers-color-scheme: dark)">
        <img
          src="<?= base_url('assets/images/logo.png') ?>"
          alt="Site logo"
          class="brand-logo mb-2"
          width="80"
          height="120"
          decoding="async"
          fetchpriority="high"
        >
      </picture>

      <!-- Compact icon (used when collapsed) -->
      <img
        src="<?= base_url('assets/images/logo-icon.png') ?>"
        alt=""
        class="logo-icon"
        width="60"
        height="60"
        decoding="async"
        aria-hidden="true"
      >
    </a>
  </div>

  <!-- Nav -->
  <nav class="sidebar-menu-area" aria-label="Primary">
    <ul class="sidebar-menu" id="sidebar-menu">
      <li class="<?= $isActive('dashboard') ?>">
        <a href="<?= base_url('dashboard') ?>" aria-current="<?= $ariaCur('dashboard') ?>">
          <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon" aria-hidden="true"></iconify-icon>
          <span>Dashboard</span>
        </a>
      </li>

      <li class="<?= $isActive('patients') ?>">
        <a href="<?= base_url('patients') ?>" aria-current="<?= $ariaCur('patients') ?>">
          <iconify-icon icon="mdi:account-multiple-outline" class="menu-icon" aria-hidden="true"></iconify-icon>
          <span>Manage Patients</span>
        </a>
      </li>

      <li class="<?= $isActive('doctors') ?>">
        <a href="<?= base_url('doctors') ?>" aria-current="<?= $ariaCur('doctors') ?>">
          <iconify-icon icon="healthicons:doctor-male-outline" class="menu-icon" aria-hidden="true"></iconify-icon>
          <span>Manage Doctors</span>
        </a>
      </li>

      <!-- Example: keep commented items tidy or guard with permissions -->
      <?php /* if ($canViewReports): */ ?>
      <!--
      <li class="<?= $isActive('reports') ?>">
        <a href="<?= base_url('reports') ?>" aria-current="<?= $ariaCur('reports') ?>">
          <iconify-icon icon="mdi:file-chart-outline" class="menu-icon" aria-hidden="true"></iconify-icon>
          <span>Reports</span>
        </a>
      </li>
      -->
      <?php /* endif; */ ?>

      <li class="<?= $isActive('supplies') ?>">
        <a href="<?= base_url('supplies') ?>" aria-current="<?= $ariaCur('supplies') ?>">
          <iconify-icon icon="mdi:package-variant" class="menu-icon" aria-hidden="true"></iconify-icon>
          <span>Manage Supplies</span>
        </a>
      </li>

      <li class="<?= $isActive('drugs') ?>">
        <a href="<?= base_url('drugs') ?>" aria-current="<?= $ariaCur('drugs') ?>">
          <iconify-icon icon="mdi:pill" class="menu-icon" aria-hidden="true"></iconify-icon>
          <span>Manage Drugs</span>
        </a>
      </li>

      <li class="<?= $isActive('visits') ?>">
        <a href="<?= base_url('visits') ?>" aria-current="<?= $ariaCur('visits') ?>">
          <iconify-icon icon="mdi:clipboard-text-outline" class="menu-icon" aria-hidden="true"></iconify-icon>
          <span>Manage Visits</span>
        </a>
      </li>
    </ul>
  </nav>
</aside>

<?php // Scripts (no design change, just UX + a11y) ?>
<?= $this->section('scripts') ?>
<script>
/**
 * Sidebar UX polish:
 * - Close with ESC on mobile
 * - Persist collapsed state across reloads
 * - Ensure focus returns to the toggle/close button when closing
 */
(function () {
  const storageKey = 'sidebar:collapsed';
  const sidebar = document.querySelector('.sidebar');
  const menu    = document.getElementById('sidebar-menu');
  const closeBtn = document.querySelector('[data-js="sidebar-close"]');
  const body = document.body;

  if (!sidebar) return;

  // Restore collapsed state
  const collapsed = localStorage.getItem(storageKey) === '1';
  if (collapsed) body.classList.add('sidebar-collapsed');

  // If you have a separate toggle button in topbar, wire it like this:
  const externalToggle = document.querySelector('[data-js="sidebar-toggle"]');
  function setCollapsed(next) {
    body.classList.toggle('sidebar-collapsed', next);
    localStorage.setItem(storageKey, next ? '1' : '0');
  }

  externalToggle?.addEventListener('click', () => {
    setCollapsed(!body.classList.contains('sidebar-collapsed'));
  });

  // Close (mobile overlay style)
  closeBtn?.addEventListener('click', () => {
    body.classList.remove('sidebar-open'); // assuming you add/remove this class for mobile
    // return focus to triggering control if you store it
    closeBtn.focus();
  });

  // Keyboard: ESC closes on mobile
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && body.classList.contains('sidebar-open')) {
      body.classList.remove('sidebar-open');
      closeBtn?.focus();
    }
  });

  // Improve focus ring visibility only when keyboard is used
  document.addEventListener('mousedown', () => body.classList.add('no-keyboard-focus'));
  document.addEventListener('keydown', () => body.classList.remove('no-keyboard-focus'));

  // Optional: scroll active item into view on load (long menus)
  const active = menu?.querySelector('.is-active a');
  active?.scrollIntoView({ block: 'nearest' });

})();
</script>
<?= $this->endSection() ?>
