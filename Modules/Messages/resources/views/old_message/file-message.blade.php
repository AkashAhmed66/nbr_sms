<form action="{{ route('messages.store-file-message') }}" method="POST">
  @csrf
  <div class="row g-6">
    <div class="col-md-6">
      <div class="form-floating form-floating-outline">
        <select id="senderId" name="sender_id" class="select2 form-select form-select-lg" data-allow-clear="true">
          <option value="">Select</option>
          @foreach($senderIds as $key=>$senderId)
            <option value="{{$senderId->senderID}}">{{$senderId->senderID}}</option>
          @endforeach
        </select>
        <label for="formtabs-country">Sender ID</label>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-floating form-floating-outline">
        <select id="template" class="select2 form-select form-select-lg" data-allow-clear="true">
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
        <input type="text" id="formtabs-first-name" class="form-control" placeholder="John" />
        <label for="formtabs-first-name">Campaign Name</label>
      </div>
    </div>

    <div class="col-md-6">
      <label class="form-check-label">Select SMS Type</label>
      <div class="col mt-2">
        <div class="form-check form-check-inline">
          <input name="content_type" class="form-check-input" type="radio" value="Text" id="collapsible-address-type-home" checked="" />
          <label class="form-check-label" for="collapsible-address-type-home">Text</label>
        </div>
        <div class="form-check form-check-inline">
          <input name="content_type" class="form-check-input" type="radio" value="Flash" id="collapsible-address-type-office" />
          <label class="form-check-label" for="collapsible-address-type-office">Flash</label>
        </div>
      </div>
    </div>

    <div class=" col-md-6">
      <div class="form-check form-check-primary mt-4">
        <input class="form-check-input" type="checkbox" value="" id="scheduleMessage" />
        <label class="form-check-label" for="customCheckPrimary">Schedule Message</label>
      </div>
    </div>

    <div class=" col-md-6">
      <div class="form-check form-check-primary mt-4">
        <input class="form-check-input" type="checkbox" value="" id="customCheckPrimary" />
        <label class="form-check-label" for="customCheckPrimary">DND</label>
      </div>
    </div>

    <div class="col-md-6 scheduleDateSection">
      <div class="form-floating form-floating-outline">
        <input type="text" id="scheduleDate" class="form-control dob-picker" placeholder="YYYY-MM-DD" />
        <label for="formtabs-birthdate">Date</label>
      </div>
    </div>

    <!-- Time Picker-->
    <div class="col-md-6 col-12 mb-6 scheduleTimeSection">
      <div class="form-floating form-floating-outline">
        <input type="text" class="form-control scheduleTime" placeholder="HH:MM" id="flatpickr-time" />
        <label for="flatpickr-time">Time Picker</label>
      </div>
    </div>
    <!-- /Time Picker -->

    <div class="form-floating form-floating-outline mb-6">
      
      <div class="form-floating form-floating-outline mb-5">
            <input type="file" name="file" id="current-image" class="form-control" accept=".csv, .xlsx, .xls">
            <label for="add-name">Upload Excel/CSV File</label>
          </div>
    </div>

    <div class="form-floating form-floating-outline mb-6">
      <textarea class="form-control h-px-100" name="message_text" id="message" placeholder="Message here..."></textarea>
      <label for="exampleFormControlTextarea1">Message</label>
      <span class="btn btn-sm btn-warning text-bold-700">Note: </span> Press (Ctrl+Alt+M) switch to Bengali. Hit Space, Enter or Tab to transliterate. <br>
      <span>Entered Char : <span name="countBox2" id="countBox2" style="color: #ff5b5b !important;" class="text-bold-700"></span>, Number of char per SMS : <span name="actualSMSLength" id="actualSMSLength" class="text-primary text-bold-700"></span> ,Total SMS :<span name="usrSMSCnt" id="usrSMSCnt" style="color: #ff5b5b !important;" class="text-bold-700"></span></span><br>

      <input type="hidden" name="page" value="" id="page"/>
      <input type="hidden" name="isunicode" value="" id="isunicode"/>
      <input type="hidden" name="smscharlength" value="" id="smscharlength"/>
      <input type="hidden" name="totalsmscount" value="" id="totalsmscount"/>
    </div>

  </div>
  <div class="pt-6">
    <button type="submit" class="btn btn-primary me-4">Submit</button>
    <button type="reset" class="btn btn-outline-secondary">Cancel</button>
  </div>
</form>
