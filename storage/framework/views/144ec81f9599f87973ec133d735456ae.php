<style>
  .text-right {
    display: block;
    text-align: right;
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }
</style>
<form action="<?php echo e(route('messages.store-file-message')); ?>" method="POST" enctype="multipart/form-data">
  <?php echo csrf_field(); ?>
  <div class="row g-6">
    

    <div class="col-md-6">
      <div class="form-floating form-floating-outline nonMaskingOptions" style="display: block;">
        <select name="sender_id" <?php if(old('masking_type', 'Non-Masking') == 'Non-Masking'): ?> required <?php endif; ?>
          class="select2 form-select form-select-lg senderIdNonMasking" data-allow-clear="true">
          <option value="">Select</option>
          <?php $__currentLoopData = $senderIds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $senderId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($senderId->senderID); ?>" <?php if(old('sender_id') == $senderId->senderID): ?> selected <?php endif; ?>>
        <?php echo e($senderId->senderID); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <label for="senderIdNonMasking">Sender ID <span class="required-star">*</span> </label>
        <?php $__errorArgs = ['sender_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
      <span class="text-danger"><?php echo e($message); ?></span>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
      </div>

      
    </div>

    <div class="col-md-6">
      <div class="form-floating form-floating-outline">
        <select id="file-template" class="select2 form-select form-select-lg" data-allow-clear="true"
          onchange="updateFileTextArea()">
          <option value="">Select</option>
          <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($template->description); ?>" data-id="<?php echo e($template->template_type); ?>"><?php echo e($template->title); ?>

        </option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <label for="formtabs-country">Template</label>
      </div>
    </div>
    <input type="hidden" id="fileTemplateType" name="template_type" value="">
    <input type="hidden" id="totalMobileNumbers" name="totalMobileNumbers" value="1000">
    <div class="col-md-6">
      <div class="form-floating form-floating-outline">
        <input type="text" id="formtabs-first-name" name="campaign_name" class="form-control" placeholder="John" />
        <label for="formtabs-first-name">Campaign Name</label>
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-floating form-floating-outline">
        <input required="required" type="text" id="file-campaign-id" name="campaign_id" class="form-control" placeholder="555555" />
        <label for="formtabs-first-name">Campaign ID</label>
        <span class="text-danger" id="file-campaign-id-error"></span>
      </div>
    </div>

    <div class=" col-md-3">
      <div class="form-check form-check-primary mt-4">
        <input name="dnd" class="form-check-input" type="checkbox" value="1" id="customCheckPrimary" />
        <label class="form-check-label" for="customCheckPrimary">DND</label>
      </div>
    </div>

    <div class=" col-md-3">
      <div class="form-check form-check-primary mt-4">
        <input type="hidden" name="isScheduleMessage" value="0" />
        <input class="form-check-input scheduleMessage" name="isScheduleMessage" type="checkbox" value="1" />
        <label class="form-check-label" for="customCheckPrimary">Schedule Message</label>
      </div>
    </div>

    <div class="col-md-6">
      <div class="row">
        <!-- Date Picker (left) -->
        <div class="col-md-6 scheduleDateSection">
          <div class="form-floating form-floating-outline">
            <input type="text" id="scheduleDate" name="scheduleDate" class="form-control dob-picker"
              placeholder="YYYY-MM-DD" />
            <label for="formtabs-birthdate">Date</label>
          </div>
        </div>
        <!-- Time Picker (right) -->
        <div class="col-md-6 col-12 mb-6 scheduleTimeSection">
          <div class="form-floating form-floating-outline">
            <input type="text" class="form-control scheduleTime" name="scheduleTime" placeholder="HH:MM"
              id="flatpickr-time" />
            <label for="flatpickr-time">Time Picker</label>
            <div style="margin-top:2px; margin-bottom:0;">
              <small style="color:#ff5b5b; font-size:12px;">24 hour time</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="form-floating form-floating-outline mb-6">

      <div class="form-floating form-floating-outline mb-5">
        <input type="file" name="importFile" id="importFile" required class="form-control" accept=".csv, .xlsx, .xls">
        <label for="add-name">Upload Excel/CSV File <span class="required-star">*</span> </label>
        <span class="text-end d-block cursor-pointer"><a href="javascript:void(0)" onclick="downloadExcel()">Download Sample File</a></span>
      </div>
      <div id="mobileCount" class="mt-2 text-muted"></div>
    </div>

    <div class="form-floating form-floating-outline mb-6">
      <textarea class="form-control h-px-100" maxlength="1336" name="message_text" required id="file-message"
        oninput="updateFileCharCount()" placeholder="Message here..."></textarea>
      <label for="exampleFormControlTextarea1">Message <span class="required-star">*</span> </label>
      <span class="btn btn-sm btn-warning text-bold-700">Note: </span> Press (Ctrl+Alt+M) switch to Bengali. Hit Space,
      Enter or Tab to transliterate. <br>
      <span>Entered Char : <span name="countBox2" id="countBox23" style="color: #ff5b5b !important;"
          class="text-bold-700"></span>, Number of char per SMS : <span name="actualSMSLength" id="actualSMSLength3"
          class="text-primary text-bold-700"></span> ,Total SMS :<span name="usrSMSCnt" id="usrSMSCnt3"
          style="color: #ff5b5b !important;" class="text-bold-700"></span></span><br>

      <input type="hidden" name="page" value="" id="page" />
      <input type="hidden" name="isunicode" value="" id="file-isunicode" />
      <input type="hidden" name="smscharlength" value="" id="smscharlength" />
      <input type="hidden" name="totalsmscount" value="" id="totalsmscount" />
    </div>

  </div>
  <div class="pt-6">
    <button type="submit" class="btn btn-primary me-4" id="submitBtn">
      <span id="submitBtnText">Submit</span>
      <span id="submitBtnSpinner" class="spinner-border spinner-border-sm" style="display:none;" role="status"
        aria-hidden="true"></span>
    </button>
    <button type="reset" class="btn btn-outline-secondary">Cancel</button>
  </div>
</form>
<!-- Loader Overlay -->
<div id="pageLoader"
  style="display:none;position:fixed;top:0;left:0;z-index:9999;width:100vw;height:100vh;background:rgba(255,255,255,0.7);text-align:center;">
  <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);">
    <div class="spinner"
      style="border: 8px solid #f3f3f3;border-top: 8px solid #3498db;border-radius: 50%;width: 60px;height: 60px;animation: spin 1s linear infinite;">
    </div>
    <div style="margin-top:10px;">Processing file, please wait...</div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>
