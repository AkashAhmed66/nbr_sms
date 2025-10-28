

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

  <!-- Campaign Info Card -->
  <div class="card mb-4">
    <div class="card-header">
      <div class="d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Campaign ID: <?php echo e($campaignId); ?></h5>
        <a href="<?php echo e(route('running-campaign-list')); ?>" class="btn btn-outline-secondary">
          <i class="ri-arrow-left-line me-1"></i>Back to Campaign List
        </a>
      </div>
    </div>
  </div>

  <!-- Download Progress Bar -->
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

  <!-- Filter & SMS Details Table -->
  <div class="card">
    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
      <h5 class="card-title mb-0">SMS Details</h5>
    </div>
    <div class="card-body pt-2 pb-0">
      <!-- Filter Form (mirroring last-2days-sms-list) -->
      <form class="dt_adv_search mb-3">
        <div class="row g-3">
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="form-floating form-floating-outline">
              <input type="text" class="form-control dt-input" name="message" placeholder="Message">
              <label>Message</label>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="form-floating form-floating-outline">
              <input type="text" class="form-control dt-input" name="mobile" placeholder="Mobile">
              <label>Mobile</label>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="form-floating form-floating-outline mb-2">
              <select name="source" class="select2 form-select dt-input">
                <option value="">Select Source</option>
                <option value="API">API</option>
                <option value="WEB">WEB</option>
              </select>
              <label>Source</label>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="form-floating form-floating-outline mb-2">
              <select name="user_id" class="select2 form-select dt-input">
                <option value="">Select User</option>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
              <label>User</label>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="form-floating form-floating-outline mb-2">
              <input type="datetime-local" class="form-control dt-input" name="from_date" value="<?php echo e(\Carbon\Carbon::now('Asia/Dhaka')->subDays(2)->startOfDay()->format('Y-m-d\TH:i')); ?>" max="<?php echo e(\Carbon\Carbon::now('Asia/Dhaka')->format('Y-m-d\TH:i')); ?>">
              <label>Date From</label>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="form-floating form-floating-outline mb-2">
              <input type="datetime-local" class="form-control dt-input" name="to_date" value="<?php echo e(\Carbon\Carbon::now('Asia/Dhaka')->format('Y-m-d\TH:i')); ?>" max="<?php echo e(\Carbon\Carbon::now('Asia/Dhaka')->format('Y-m-d\TH:i')); ?>">
              <label>Date To</label>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="form-floating form-floating-outline mb-2">
              <select name="operator" class="select2 form-select dt-input">
                <option value="">Select Operator</option>
                <?php $__currentLoopData = $operators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $operator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($key); ?>"><?php echo e($operator); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
              <label>Operator</label>
            </div>
          </div>
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="form-floating form-floating-outline mb-2">
              <select name="senderId" class="select2 form-select dt-input">
                <option value="">Sender ID</option>
                <?php $__currentLoopData = $senderIds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $senderId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <option value="<?php echo e($senderId); ?>"><?php echo e($senderId); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </select>
              <label>Sender ID</label>
            </div>
          </div>
          <div class="col-12 col-lg-3 d-flex align-items-end gap-2">
            <button type="submit" class="btn btn-primary"><i class="ri ri-filter-2-line"></i> Filter</button>
            <a href="<?php echo e(url()->current()); ?>" class="btn btn-secondary"><i class="ri ri-refresh-line"></i> Reset</a>
            <a href="#" id="download-excel" class="btn btn-primary me-1">
              <i class="ri ri-file-excel-2-fill"></i> Download
            </a>
          </div>
        </div>
      </form>
    </div>
    <div class="card-datatable table-responsive">
      <table class="table table-bordered" id="campaign-details-table">
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
      <div class="modal-content" style="height: 40vh; border-radius: 12px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); border: none;">
        <div class="modal-header" style="background: #696cff; color: white; border-radius: 12px 12px 0 0; border-bottom: none; padding: 20px 25px;">
          <h5 class="modal-title d-flex align-items-center" id="messageModalLabel">
            <i class="ri-message-3-line me-2" style="font-size: 20px;"></i>
            Complete Message
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="flex: 1; overflow-y: auto; padding: 25px; background: #f8f9ff;">
          <div class="message-container" style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #e3e6f0;">
            <textarea class="form-control" id="modalMessageContent" readonly style="border: none; background: transparent; resize: none; font-family: 'Segoe UI', sans-serif; font-size: 14px; line-height: 1.6; height: 150px;"></textarea>
          </div>
        </div>
        <div class="modal-footer" style="background: #f8f9ff; border-top: 1px solid #e3e6f0; border-radius: 0 0 12px 12px; padding: 20px 25px; justify-content: space-between;">
          <small class="text-muted d-flex align-items-center">
            <i class="ri-information-line me-1"></i>
            Click copy to save message to clipboard
          </small>
          <div>
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
              <i class="ri-close-line me-1"></i>Close
            </button>
            <button type="button" class="btn btn-primary btn-sm ms-2" onclick="copyMessageToClipboard()">
              <i class="ri-file-copy-line me-1"></i>Copy Message
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

