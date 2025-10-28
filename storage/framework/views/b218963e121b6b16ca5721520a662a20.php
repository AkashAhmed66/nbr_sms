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

<script>
  $(document).ready(function () {
    $('.select2').select2({
      placeholder: 'Select an option',
      allowClear: true
    });
  });
</script>

<?php $__env->startSection('content'); ?>
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

  <!-- Progress Bar at top -->
  <div id="download-progress-container" class="d-none mb-3">
    <div class="card">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div>
            <h6 class="mb-0">
              <i class="ri-download-line me-2"></i>Download Progress
            </h6>
            <small class="text-muted" id="progress-status">Preparing export...</small>
          </div>
          <div class="text-end">
            <small class="text-muted" id="progress-rows">0 rows processed</small>
          </div>
        </div>
        <div class="progress" style="height: 8px;">
          <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
               role="progressbar" 
               id="download-progress-bar" 
               style="width: 0%" 
               aria-valuenow="0" 
               aria-valuemin="0" 
               aria-valuemax="100">
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="card-header pb-0">

    <!--Search Form -->
    <form class="dt_adv_search">
      <div class="row">
      <div class="col-12">
        <div class="row g-5">
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="form-floating form-floating-outline">
          <input type="hidden" name="report_type" value="2days">
          <input type="text" class="form-control dt-input dt-full-name" name="message">
          <label>Message</label>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="form-floating form-floating-outline">
          <input type="text" class="form-control dt-input" name="mobile">
          <label>Mobile</label>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="form-floating form-floating-outline mb-5">
          <select id="add-sms-rate" name="source" class="select2 form-select dt-input">
            <option value="">Select Source</option>

            <option value="API">API</option>
            <option value="WEB">WEB</option>
          </select>
          <label for="sms-rate">Source</label>
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
          <div class="form-floating form-floating-outline">
            <input type="datetime-local" class="form-control dt-input" name="from_date"
              value="<?php echo e(\Carbon\Carbon::now('Asia/Dhaka')->subDays(2)->startOfDay()->format('Y-m-d\TH:i')); ?>"
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
          <select id="add-operator" name="operator" class="select2 form-select dt-input">
            <option value="">Select Operator</option>
            <?php $__currentLoopData = $operators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $operator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($key); ?>"><?php echo e($operator); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
          <label for="country">Operator</label>
          </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="form-floating form-floating-outline">
          <select id="add-senderId" name="senderId" class="select2 form-select dt-input">
            <option value="">Sender ID</option>
            <?php $__currentLoopData = $senderIds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $senderId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($senderId); ?>"><?php echo e($senderId); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
          <label for="country">Sender ID</label>
          </div>
        </div>
        <div class="col-12 mt-10 mb-3">
          <div class="row align-items-end">
            <div class="col-lg-2 col-md-3 col-sm-4">
              <div class="form-floating form-floating-outline">
                <select id="pagination-select" class="form-select">
                  <option value="10" selected>10</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
                  <option value="100">100</option>
                </select>
                <label for="pagination-select">Records per page</label>
              </div>
            </div>
            <div class="col-lg-10 col-md-9 col-sm-8">
              <div class="d-flex gap-2 justify-content-end">
                <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit"><i
                  class="ri ri-filter-2-line"></i> Filter
                </button>
                <a href="<?php echo e(url('/reports/last-2days-sms-list')); ?>" class="btn btn-secondary me-sm-3 me-1 data-reset"><i
                  class="ri ri-refresh-line"></i> Reset</a>
                <a href="#" id="download-excel" class="btn btn-primary me-sm-3 me-1">
                  <i class="ri ri-file-excel-2-fill"></i> Download Excel
                </a>
              </div>
            </div>
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
        <th>Sender ID</th>
        <th>Username</th>
        <th>Mobile</th>
        <th>Message</th>
        <th>Write Time</th>
        
        <th>SMS Count</th>
        <th>Rate(BDT)</th>
        <th>Charge(BDT)</th>
        <th>API/WEB</th>
        <?php if(env("APP_TYPE") !== "Aggregator"): ?>
      <th>Retry Count</th>
      <th>Error Code</th>
      <th>Error Message</th>
      <?php else: ?>
      <th>Delivery Status</th>
      <?php endif; ?>
      </tr>
      </thead>
    </table>
    </div>
  </div>

  <!-- Message Modal -->
  <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-height: 55vh; max-width: 650px;">
    <div class="modal-content"
      style="height: 40vh; border-radius: 12px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); border: none;">
      <div class="modal-header"
      style="background: #696cff; color: white; border-radius: 12px 12px 0 0; border-bottom: none; padding: 20px 25px;">
      <h5 class="modal-title d-flex align-items-center" id="messageModalLabel">
        <i class="ri-message-3-line me-2" style="font-size: 20px;"></i>
        Complete Message
      </h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" style="flex: 1; overflow-y: auto; padding: 25px; background: #f8f9ff;">
      <div class="message-container"
        style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #e3e6f0;">
        <textarea class="form-control" id="modalMessageContent" readonly
        style="resize: none; font-family: 'Segoe UI', system-ui, sans-serif; font-size: 16px; line-height: 1.6; min-height: 150px; font-weight: 400; border: none; background: transparent; color: #2c3e50; padding: 0; box-shadow: none;"></textarea>
      </div>
      </div>
      <div class="modal-footer"
      style="background: #f8f9ff; border-top: 1px solid #e3e6f0; border-radius: 0 0 12px 12px; padding: 20px 25px; justify-content: space-between;">
      <small class="text-muted d-flex align-items-center">
        <i class="ri-information-line me-1"></i>
        Click copy to save this message
      </small>
      <div>
        <button type="button" class="btn btn-light me-2" data-bs-dismiss="modal"
        style="border-radius: 8px; padding: 8px 20px;">
        <i class="ri-close-line me-1"></i>Close
        </button>
        <button type="button" class="btn btn-primary" onclick="copyMessageToClipboard()"
        style="border-radius: 8px; padding: 8px 20px; background: #696cff; border: none;">
        <i class="ri-file-copy-line me-1"></i>Copy Message
        </button>
      </div>
      </div>
    </div>
    </div>
  </div>



  <script>
    (function () {
    const exportId = window.__EXPORT_ID__;
    if (!exportId) return;

    const statusBox = document.getElementById('export-status');
    const statusText = document.getElementById('status-text');
    const rowsText = document.getElementById('rows-text');

    statusBox.classList.remove('d-none');
    statusText.textContent = 'running...';

    const poll = setInterval(async () => {
      try {
      const res = await fetch(`<?php echo e(url('/exports')); ?>/${exportId}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        cache: 'no-store'
      });
      if (!res.ok) return;
      const j = await res.json();

      statusText.textContent = j.status;
      rowsText.textContent = j.rows_written ?? 0;

      if (j.status === 'completed' && j.download_url) {
        clearInterval(poll);
        // Auto-download
        window.location.href = j.download_url;
      }
      if (j.status === 'failed') {
        clearInterval(poll);
        alert('Export failed: ' + (j.error || 'Unknown error'));
      }
      } catch (e) {
      console.error(e);
      }
    }, 3000);
    })();
  </script>


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
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    var table = $('#users-table').DataTable({
      processing: true,
      serverSide: true,
      searching: false,
      lengthChange: false,
      lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
      pageLength: 10, // Default selected value
      ajax: {
        url: '<?php echo e(url('/reports/last-2days-sms-list')); ?>',
        data: function (d) {
          d.message = $('input[name=message]').val();
          d.mobile = $('input[name=mobile]').val();
          d.source = $('select[name=source]').val();
          d.from_date = $('input[name=from_date]').val();
          d.to_date = $('input[name=to_date]').val();
          d.user_id = $('select[name=user_id]').val();
          d.operator = $('select[name=operator]').val();
          d.senderId = $('select[name=senderId]').val();
          d.type = 'normal';
        }
      },
      columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'mask', name: 'mask' },
        { data: 'name', name: 'name' },
        { data: 'destmn', name: 'destmn' },
        {
          data: 'message',
          name: 'message',
          render: function (data, type, row) {
            // Truncate message for display and make it clickable
            var truncated = data.length > 50 ? data.substring(0, 50) + '...' : data;
            var escapedData = data.replace(/\\/g, '\\\\').replace(/'/g, '\\\'').replace(/"/g, '\\"').replace(/\r?\n/g, '\\n');
            return '<span class="message-cell" style="cursor: pointer;" onclick="showMessageModal(\'' +
              escapedData + '\')" title="Click to view full message">' + truncated + '</span>';
          }
        },
        { data: 'write_time', name: 'write_time' },
        // { data: 'last_updated', name: 'last_updated' },
        { data: 'smscount', name: 'smscount' },
        { data: 'rate', name: 'rate' },

        { data: 'sms_cost', name: 'sms_cost' },
        // { data: 'status', name: 'status' },
        { data: 'source', name: 'source' },
        <?php if(env("APP_TYPE") !== "Aggregator"): ?>
      { data: 'retry_count', name: 'retry_count' },
      { data: 'error_code', name: 'error_code', render: function (data) { return data ? data : '-1'; } },
      { data: 'error_message', name: 'error_message', render: function (data) { return data ? data : 'NULL'; } },
    <?php else: ?>
      { data: 'dlr_status', name: 'dlr_status', render: function (data) { return data ? data : 'Message Submitted'; } },
    <?php endif; ?>
          ],
    });
    
    // Handle pagination select change
    $('#pagination-select').on('change', function() {
      var length = $(this).val();
      table.page.len(length).draw();
    });
    
    // Submit form and reload DataTable
    $('.dt_adv_search').on('submit', function (e) {
      e.preventDefault();
      table.draw();
    });

    // on click Download Excel ajax call
    // $('#download-excel').on('click', function(e) {
    //   e.preventDefault();
    //   var formData = $('.dt_adv_search').serialize();
    //   window.location.href = '<?php echo e(route('archived-sms-report-export')); ?>?' + formData;
    // });


    //onclick download-excel call API
    $('#download-excel').on('click', function (e) {
      e.preventDefault();
      startExport();
    });


    async function startExport() {
      // Show progress bar
      showProgressBar();
      updateProgress(0, 'Preparing export...', '0 rows processed');

      try {
        const res = await fetch('/reports/exports/outbox', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
          },
          body: JSON.stringify({
            format: 'csv',
            columns: ['id', 'destmn', 'mask', 'message', 'write_time', 'smscount', 'sms_cost','dlr_status_code'],
            filters: {
              date_from: $('input[name=from_date]').val(),
              date_to: $('input[name=to_date]').val(),
              user_id: $('select[name=user_id]').val(),
              message: $('input[name=message]').val(),
              mobile: $('input[name=mobile]').val(),
              source: $('select[name=source]').val(),
              operator: $('select[name=operator]').val(),
              senderId: $('select[name=senderId]').val(),
              type: "normal",
            }
          })
        });

        const { id } = await res.json();
        updateProgress(10, 'Export initiated...', '0 rows processed');
        pollStatus(id);
      } catch (error) {
        hideProgressBar();
        Swal.fire({
          icon: 'error',
          title: 'Export Failed',
          text: 'Failed to start export: ' + error.message,
          confirmButtonText: 'OK'
        });
      }
    }

    async function pollStatus(id) {
      const t = setInterval(async () => {
        try {
          const s = await fetch(`/reports/exports/${id}`, {
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token,
            },
          });
          const j = await s.json();

          // Calculate progress based on status
          let progress = 20;
          let statusText = j.status || 'Processing...';
          let rowsText = (j.rows_written || 0) + ' rows processed';

          if (j.status === 'running') {
            progress = Math.min(90, 20 + (j.rows_written || 0) / 1000); // Rough progress estimate
            statusText = 'Processing data...';
          } else if (j.status === 'completed') {
            progress = 100;
            statusText = 'Download ready!';
          }

          updateProgress(progress, statusText, rowsText);

          if (j.status === 'completed' && j.download_url) {
            clearInterval(t);

            // Show completion
            updateProgress(100, 'Download starting...', rowsText);

            // Trigger download after a short delay
            setTimeout(() => {
              const a = document.createElement('a');
              a.href = j.download_url;
              a.download = '';
              document.body.appendChild(a);
              a.click();
              a.remove();

              // Hide progress bar after successful download
              setTimeout(() => {
                hideProgressBar();
                Swal.fire({
                  icon: 'success',
                  title: 'Download Complete!',
                  text: 'Your file has been downloaded successfully.',
                  timer: 2000,
                  showConfirmButton: false
                });
              }, 1000);
            }, 500);
          }

          if (['failed', 'canceled'].includes(j.status)) {
            clearInterval(t);
            hideProgressBar();
            Swal.fire({
              icon: 'error',
              title: 'Export Failed',
              text: j.error || 'Something went wrong',
              confirmButtonText: 'OK'
            });
          }
        } catch (error) {
          clearInterval(t);
          hideProgressBar();
          Swal.fire({
            icon: 'error',
            title: 'Export Failed',
            text: 'Failed to check export status: ' + error.message,
            confirmButtonText: 'OK'
          });
        }
      }, 2000); // Check every 2 seconds for more responsive updates
    }

    function showProgressBar() {
      $('#download-progress-container').removeClass('d-none');
    }

    function hideProgressBar() {
      $('#download-progress-container').addClass('d-none');
      updateProgress(0, 'Preparing export...', '0 rows processed');
    }

    function updateProgress(percentage, status, rows) {
      $('#download-progress-bar').css('width', percentage + '%').attr('aria-valuenow', percentage);
      $('#progress-status').text(status);
      $('#progress-rows').text(rows);
    }
  });

  // Function to show message modal
  function showMessageModal(message) {
    // Properly decode escaped characters
    var decodedMessage = message.replace(/\\\\/g, '\\').replace(/\\'/g, "'").replace(/\\"/g, '"').replace(/\\n/g, '\n');
    
    $('#modalMessageContent').val(decodedMessage);
    
    // Fix accessibility issue by ensuring modal is properly handled
    var modal = $('#messageModal');
    modal.modal('show');
    
    // Ensure focus is properly managed
    modal.on('shown.bs.modal', function () {
      $('#modalMessageContent').focus();
    });
  }

  // Function to copy message to clipboard
  function copyMessageToClipboard() {
    var messageText = $('#modalMessageContent').val();
    navigator.clipboard.writeText(messageText).then(function () {
      // Show success message
      Swal.fire({
        icon: 'success',
        title: 'Copied!',
        text: 'Message copied to clipboard',
        timer: 1500,
        showConfirmButton: false,
        customClass: {
          confirmButton: 'btn btn-success'
        }
      });
    }).catch(function (err) {
      console.error('Failed to copy message: ', err);
      // Fallback for older browsers
      $('#modalMessageContent').select();
      document.execCommand('copy');

      Swal.fire({
        icon: 'success',
        title: 'Copied!',
        text: 'Message copied to clipboard',
        timer: 1500,
        showConfirmButton: false,
        customClass: {
          confirmButton: 'btn btn-success'
        }
      });
    });
  }
</script>
<?php echo $__env->make('layouts/layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\metronetsms\Modules/Reports\resources/views/last-2days-sms-list.blade.php ENDPATH**/ ?>