<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="auth bg-base d-flex flex-wrap">
  <!-- Left visual panel (unchanged layout) -->
  <div class="auth-left d-lg-block d-none">
    <div class="d-flex align-items-center flex-column h-100 justify-content-center"></div>
  </div>

  <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
    <div class="max-w-464-px mx-auto w-100">

      <!-- Header -->
      <div class="text-center">
        <a href="<?= base_url() ?>" class="mb-40 max-w-290-px d-inline-block">
          <!-- remove inline style; keep aspect via utility classes if available -->
          <img src="<?= base_url('assets/images/logo.png') ?>" class="d-block mx-auto" style="height:100px;width:100px" alt="Organization logo">
        </a>
        <h4 class="mb-12">UIRI CLINIC INVENTORY MANAGEMENT SYSTEM</h4>
        <p class="mb-32 text-secondary-light text-lg">Welcome back Musawo!</p>
      </div>

      <!-- Global error / success messages -->
      <?php if ($msg = session()->getFlashdata('error')): ?>
        <div class="alert alert-danger" role="alert"><?= esc($msg) ?></div>
      <?php endif; ?>

      <!-- Per-field validation errors (CI4 Validator) -->
      <?php $errors = session()->getFlashdata('errors') ?? []; ?>

      <form
        action="<?= base_url('login/authenticate') ?>"
        method="post"
        novalidate
        autocomplete="on"
        class="needs-validation"
        data-js="login-form"
      >
        <?= csrf_field() ?>

        <!-- Honeypot (basic bot friction; ignore server-side if filled) -->
        <div class="d-none" aria-hidden="true">
          <label>Leave this field empty</label>
          <input type="text" name="website" tabindex="-1" autocomplete="off">
        </div>

        <!-- Username -->
        <div class="icon-field mb-16">
          <label for="username" class="visually-hidden">Username</label>
          <span class="icon top-50 translate-middle-y" aria-hidden="true">
            <iconify-icon icon="mage:email"></iconify-icon>
          </span>
          <input
            id="username"
            type="text"
            name="username"
            class="form-control h-56-px bg-neutral-50 radius-12 <?= isset($errors['username']) ? 'is-invalid' : '' ?>"
            placeholder="Username"
            required
            spellcheck="false"
            inputmode="text"
            autocomplete="username"
            value="<?= esc(old('username') ?? '') ?>"
            aria-describedby="usernameHelp <?= isset($errors['username']) ? 'usernameError' : '' ?>"
            aria-invalid="<?= isset($errors['username']) ? 'true' : 'false' ?>"
          >
          <small id="usernameHelp" class="text-secondary-light d-block mt-1">Use your clinic username.</small>
          <?php if (isset($errors['username'])): ?>
            <div id="usernameError" class="invalid-feedback"><?= esc($errors['username']) ?></div>
          <?php endif; ?>
        </div>

        <!-- Password -->
        <div class="position-relative mb-20">
          <div class="icon-field">
            <label for="password" class="visually-hidden">Password</label>
            <span class="icon top-50 translate-middle-y" aria-hidden="true">
              <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
            </span>
            <input
              type="password"
              name="password"
              class="form-control h-56-px bg-neutral-50 radius-12 <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
              id="password"
              placeholder="Password"
              required
              autocomplete="current-password"
              aria-describedby="<?= isset($errors['password']) ? 'passwordError' : '' ?>"
              aria-invalid="<?= isset($errors['password']) ? 'true' : 'false' ?>"
            >
            <?php if (isset($errors['password'])): ?>
              <div id="passwordError" class="invalid-feedback"><?= esc($errors['password']) ?></div>
            <?php endif; ?>
          </div>

          <!-- Accessible password toggle -->
          <button
            type="button"
            class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
            data-toggle="#password"
            aria-controls="password"
            aria-pressed="false"
            aria-label="Show password"
          ></button>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn btn-primary w-100 mt-32" data-js="submit">
          <span data-js="submit-text">Sign In</span>
          <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true" data-js="spinner"></span>
        </button>

      </form>
    </div>
  </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Vanilla JS (removes jQuery dependency for this feature)
(function () {
  const $ = (sel, root=document) => root.querySelector(sel);

  // Password visibility toggle
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.toggle-password');
    if (!btn) return;

    const targetSel = btn.getAttribute('data-toggle');
    const input = $(targetSel);
    if (!input) return;

    const isPassword = input.getAttribute('type') === 'password';
    input.setAttribute('type', isPassword ? 'text' : 'password');

    // Update icon & a11y state
    btn.classList.toggle('ri-eye-off-line', isPassword);
    btn.setAttribute('aria-pressed', String(isPassword));
    btn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');

    // Keep focus on the field for better UX
    input.focus();
  });

  // Prevent double submit, show spinner
  const form = document.querySelector('form[data-js="login-form"]');
  if (form) {
    form.addEventListener('submit', function (e) {
      const submitBtn = $('[data-js="submit"]', form);
      const spinner = $('[data-js="spinner"]', form);
      if (submitBtn && spinner) {
        submitBtn.setAttribute('disabled', 'true');
        spinner.classList.remove('d-none');
      }
    });

    // Basic client-side constraint validation hinting (without blocking)
    form.addEventListener('invalid', function (event) {
      event.preventDefault(); // avoid native tooltip noise
    }, true);
  }
})();
</script>
<?= $this->endSection() ?>
