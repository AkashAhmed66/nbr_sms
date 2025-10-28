<?php $__env->startSection('title', ' Vertical Layouts - Forms'); ?>

<!-- Vendor Styles -->
<?php $__env->startSection('vendor-style'); ?>
  <?php echo app('Illuminate\Foundation\Vite')([
    'resources/assets/vendor/libs/flatpickr/flatpickr.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/jquery-timepicker/jquery-timepicker.scss',
    'resources/assets/vendor/libs/pickr/pickr-themes.scss',
    'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.scss',
  ]); ?>
<?php $__env->stopSection(); ?>

<!-- Vendor Scripts -->
<?php $__env->startSection('vendor-script'); ?>
  <?php echo app('Illuminate\Foundation\Vite')([
    'resources/assets/vendor/libs/cleavejs/cleave.js',
    'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
    'resources/assets/vendor/libs/moment/moment.js',
    'resources/assets/vendor/libs/flatpickr/flatpickr.js',
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js',
    'resources/assets/vendor/libs/jquery-timepicker/jquery-timepicker.js',
    'resources/assets/vendor/libs/pickr/pickr.js'
  ]); ?>
<?php $__env->stopSection(); ?>

<!-- Page Scripts -->
<?php $__env->startSection('page-script'); ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.7.7/xlsx.core.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xls/0.7.4-a/xls.core.min.js"></script>
  <?php echo app('Illuminate\Foundation\Vite')(['resources/assets/js/form-layouts.js', 'resources/js/message-management.js', 'resources/js/banglaType.js']); ?>
<?php $__env->stopSection(); ?>
<?php
  use Illuminate\Support\Facades\Session;
?>
<?php $__env->startSection('content'); ?>
  <div class="row">

    <div class="col-xl">
      <div class="card mb-6">

        <div class="card-header overflow-hidden">
          <?php if(session()->has('success')): ?>
            <div class="alert alert-success">
              <?php echo e(session()->get('success')); ?>

            </div>
          <?php endif; ?>
          <?php if(session()->has('error')): ?>
            <div class="alert alert-danger">
              <?php echo e(session()->get('error')); ?>

            </div>
          <?php endif; ?>

          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
              <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#form-tabs-regular-message"
                      role="tab" aria-selected="true">
                <span class="ri-message-3-line ri-20px d-sm-none"></span><span
                  class="d-none d-sm-block">Regular Message</span></button>
            </li>
            <li class="nav-item">
              <button class="nav-link " data-bs-toggle="tab" data-bs-target="#form-tabs-dynamic-message" role="tab"
                      aria-selected="false"><span class="ri-group-line ri-20px d-sm-none"></span><span
                  class="d-none d-sm-block">Group Message</span></button>
            </li>
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#form-tabs-file-message" role="tab"
                      aria-selected="false"><span class="ri-file-text-line ri-20px d-sm-none"></span><span
                  class="d-none d-sm-block">File Message</span></button>
            </li>
            <li class="nav-item">
              <button class="nav-link" data-bs-toggle="tab" data-bs-target="#form-tabs-file-message-actual" role="tab"
                      aria-selected="false"><span class="ri-code-s-slash-line ri-20px d-sm-none"></span><span
                  class="d-none d-sm-block">Dynamic Message</span></button>
            </li>
          </ul>
        </div>

        <div class="tab-content">
          <div class="tab-pane fade active show" id="form-tabs-regular-message" role="tabpanel">
            <?php echo $__env->make('messages::message.regular-message', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
          </div>
          <div class="tab-pane fade" id="form-tabs-dynamic-message" role="tabpanel">
            <?php echo $__env->make('messages::message.dynamic-message', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
          </div>
          <div class="tab-pane fade" id="form-tabs-file-message" role="tabpanel">
            <?php echo $__env->make('messages::message.file-message', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
          </div>
          <div class="tab-pane fade" id="form-tabs-file-message-actual" role="tabpanel">
            <?php echo $__env->make('messages::message.dynamic-message-actual', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
          </div>
        </div>

      </div>
    </div>

    <div class="col-xl">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">TERMS OF SMS CONTENT</h5>
          <span>Available Balance : <?php echo e($available_balance); ?> BDT</span>
          
        </div>
        <div class="card-body">
          <ul>
            <li>160 Characters are counted as 1 SMS in case of English language & 70 in other language.</li>
            <li>One simple text message containing extended GSM character set (~^{}[]\|€) is of 140 characters long. Check your SMS count before pushing SMS.</li>
            <li>Check your balance before send SMS</li>
            <li>Number format must be start with 88, for example 8801727000000</li>
            <li>You may send up to 3 sms size in a single try.</li>
            <li>Separate Numbers by Comma. For example: 8801727000000,8801727000001</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\metronetsms\Modules/Messages\resources/views/message/create.blade.php ENDPATH**/ ?>