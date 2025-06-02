<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="auth bg-base d-flex flex-wrap">
  <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center w-100">
    <div class="max-w-464-px mx-auto w-100">
      <h4 class="mb-12">Create Your Account</h4>

      <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session('error') ?></div>
      <?php endif; ?>
      <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session('success') ?></div>
      <?php endif; ?>

      <form action="<?= base_url('register/save') ?>" method="post">
        <div class="mb-3">
          <input type="username" name="username" class="form-control" placeholder="UserName" required>
        </div>
        <div class="mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="mb-3">
          <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Register</button>
        <p class="text-center mt-3">
          Already have an account? <a href="<?= base_url('login') ?>">Login here</a>
        </p>
      </form>
    </div>
  </div>
</section>
<?= $this->endSection() ?>