<script>
  $(document).ready(function () {

    $("form").on("submit", function (e) {
      // Check if schedule message is enabled but fields are not properly filled
      const isScheduleEnabled = $('.scheduleMessage').is(':checked');
      
      if (isScheduleEnabled) {
        const scheduleDate = $('#scheduleDate').val();
        const scheduleTime = $('.scheduleTime').val();
        
        // If schedule is enabled but fields are empty, show error and prevent submission
        if (!scheduleDate || !scheduleTime) {
          e.preventDefault();
          alert('Please fill in both schedule date and time fields.');
          return false;
        }
      } else {
        // If schedule is not enabled, remove required attributes to prevent validation errors
        $('#scheduleDate').removeAttr('required');
        $('.scheduleTime').removeAttr('required');
      }
      
      // Disable submit button and show spinner
      $("#submitBtn").prop("disabled", true);
      $("#submitBtnText").hide();
      $("#submitBtnSpinner").show();
    });
    
    // Handle schedule message checkbox change
    $('.scheduleMessage').on('change', function() {
      if ($(this).is(':checked') && $('.scheduleDateSection').is(':visible')) {
        // Make fields required when schedule is enabled
        $('#scheduleDate').attr('required', true);
        $('.scheduleTime').attr('required', true);
      } else {
        // Remove required when schedule is disabled
        $('#scheduleDate').removeAttr('required');
        $('.scheduleTime').removeAttr('required');
      }
    });


    // Show loader
    function showLoader() {
      $('#pageLoader').show();
    }
    // Hide loader
    function hideLoader() {
      $('#pageLoader').hide();
    }
    // Listen for changes on the file input with id="importFile"
    $(document).on('change', '#importFile', function (e) {
      var fileInput = this; // More direct
      var file = fileInput.files && fileInput.files[0];

      // Clear previous count message
      $('#mobileCount').text('');

      if (!file) {
        $('#mobileCount').text('No file selected.');
        return;
      }

      var fileExtension = file.name.split('.').pop().toLowerCase();

      // Show loader
      showLoader();

      if (fileExtension === 'xlsx' || fileExtension === 'xls') {
        // Handle Excel files using SheetJS
        var reader = new FileReader();

        reader.onload = function (event) {
          try {
            var data = event.target.result;
            var workbook = XLSX.read(data, { type: 'binary' });
            var firstSheet = workbook.Sheets[workbook.SheetNames[0]];
            var json = XLSX.utils.sheet_to_json(firstSheet, { header: 1 }); // Array of arrays

            // Define a regex for BD mobile numbers (with or without +88)
            const mobileRegex = /^(\+88)?01[3-9]\d{8}$/;
            var mobileNumbers = 0;

            json.forEach(function (row) {
              /*var phoneNumber = row[0] && row[0].toString().trim();
              if (phoneNumber && mobileRegex.test(phoneNumber)) {
                mobileNumbers++;
              }*/
              const val = row[0] && row[0].toString().trim();
              if (val && val.match(/^(8801\d{9}|01\d{9})$/)) {
                mobileNumbers++;
              }
            });

            $('#totalMobileNumbers').val(mobileNumbers);
            $('#mobileCount').text('Total mobile numbers: ' + mobileNumbers);


            if (mobileNumbers > 1) {
              $('#file-campaign-id').prop('required', true);
              $('#file-campaign-id').attr('data-error', 'Please Add Infozillion approval Campaign ID');
              $('#file-campaign-id-error').text('Please Add Infozillion approval Campaign ID');
            }else {
              $('#file-campaign-id').prop('required', false);
              $('#file-campaign-id').removeAttr('data-error');
              $('#file-campaign-id-error').text('');
            }


            //if on keyup file-campaign-id then error message will be removed
            $('#file-campaign-id').on('keyup', function () {
              $('#file-campaign-id').removeAttr('data-error');
              $('#file-campaign-id-error').text('');
            });



          } catch (error) {
            $('#mobileCount').text('Error reading Excel file.');
            console.error(error);
          } finally {
            hideLoader();
          }
        };

        reader.onerror = function () {
          $('#mobileCount').text('Error reading Excel file.');
          hideLoader();
        };

        reader.readAsBinaryString(file);

      } else if (fileExtension === 'csv') {
        // Handle CSV files
        var reader = new FileReader();

        reader.onload = function (event) {
          try {
            var csvData = event.target.result;
            var lines = csvData.split(/\r?\n/);
            const mobileRegex = /^(\+88)?01[3-9]\d{8}$/;
            var mobileNumbers = 0;

            lines.forEach(function (line) {
              // Ignore empty lines
              if (!line.trim()) return;
              var row = line.split(',');
              var phoneNumber = row[0] && row[0].trim();
              if (phoneNumber && mobileRegex.test(phoneNumber)) {
                mobileNumbers++;
              }
            });

            $('#mobileCount').text('Total mobile numbers: ' + mobileNumbers);
          } catch (error) {
            $('#mobileCount').text('Error reading CSV file.');
            console.error(error);
          } finally {
            hideLoader();
          }
        };

        reader.onerror = function () {
          $('#mobileCount').text('Error reading CSV file.');
          hideLoader();
        };

        reader.readAsText(file);

      } else {
        $('#mobileCount').text('Unsupported file type. Please upload an Excel or CSV file.');
        hideLoader();
      }
    });
  });
