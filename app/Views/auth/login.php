<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="auth bg-base d-flex flex-wrap">
  <div class="auth-left d-lg-block d-none">
    <div class="d-flex align-items-center flex-column h-100 justify-content-center">
      <img src="<?= base_url('assets/images/auth/auth-img.png') ?>" alt="">
    </div>
  </div>
  <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
    <div class="max-w-464-px mx-auto w-100">
    <div>
        <a href="<?= base_url() ?>" class="mb-40 max-w-290-px">
        <img src="<?= base_url('assets/images/logo.png') ?>" style="height:100px; width:100px; display:block; margin:0 auto;" alt="">
        </a>
        <h4 class="mb-12">UIRI CLINIC INVENTORY MANAGEMENT SYSTEM</h4>
        <p class="mb-32 text-secondary-light text-lg">Welcome back Musawo!</p>
      </div>

      <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
      <?php endif; ?>

      <form action="<?= base_url('login/authenticate') ?>" method="post">
        <div class="icon-field mb-16">
          <span class="icon top-50 translate-middle-y">
            <iconify-icon icon="mage:email"></iconify-icon>
          </span>
          <input type="text" name="username" class="form-control h-56-px bg-neutral-50 radius-12" placeholder="Username" required>
        </div>
        <div class="position-relative mb-20">
          <div class="icon-field">
            <span class="icon top-50 translate-middle-y">
              <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
            </span> 
            <input type="password" name="password" class="form-control h-56-px bg-neutral-50 radius-12" id="your-password" placeholder="Password" required>
          </div>
          <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light" data-toggle="#your-password"></span>
        </div>

        <div class="d-flex justify-content-between gap-2">
          <a href="#" class="text-primary-600 fw-medium">Forgot Password?</a>
        </div>

        <button type="submit" class="btn btn-primary w-100 mt-32">Sign In</button>
      </form>
    </div>
  </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  $('.toggle-password').on('click', function() {
    $(this).toggleClass("ri-eye-off-line");
    let input = $($(this).attr("data-toggle"));
    input.attr("type", input.attr("type") === "password" ? "text" : "password");
  });
</script>
<?= $this->endSection() ?>