<?php $__env->stopSection(); ?>

<style>
  #campaign-details-table td {
    padding-top: 3px !important;
    padding-bottom: 3px !important;
    line-height: 2 !important;
    white-space: nowrap !important;
    vertical-align: middle !important;
  }

  #campaign-details-table thead th {
    padding-top: 3px !important;
    padding-bottom: 3px !important;
    height: 35px !important;
    white-space: nowrap !important;
    vertical-align: middle !important;
  }

  #campaign-details-table tbody tr:nth-child(odd) td {
    background-color: #f8f9fa !important;
  }
</style>

<script>
  $(document).ready(function () {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    var table = $('#campaign-details-table').DataTable({
      processing: true,
      serverSide: true,
      searching: false,
      lengthChange: false,
      lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
      pageLength: 25, // Default selected value
      ajax: {
        url: '<?php echo e($ajaxUrl); ?>',
        headers: {
          'X-CSRF-TOKEN': token
        }
      },
      columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'senderId', name: 'senderId' },
        { data: 'username', name: 'username' },
        { data: 'mobile', name: 'mobile' },
        {
          data: 'message',
          name: 'message',
          render: function (data, type, row) {
            // Truncate message for display and make it clickable
            var truncated = data && data.length > 50 ? data.substring(0, 50) + '...' : (data || '');
            return '<span class="message-cell" style="cursor: pointer;" onclick="showMessageModal(\'' +
              (data || '').replace(/'/g, '&#39;').replace(/"/g, '&quot;') + '\')" title="Click to view full message">' + truncated + '</span>';
          }
        },
        { data: 'write_time', name: 'write_time' },
        { data: 'smscount', name: 'smscount' },
        { data: 'rate', name: 'rate' },
        { data: 'charge', name: 'charge' },
        { data: 'source', name: 'source' },
        <?php if(env("APP_TYPE") !== "Aggregator"): ?>
          { data: 'retry_count', name: 'retry_count' },
          { data: 'error_code', name: 'error_code', render: function (data) { return data ? data : '-1'; } },
          { data: 'error_message', name: 'error_message', render: function (data) { return data ? data : 'NULL'; } },
        <?php else: ?>
          { data: 'dlr_status', name: 'dlr_status', render: function (data) { return data ? data : 'Message Submitted'; } },
        <?php endif; ?>
      ],
      order: [[5, 'desc']] // Order by write_time
    });

    // Download Excel
    $('#download-excel').on('click', function (e) {
      e.preventDefault();
      startExport();
    });

    async function startExport() {
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
              type: "campaign",
              campaign_id: '<?php echo e($campaignId); ?>',
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
    // Decode HTML entities
    var decodedMessage = message.replace(/&#39;/g, "'").replace(/&quot;/g, '"').replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>');

    $('#modalMessageContent').val(decodedMessage);
    $('#messageModal').modal('show');
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
<?php echo $__env->make('layouts/layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\metronetsms\Modules/Campaign\resources/views/campaign_details.blade.php ENDPATH**/ ?>