</script>

<script>
  function updateFileTextArea() {

    const selectElement = document.getElementById('file-template');
    const selectedOption = selectElement.options[selectElement.selectedIndex];

    const description = selectElement.value;
    const templateId = selectedOption.getAttribute('data-id');
    document.getElementById('fileTemplateType').value = templateId;
    updateFileCharCount();
  }

  function updateFileCharCount() {
    const messageTextArea = document.getElementById('file-message');
    const charCountDisplay = document.getElementById('countBox23');
    const smsLengthDisplay = document.getElementById('actualSMSLength3');
    const smsCountDisplay = document.getElementById('usrSMSCnt3');

    const text = messageTextArea.value;
    const isEnglish = /^[\x00-\x7F]*$/.test(text);
    const smsCharLimit = isEnglish ? 160 : 70;

    const charCount = messageTextArea.value.length;

    const totalSMS = Math.ceil(charCount / smsCharLimit);

    charCountDisplay.textContent = charCount;
    smsLengthDisplay.textContent = smsCharLimit;
    smsCountDisplay.textContent = totalSMS;
  }


  function downloadExcel() {
    fetch('/phonebook/download-excel')
      .then(response => {
        if (response.ok) {
          return response.blob();
        }
        throw new Error('Failed to download the file.');
      })
      .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        a.download = 'samplefile.xlsx';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
      })
      .catch(error => {
        console.error(error);
        alert('Unable to download the file.');
      });
  }
</script><?php /**PATH C:\xampp\htdocs\nbr\Modules/Messages\resources/views/message/file-message.blade.php ENDPATH**/ ?>