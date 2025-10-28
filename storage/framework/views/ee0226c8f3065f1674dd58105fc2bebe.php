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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<?php $__env->startSection('content'); ?>
  <div class="card">
  <div class="card-header pb-0">

      <!--Search Form -->
      <form class="dt_adv_search">
        <div class="row">
          <div class="col-12">
            <div class="row g-5">

              <div class="col-12 col-sm-6 col-lg-3">
                <div class="form-floating form-floating-outline">
                  <input type="datetime-local" class="form-control dt-input" name="from_date"
                    value="<?php echo e(\Carbon\Carbon::now('Asia/Dhaka')->startOfDay()->format('Y-m-d\TH:i')); ?>"
                    max="<?php echo e(\Carbon\Carbon::now('Asia/Dhaka')->format('Y-m-d\TH:i')); ?>">
                  <label>Date From</label>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-lg-3">
                <div class="form-floating form-floating-outline">
                  <input type="datetime-local" class="form-control dt-input" name="to_date"
                    value="<?php echo e(\Carbon\Carbon::now('Asia/Dhaka')->format('Y-m-d\TH:i')); ?>"
                    max="<?php echo e(\Carbon\Carbon::now('Asia/Dhaka')->format('Y-m-d\TH:i')); ?>">
                  <label>Date To</label>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-lg-3">
                <div class="form-floating form-floating-outline">
                  <select id="add-user" name="user_id" class="select2 form-select dt-input">
                      <option value="">Select</option>
                      <?php $__currentLoopData = $userLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($userList->id); ?>"><?php echo e($userList->name); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </select>
                  <label for="country">User</label>
                </div>
              </div>

              <div class="col-12 col-sm-6 col-lg-3">
                <div class="form-floating form-floating-outline mt-1">
                <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                </div>
              </div>
            </div>

          </div>
        </div>
      </form>
    </div>
    <div class="card-datatable table-responsive">
    <table class="table table-bordered" id="users-table">
        <thead>
            <tr>
                <th>SL No</th>
                <th>Date</th>
                <th>Total SMS</th>
                <th>Total Amount</th>
                <th>Username</th>
            </tr>
        </thead>
        <tfoot>
        <tr>
            <th colspan="2" class="text-end">Total:</th>
            <th id="total-sms" style="font-weight: bold;font-size: 15px;color: red;">0</th>
            <th id="total-amount" style="font-weight: bold;font-size: 15px;color: red;">0</th>
            <th></th>
        </tr>
    </tfoot>
    </table>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<style>
  #users-table td {
    padding-top: 3px !important;
    padding-bottom: 3px !important;
    line-height: 2 !important;
    white-space: nowrap !important;
    vertical-align: middle !important;
  }
  #users-table thead th {
    padding-top: 3px !important;
    padding-bottom: 3px !important;
    height: 35px !important;
    white-space: nowrap !important;
    vertical-align: middle !important;
  }
  #users-table tbody tr:nth-child(odd) td {
    background-color: #f8f9fa !important;
  }
</style>

<script>
$(document).ready(function () {
    var table = $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        dom: "<'row'<'col-12'<'row'<'col-sm-5 d-flex align-items-center ms-12'l><'col-sm-6 text-end'B>>>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
            {
                extend: 'collection',
                text: 'Export',
                className: 'btn dropdown-toggle btn-outline-secondary me-4 waves-effect waves-light mt-6',
                buttons: [
                    { extend: 'copy', text: 'Copy' },
                    { extend: 'csv', text: 'CSV' },
                    { extend: 'excel', text: 'Excel' },
                    { extend: 'pdf', text: 'PDF' },
                    { extend: 'print', text: 'Print' }
                ]
            }
        ],
        lengthMenu: [[15, 25, 50, 100, -1], [15, 25, 50, 100, "All"]],
        pageLength: 15, // Default selected value

        ajax: {
            url: '<?php echo e(url('/reports/client-daywise-sms')); ?>',
            data: function (d) {
                d.from_date = $('input[name=from_date]').val();
                d.to_date = $('input[name=to_date]').val();
                d.user_id = $('select[name=user_id]').val();
            }
        },
        columns: [
          { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
          {
            data: 'date',
            name: 'date',
            render: function (data, type, row) {
                return moment(data).format('D-MMM-YYYY');
            }
          },
          { data: 'message_count', name: 'message_count' },
          { data: 'total_cost', name: 'total_cost' },
          { data: 'name', name: 'name' },
        ],

        // **Footer Calculation**
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
            var intVal = function (i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ? i : 0;
            };

            // **Total SMS Count**
            var totalSms = api
                .column(2, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // **Total Amount**
            var totalAmount = api
                .column(3, { page: 'current' })
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // **Update Footer Values**
            $('#total-sms').html(totalSms);
            $('#total-amount').html(totalAmount.toFixed(2));
        }
    });

    // **Submit Form & Reload Table**
    $('.dt_adv_search').on('submit', function (e) {
        e.preventDefault();
        table.draw();
    });
});


</script>




<?php echo $__env->make('layouts/layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\nbr\Modules/Reports\resources/views/client-day-wise-sms.blade.php ENDPATH**/ ?>