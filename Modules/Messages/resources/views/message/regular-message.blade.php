<style>
  .required-star {
    color: red;
  }
</style>


<form action="{{ route('messages.store-regular-message') }}" method="POST">
  @csrf
  <div class="row g-6">

    {{-- <div class="col-md-6">
      <div class="form-check form-check-inline">
        <input name="masking_type" class="form-check-input non-masking-type" type="radio" value="Non-Masking"
               checked />
        <label class="form-check-label" for="non-masking-type">Non-Masking</label>
      </div>
      <div class="form-check form-check-inline">
        <input name="masking_type" class="form-check-input masking-type" type="radio" value="Masking" />
        <label class="form-check-label" for="masking-type">Masking</label>
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-check form-check-inline">
        <input name="content_type" class="form-check-input" type="radio" value="Text"
               id="collapsible-address-type-home" checked="" />
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
        <select name="sender_id" @if(old('masking_type', 'Non-Masking') == 'Non-Masking') required @endif class="select2 form-select form-select-lg senderIdNonMasking" data-allow-clear="true">
          <option value="">Select</option>
          @foreach($senderIds as $key=>$senderId)
            <option value="{{$senderId->senderID}}" @if(old('sender_id') == $senderId->senderID) selected @endif>{{$senderId->senderID}}</option>
          @endforeach
        </select>
        <label for="senderIdNonMasking">Sender ID <span class="required-star">*</span> </label>
        @error('sender_id')
        <span class="text-danger">{{ $message }}</span>
        @enderror
      </div>

      {{-- <div class="form-floating form-floating-outline maskingOptions" style="display: none;">
        <select name="sender_id" disabled @if(old('masking_type') == 'Masking') required @endif class="select2 form-select form-select-lg senderIdMasking" data-allow-clear="true">
          <option value="">Select</option>
          @foreach($maskList as $key=>$mask)
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
        <select id="template" class="select2 form-select form-select-lg" data-allow-clear="true"
                onchange="updateRegularTextArea()">
          <option value="">Select</option>
          @foreach($templates as $key=>$template)
            <option value="{{$template->description}}">{{$template->title}}</option>
          @endforeach
        </select>
        <label for="formtabs-country">Template</label>
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-floating form-floating-outline">
        <input type="text" id="formtabs-first-name" name="campaign_name" class="form-control" placeholder="John" />
        <label for="formtabs-first-name">Campaign Name</label>
      </div>
    </div>

    <div class="col-md-6">
      <div class="form-floating form-floating-outline">
        <input type="text" id="campaign-id" name="campaign_id" class="form-control" placeholder="555555" />
        <label for="formtabs-first-name">Campaign ID</label>
        <span class="text-danger" id="campaign-id-error"></span>
      </div>
    </div>

    <div class=" col-md-3">
      <div class="form-check form-check-primary mt-4">
        <input name='dnd' class="form-check-input" type="checkbox" value="1" id="customCheckPrimary" />
        <label class="form-check-label" for="customCheckPrimary">DND</label>
      </div>
    </div>

    <div class=" col-md-3">
      <div class="form-check form-check-primary mt-4">
        <input type="hidden" name="isScheduleMessage" value="0" />
          <input class="form-check-input scheduleMessage" name="isScheduleMessage" type="checkbox" value="1" />
        <label class="form-check-label" for="isScheduleMessage">Schedule Message</label>
      </div>
    </div>

    <div class="col-md-6">
      <div class="row">
        <!-- Date Picker (left) -->
        <div class="col-md-6 scheduleDateSection">
          <div class="form-floating form-floating-outline">
            <input type="text" id="scheduleDate" name="scheduleDate" class="form-control dob-picker" placeholder="YYYY-MM-DD" />
            <label for="scheduleDate">Date</label>
          </div>
        </div>
        <!-- Time Picker (right) -->
        <div class="col-md-6 col-12 mb-6 scheduleTimeSection">
          <div class="form-floating form-floating-outline">
            <input type="text" class="form-control scheduleTime" name="scheduleTime" placeholder="HH:MM" id="flatpickr-time" />
            <label for="scheduleTime">Time Picker</label>
            <div style="margin-top:2px; margin-bottom:0;">
              <small style="color:#ff5b5b; font-size:12px;">24 hour time</small>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="form-floating form-floating-outline mb-6">
      <textarea class="form-control h-px-100" id="number" name="recipient_number"
                required placeholder="Number here..."></textarea>
      <label for="exampleFormControlTextarea1">Recipient Number <span class="required-star">*</span></label>
      <span style="font-size: 12px; font-weight: bold; color: #ff5b5b !important;" id="display_number_count">0</span>
      <span style="font-size: 12px; font-weight: bold;">Total Recipients</span><br>
      @error('recipient_number')
      <span class="text-danger">{{ $message }}</span>
      @enderror
    </div>

    <div class="form-floating form-floating-outline mb-6">

      <textarea
        class="form-control h-px-100"
        name="message_text"
        required
        id="message"
        placeholder="Message here..."
        oninput="updateCharCount()"
        maxlength="1336"
      ></textarea>
      <label for="exampleFormControlTextarea1">Message <span class="required-star">*</span> </label>
      <span class="btn btn-sm btn-warning text-bold-700">Note: </span> Press (Ctrl+Alt+M) switch to Bengali. Hit Space,
      Enter or Tab to transliterate. <br>
      <span>Entered Char : <span name="countBox2" id="countBox2" style="color: #ff5b5b !important;"
                                 class="text-bold-700"></span>, Number of char per SMS : <span name="actualSMSLength"
                                                                                               id="actualSMSLength"
                                                                                               class="text-primary text-bold-700"></span> ,Total SMS :<span
          name="usrSMSCnt" id="usrSMSCnt" style="color: #ff5b5b !important;" class="text-bold-700"></span></span><br>

      <input type="hidden" name="page" value="" id="page" />
      <input type="hidden" name="isunicode" value="" id="isunicode" />
      <input type="hidden" name="smscharlength" value="" id="smscharlength" />
      <input type="hidden" name="totalsmscount" value="" id="totalsmscount" />
    </div>

  </div>
  <div class="pt-6">
    <button type="submit" class="btn btn-primary me-4" id="submitBtn">
      <span id="submitBtnText">Submit</span>
      <span id="submitBtnSpinner" class="spinner-border spinner-border-sm" style="display:none;" role="status" aria-hidden="true"></span>
    </button>
    <button type="reset" class="btn btn-outline-secondary">Cancel</button>
  </div>
</form>


<script>
  $(document).ready(function() {
    // Handle form submission
    $('form').on('submit', function(e) {
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
      
      // Show loading state
      $('#submitBtn').prop('disabled', true);
      $('#submitBtnText').hide();
      $('#submitBtnSpinner').show();
    });
    
    // Handle schedule message checkbox change
    $('.scheduleMessage').on('change', function() {
      if ($(this).is(':checked')) {
        // Make fields required when schedule is enabled
        $('#scheduleDate').attr('required', true);
        $('.scheduleTime').attr('required', true);
      } else {
        // Remove required when schedule is disabled
        $('#scheduleDate').removeAttr('required');
        $('.scheduleTime').removeAttr('required');
      }
    });
  });

  function updateRegularTextArea() {
    updateCharCount();
  }

  function updateCharCount() {
    const textarea = document.getElementById('message');
    const text = textarea.value;

    const isEnglish = /^[\x00-\x7F]*$/.test(text);
    const charLimit = isEnglish ? 160 : 70;

    document.getElementById('actualSMSLength').textContent = charLimit;

    const charCount = Array.from(text).length;

    const smsCount = Math.ceil(charCount / charLimit);

    document.getElementById('countBox2').textContent = charCount;
    document.getElementById('usrSMSCnt').textContent = smsCount;
  }

  document.getElementById('message').addEventListener('paste', function() {
    setTimeout(updateCharCount, 0);
  });

  document.getElementById('message').addEventListener('input', updateCharCount);
</script>

