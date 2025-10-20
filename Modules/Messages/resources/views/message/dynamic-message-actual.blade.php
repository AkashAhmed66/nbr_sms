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

  .excel-header-btn {
    background: white;
    border: 1px solid #dee2e6;
    color: #495057;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .excel-header-btn:hover {
    border-color: #007bff;
    color: #007bff;
    background: #f8f9fa;
  }

  .excel-header-btn:active {
    background: #e9ecef;
  }

  .excel-header-btn.clicked {
    background: #d4edda;
    border-color: #28a745;
    color: #155724;
  }

  .excel-header-btn::before {
    content: "";
  }

  #excelHeadersSection {
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 15px;
    margin-top: 10px;
  }

  #excelHeadersSection.has-headers {
    border-color: #28a745;
    background: linear-gradient(135deg, #f8f9fa 0%, #e8f5e8 100%);
  }
</style>
<form action="{{ route('messages.store-dynamic-message') }}" method="POST" enctype="multipart/form-data">
  @csrf
  <div class="row g-6">
    {{-- <div class="col-md-6">
      <div class="form-check form-check-inline">
        <input name="masking_type" class="form-check-input" type="radio" value="Non-Masking" id="non-masking-type"
          checked />
        <label class="form-check-label" for="non-masking-type">Non-Masking</label>
      </div>
      <div class="form-check form-check-inline">
        <input name="masking_type" class="form-check-input" type="radio" value="Masking" id="masking-type" />
        <label class="form-check-label" for="masking-type">Masking</label>
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-check form-check-inline">
        <input name="content_type" class="form-check-input" type="radio" value="Text" id="collapsible-address-type-home"
          checked="" />
        <label class="form-check-label" for="collapsible-address-type-home">Text</label>
      </div>
      <div class="form-check form-check-inline">
        <input name="content_type" class="form-check-input" type="radio" value="Flash"
          id="collapsible-address-type-office" />
        <label class="form-check-label" for="collapsible-address-type-office">Flash</label>
      </div>
    </div> --}}

    <div class="col-md-6">
      <div class="form-floating form-floating-outline nonMaskingOptions" style="display: block;">
        <select name="sender_id" @if(old('masking_type', 'Non-Masking') == 'Non-Masking') required @endif
          class="select2 form-select form-select-lg senderIdNonMasking" data-allow-clear="true">
          <option value="">Select</option>
          @foreach($senderIds as $key => $senderId)
        <option value="{{$senderId->senderID}}" @if(old('sender_id') == $senderId->senderID) selected @endif>
        {{$senderId->senderID}}</option>
      @endforeach
        </select>
        <label for="senderIdNonMasking">Sender ID <span class="required-star">*</span> </label>
        @error('sender_id')
      <span class="text-danger">{{ $message }}</span>
    @enderror
      </div>

      {{-- <div class="form-floating form-floating-outline maskingOptions" style="display: none;">
        <select name="sender_id" disabled @if(old('masking_type') == 'Masking') required @endif
          class="select2 form-select form-select-lg senderIdMasking" data-allow-clear="true">
          <option value="">Select</option>
          @foreach($maskList as $key => $mask)
            <option value="{{$mask->mask}}" @if(old('sender_id') == $mask->mask) selected @endif>{{$mask->mask}}</option>
          @endforeach
        </select>
        <label for="senderIdMasking">Masking <span class="required-star">*</span> </label>
        @error('sender_id')
          <span class="text-danger">{{ $message }}</span>
        @enderror
      </div> --}}
    </div>

    <div class="col-md-6">
      <div class="form-floating form-floating-outline">
        <select id="file-template" class="select2 form-select form-select-lg" data-allow-clear="true"
          onchange="updateFileTextArea()">
          <option value="">Select</option>
          @foreach($templates as $key => $template)
        <option value="{{$template->description}}" data-id="{{$template->template_type}}">{{$template->title}}
        </option>
      @endforeach
        </select>
        <label for="formtabs-country">Template</label>
      </div>
    </div>
    <input type="hidden" id="fileTemplateType" name="template_type" value="">
    <input type="hidden" id="totalMobileNumbersd" name="totalMobileNumbers" value="1000">
    <input type="hidden" id="excelHeadersList" name="excel_headers" value="">
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
        <span class="text-end d-block cursor-pointer"><a href="javascript:void(0)" onclick="downloadExcelDynamic()">Download Sample File</a></span>
      </div>
      <div id="mobileCount" class="mt-2 text-muted"></div>
      
      <!-- Excel Headers Section -->
      <div id="excelHeadersSection" class="mt-3" style="display: none;">
        <label class="form-label fw-bold text-primary">Available Fields from Excel:</label>
        <div id="excelHeaders" class="d-flex flex-wrap gap-2 mt-2">
          <!-- Headers will be populated here -->
        </div>
        <small class="text-muted mt-1 d-block">Click on any field to insert it into your message</small>
      </div>
    </div>

    <div class="form-floating form-floating-outline mb-6">
      <textarea class="form-control h-px-100" maxlength="1336" name="message_text" required id="file-message-dynamic"
        oninput="updateFileCharCount()" placeholder="Message here..."></textarea>
      <label for="exampleFormControlTextarea1">Message <span class="required-star">*</span> </label>
      <span class="btn btn-sm btn-warning text-bold-700">Note: </span> Press (Ctrl+Alt+M) switch to Bengali. Hit Space,
      Enter or Tab to transliterate. <br>
      <span>Entered Char : <span name="countBox2" id="countBox24" style="color: #ff5b5b !important;"
          class="text-bold-700"></span>, Number of char per SMS : <span name="actualSMSLength" id="actualSMSLength3d"
          class="text-primary text-bold-700"></span> ,Total SMS :<span name="usrSMSCnt" id="usrSMSCnt3d"
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
      hideExcelHeaders();

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

            // Extract headers from first row
            var headers = json[0] || [];
            displayExcelHeaders(headers);

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

            document.getElementById('totalMobileNumbersd').value = mobileNumbers;
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
            hideExcelHeaders();
            console.error(error);
          } finally {
            hideLoader();
          }
        };

        reader.onerror = function () {
          $('#mobileCount').text('Error reading Excel file.');
          hideExcelHeaders();
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
            
            // Extract headers from first line
            if (lines.length > 0 && lines[0].trim()) {
              var headers = lines[0].split(',').map(function(header) {
                return header.trim().replace(/"/g, ''); // Remove quotes
              });
              displayExcelHeaders(headers);
            }
            
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
            hideExcelHeaders();
            console.error(error);
          } finally {
            hideLoader();
          }
        };

        reader.onerror = function () {
          $('#mobileCount').text('Error reading CSV file.');
          hideExcelHeaders();
          hideLoader();
        };

        reader.readAsText(file);

      } else {
        $('#mobileCount').text('Unsupported file type. Please upload an Excel or CSV file.');
        hideExcelHeaders();
        hideLoader();
      }
    });

    // Function to display Excel headers as buttons
    function displayExcelHeaders(headers) {
      var headerContainer = $('#excelHeaders');
      var headerSection = $('#excelHeadersSection');
      
      // Clear previous headers
      headerContainer.empty();
      
      if (headers && headers.length > 0) {
        // Filter out empty headers
        var validHeaders = headers.filter(function(header) {
          return header && header.toString().trim() !== '';
        });
        
        if (validHeaders.length > 0) {
          // Store headers in hidden input field as JSON
          $('#excelHeadersList').val(JSON.stringify(validHeaders));
          
          validHeaders.forEach(function(header, index) {
            var cleanHeader = header.toString().trim();
            if (cleanHeader) {
              var button = $('<button type="button" class="excel-header-btn" data-header="' + cleanHeader + '">' + 
                             cleanHeader + '</button>');
              
              // Add click event to insert header into message
              button.on('click', function() {
                insertHeaderIntoMessage(cleanHeader);
              });
              
              headerContainer.append(button);
            }
          });
          
          headerSection.addClass('has-headers').show();
        } else {
          hideExcelHeaders();
        }
      } else {
        hideExcelHeaders();
      }
    }

    // Function to hide Excel headers
    function hideExcelHeaders() {
      $('#excelHeadersSection').removeClass('has-headers').hide();
      $('#excelHeaders').empty();
      $('#excelHeadersList').val(''); // Clear headers from hidden input
    }

    // Function to insert header into message textarea
    function insertHeaderIntoMessage(header) {
      var currentText =  document.getElementById("file-message-dynamic").value;
      
      // Format the header as a placeholder
      var placeholder = "{" + header + "}";
      
      // Append the placeholder at the end of the current text
      var newText = currentText + placeholder;

      console.log('Current text:', currentText);
      console.log('Inserting header:', newText);

      document.getElementById("file-message-dynamic").value = newText;
    //   messageTextarea.val(newText);
      
      // Update character count
      updateFileCharCount();
      
      // Set cursor position at the end
      var newCursorPos = newText.length;
      messageTextarea[0].setSelectionRange(newCursorPos, newCursorPos);
      
      // Focus back to textarea
      messageTextarea.focus();
      
      // Add visual feedback
      var button = $('[data-header="' + header + '"]');
      button.addClass('clicked');
      setTimeout(function() {
        button.removeClass('clicked');
      }, 300);
    }

    // Clear headers when file is cleared
    $('#importFile').on('click', function() {
      if (this.value) {
        hideExcelHeaders();
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
    const messageTextArea = document.getElementById('file-message-dynamic');
    const charCountDisplay = document.getElementById('countBox24');
    const smsLengthDisplay = document.getElementById('actualSMSLength3d');
    const smsCountDisplay = document.getElementById('usrSMSCnt3d');

    
    const text = messageTextArea.value;
    const isEnglish = /^[\x00-\x7F]*$/.test(text);


    const smsCharLimit = isEnglish ? 160 : 70;

    const charCount = messageTextArea.value.length;

    const totalSMS = Math.ceil(charCount / smsCharLimit);

    charCountDisplay.textContent = charCount;
    smsLengthDisplay.textContent = smsCharLimit;
    smsCountDisplay.textContent = totalSMS;
  }


  function downloadExcelDynamic() {
    fetch('/phonebook/download-excel-dynamic')
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
</script>