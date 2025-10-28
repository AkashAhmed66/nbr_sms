<?php
  $configData = Helper::appClasses();
  $customizerHidden = 'customizer-hide';
?>



<?php $__env->startSection('title', 'Login - Page'); ?>

<?php $__env->startSection('vendor-style'); ?>
  <?php echo app('Illuminate\Foundation\Vite')([
    'resources/assets/vendor/libs/@form-validation/form-validation.scss'
  ]); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-style'); ?>
  <?php echo app('Illuminate\Foundation\Vite')([
    'resources/assets/vendor/scss/pages/page-auth.scss'
  ]); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('vendor-script'); ?>
  <?php echo app('Illuminate\Foundation\Vite')([
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js'
  ]); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
  <?php echo app('Illuminate\Foundation\Vite')([
    'resources/assets/js/pages-auth.js'
  ]); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  <div class="position-relative">
    <div class="authentication-wrapper authentication-basic container-p-y p-4 p-sm-0">
      <div class="authentication-inner py-6">

        <!-- Login -->
        <div class="card p-md-7 p-1">
          <!-- Logo -->
          <div class="app-brand justify-content-center mt-5">
            <a href="<?php echo e(url('/')); ?>" class="app-brand-link gap-2">
              <span class="app-brand-logo demo"><?php echo $__env->make('_partials.macros',["width"=>10,"withbg"=>'var(--bs-primary)'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?></span>
              
            </a>
          </div>
          <!-- /Logo -->

          <div class="card-body mt-1">
            <h5 class="mb-1">Welcome to - <?php echo e(config('variables.templateName')); ?></h5>
            <p class="mb-5">Please sign-in to your account and start the adventure</p>

            <form id="formAuthentication" class="mb-5" action="<?php echo e(route('login')); ?>" method="POST">
              <?php echo csrf_field(); ?>
              <div class="form-floating form-floating-outline mb-5">
                <input type="text" class="form-control" id="email" name="login" placeholder="username" required autofocus autocomplete="username">
                <label for="email">Email or Username</label>
              </div>
              <div class="mb-5">
                <div class="form-password-toggle">
                  <div class="input-group input-group-merge">
                    <div class="form-floating form-floating-outline">
                      <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                      <label for="password">Password</label>
                    </div>
                    <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
                  </div>
                </div>
              </div>
              <div class="mb-5 d-flex justify-content-between mt-5">
                <div class="form-check mt-2">
                  <input class="form-check-input" type="checkbox" id="remember-me">
                  <label class="form-check-label" for="remember-me">
                    Remember Me
                  </label>
                </div>
                
              </div>
              <div class="mb-5">
                <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
              </div>
            </form>

            <!--<p class="text-center">
              <span>New on our platform?</span>
              <a href="<?php echo e(url('register')); ?>">
                <span>Create an account</span>
              </a>
            </p>-->


          </div>
        </div>
        <!-- /Login -->
        <img alt="mask" src="<?php echo e(asset('assets/img/illustrations/auth-basic-login-mask-'.$configData['style'].'.png')); ?>" class="authentication-image d-none d-lg-block" data-app-light-img="illustrations/auth-basic-login-mask-light.png" data-app-dark-img="illustrations/auth-basic-login-mask-dark.png" />
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts/layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\metronetsms\resources\views/auth/login.blade.php ENDPATH**/ ?>