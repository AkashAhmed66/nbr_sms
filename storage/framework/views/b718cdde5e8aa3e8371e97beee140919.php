<?php $__env->startSection('title', $title); ?>

<!-- Vendor Styles -->
<?php $__env->startSection('vendor-style'); ?>
  <?php echo app('Illuminate\Foundation\Vite')([
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/@form-validation/form-validation.scss',
    'resources/assets/vendor/libs/animate-css/animate.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
  ]); ?>
<?php $__env->stopSection(); ?>

<!-- Vendor Scripts -->
<?php $__env->startSection('vendor-script'); ?>
  <?php echo app('Illuminate\Foundation\Vite')([
    'resources/assets/vendor/libs/moment/moment.js',
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js',
    'resources/assets/vendor/libs/cleavejs/cleave.js',
    'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
  ]); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-script'); ?>
  <script>
    const tableHeaders = <?php echo json_encode($tableHeaders, 15, 512) ?>;
    const ajaxUrl = <?php echo json_encode($ajaxUrl, 15, 512) ?>;
    const title = <?php echo json_encode($title, 15, 512) ?>;
  </script>
   <?php echo app('Illuminate\Foundation\Vite')(['resources/js/template-list-table.js', 'resources/js/template-management.js']); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  <div class="card">
    <div class="card-header pb-0">
      <h5 class="card-title mb-0">Search Filter</h5>
    </div>
    <div class="card-datatable table-responsive">
      <table class="dt-advanced-search table table-bordered" id="datatable">
        <thead>
        <tr>
          <?php $__currentLoopData = $tableHeaders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$tableHeader): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <th><?php echo e($tableHeader); ?></th>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tr>
        </thead>
      </table>
    </div>

    <?php echo $__env->make('messages::template.create', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  </div>
<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts/layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\nbr\Modules/Messages\resources/views/template/index.blade.php ENDPATH**/ ?>