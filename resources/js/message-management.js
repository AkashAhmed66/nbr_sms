function scheduleFunction() {
  if ($('#schedule').is(':checked')) {
    $('#scheduleTime').show();
  } else {
    $('#scheduleTime').hide();
    $('#schedule_time').val('');
  }
}

$(document).ready(function() {
  $('.phoneGroup').select2({
    placeholder: 'Select Phone Group',
    allowClear: false,
    minimumResultsForSearch: 5
  });

  $(function() {
    $('#message').avro({ 'bangla': false });
  });

  // $('#tabMenu a[href="#{{ old('sms_type') }}"]').tab('show');

  $('#fileClear').on('click', function() {
    $('.custom-file-label').html('Choose file');
    $('#inputGroupFile01').val(null);
    $('#display_file_number_count').text(0);
    $('#number_count').val(0);
  });
  $('.custom-file input').change(function(e) {
    $(this).next('.custom-file-label').html(e.target.files[0].name);
  });

  $('#base-sendSms').on('click', function() {
    $('#type_sms').val('sendSms');
    $('.custom-file-label').html('Choose file');
    $('#inputGroupFile01').val(null);
    $('#display_file_number_count').text(0);
    $('input:checkbox').prop('checked', false);
  });

  $('#base-groupSms').on('click', function() {
    $('#type_sms').val('groupSms');
    $('.custom-file-label').html('Choose file');
    $('#number').val('');
    $('#number_count').val(0);
    $('#display_number_count').text(0);
    $('#inputGroupFile01').val(null);
    $('#display_file_number_count').text(0);
  });

  $('#base-fileSms').on('click', function() {
    $('#type_sms').val('fileSms');
    $('#number').val('');
    $('#number_count').val(0);
    $('#display_number_count').text(0);


    //if number count > 1
    if ($('#number_count').val() > 1) {
      $('#campaign-id').prop('required', true);
      //set error message
      $('#campaign-id').attr('data-error', 'Please Add Infozillion approval Campaign ID');
      $('#campaign-id-error').text('Please Add Infozillion approval Campaign ID');
    } else {
      $('#campaign-id').prop('required', false);
      $('#campaign-id').attr('data-error', '');
      $('#campaign-id-error').text('');
    }

    $('input:checkbox').prop('checked', false);
  });




  $('#campaign-id').on('keyup', function () {
    $('#campaign-id').removeAttr('data-error');
    $('#campaign-id-error').text('');
  });

  $('#template').on('change', function() {
    var template_msg = $(this).val();
    $('#message').val(template_msg);
    // calculateSMSs();
  });

  $('#dynamic-template').on('change', function() {
    var template_msg = $(this).val();
    $('#dynamic-message').val(template_msg);
    //calculateSMSs();
  });

  $('#file-template').on('change', function() {
    var template_msg = $(this).val();
    $('#file-message').val(template_msg);
    //alert(template_msg);
  });

  //Number Count Start


  $('#inputGroupFile01').change(function(event) {
    /*Checks whether the browser supports HTML5*/
    if (typeof (FileReader) != 'undefined') {
      var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.xlsx|.xls)$/;
      /*Checks whether the file is a valid excel file*/
      var numberCount;
      var xlsxflag = false;
      var fileExtension = $('#inputGroupFile01').val().split('.').pop();
      //if (regex.test($(this).val().toLowerCase())) {
      if (fileExtension == 'xls' || fileExtension == 'xlsx') {
        var reader = new FileReader();
        reader.onload = function(e) {
          var data = e.target.result;
          if (fileExtension == 'xlsx') {
            var workbook = XLSX.read(data, { type: 'binary' });
            var sheet_name_list = workbook.SheetNames;
            numberCount = XLSX.utils.sheet_to_json(workbook.Sheets[sheet_name_list[0]]);
          } else if (fileExtension == 'xls') {
            var workbook = XLS.read(data, { type: 'binary' });
            var sheet_name_list = workbook.SheetNames;
            numberCount = XLS.utils.sheet_to_json(workbook.Sheets[sheet_name_list[0]]);
          } else {
            numberCount = 0;
          }

          $('#display_file_number_count').text(numberCount.length);
          $('#number_count').val(numberCount.length);

          $('#totalPhoneNumber').val(numberCount.length);
          $('#totalMessageCount').val($('#totalsmscount').val());

        };
        if (xlsxflag) {/*If excel file is .xlsx extension than creates a Array Buffer from excel*/
          reader.readAsArrayBuffer($('#inputGroupFile01')[0].files[0]);
        } else {
          reader.readAsBinaryString($('#inputGroupFile01')[0].files[0]);
        }

      } else {
        var f = event.target.files[0];
        if (f) {
          var r = new FileReader();
          r.onload = function(e) {
            var contents = e.target.result;
            var rowsn = contents.match(/(?:"(?:[^"]|"")*"|[^,\n]*)(?:,(?:"(?:[^"]|"")*"|[^,\n]*))*\n/g).length;
            $('#display_file_number_count').text(rowsn - 1);
            $('#number_count').val(rowsn - 1);
            $('#totalPhoneNumber').val(rowsn - 1);
            $('#totalMessageCount').val($('#totalsmscount').val());
          };
          r.readAsText(f);
        }
      }
    } else {
      alert('Sorry! Your browser does not support HTML5!');
    }
  });

  $('#number').on('keyup', function() {
    this.value = this.value.replace(/[^0-9,\.]/g, '');

    this.value = $.map(this.value.split(','), $.trim).join(',');
    //var words = this.value.match(/\S+/g).length
    var words = this.value.split(',').length;
    $('#display_number_count').text(words);
    $('#number_count').val(words);

    //if count > 1 then required Campaign ID field
    if (words > 1) {
      $('#campaign-id').prop('required', true);
      //set error message
      $('#campaign-id').attr('data-error', 'Please Add Infozillion approval Campaign ID');
      $('#campaign-id-error').text('Please Add Infozillion approval Campaign ID');
    } else {
      $('#campaign-id').prop('required', false);
      $('#campaign-id').attr('data-error', '');
      $('#campaign-id-error').text('');
    }

    if (words > 500) {
      alert('Phone number must be smaller than 500 (number <= 500)');
      var exacnumber = this.value.slice(0, 6499);
      $('#display_number_count').text(500);
      $('#recipientList').val(exacnumber);
      $('#number_count').val(500);

    }

    $('#totalPhoneNumber').val($('#display_file_number_count').text());
    $('#totalMessageCount').val($('#totalsmscount').val());

  });
  //Number Count End


  // Sms Count Start
  $('#countBox2').html(0);
  $('#actualSMSLength').html(0);
  $('#usrSMSCnt').html(0);

  function getSMSType(usrSms) {
    var smsType;
    if (jQuery.trim(usrSms).match(/[^\x00-\x7F]+/) !== null) {
      smsType = 'unicode';
    } else {
      var newSMS = usrSms.match(/(\u000C|\u005e|\u007B|\u007D|\u005c|\u005c|\u005B|\u007E|\u005D|\u007C|\u20ac)/g);
      if (newSMS !== null) {
        smsType = 'gsmextended';
      } else {
        smsType = 'plaintext';
      }
    }
    return smsType;
  }

  function calculateSMSs(type) {
    if (type === 1) {
    var content = $('#message').val();
    } else if (type === 2) {
    var content = $('#dynamic-message').val();
    } else if (type === 3) {
    var content = $('#file-message').val();
    } else {
      var content = '';
    }
    var newLines = content.match(/(\r\n|\n|\r)/g);
    var addition = 0;
    if (newLines != null) {
      addition = newLines.length;
    }

    var smsType = getSMSType(content);


    //usrSMSCharLength = content.length + addition;
    var usrSMSCharLength = content.length;
    var actualSMSLength = 0;
    var usrSMSCnt = 0;
    // $("#countBox2").html(usrSMSCharLength);
    //alert(getSMSType(content));
    if (getSMSType(content) === 'plaintext') {
      if (usrSMSCharLength <= 160) {
        actualSMSLength = 160;
        usrSMSCnt = 1;
      } else {
        actualSMSLength = 160 - 7;
        usrSMSCnt = Math.ceil(usrSMSCharLength / actualSMSLength);
      }
    } else if (getSMSType(content) === 'gsmextended') {
      if (usrSMSCharLength <= 140) {
        actualSMSLength = 140;
        usrSMSCnt = 1;
      } else {
        actualSMSLength = 140 - 6;
        usrSMSCnt = Math.ceil(usrSMSCharLength / actualSMSLength);
      }
    } else if (getSMSType(content) === 'unicode') {
      if (usrSMSCharLength <= 70) {
        actualSMSLength = 70;
        usrSMSCnt = 1;
      } else {
        actualSMSLength = 70 - 3;
        usrSMSCnt = Math.ceil(usrSMSCharLength / actualSMSLength);
      }
    }
    $('#countBox2').html(usrSMSCharLength);
    $('#actualSMSLength').html(actualSMSLength);
    $('#usrSMSCnt').html(usrSMSCnt);
    $('#totalsmscount').val(usrSMSCnt);
    $('#isunicode').val(smsType);
    $('#file-isunicode').val(smsType);
    $('#group-isunicode').val(smsType);
    console.log("🚀 ~ calculateSMSs ~ smsType:", smsType)

  }

  //Sms Count End

  $('#message').on('keyup', function() {
    calculateSMSs(1);

    $('#totalPhoneNumber').val($('#display_file_number_count').text());
    $('#totalMessageCount').val($('#totalsmscount').val());

  });

    $('#dynamic-message').on('keyup', function() {
    calculateSMSs(2);

    $('#totalPhoneNumber').val($('#display_file_number_count').text());
    $('#totalMessageCount').val($('#totalsmscount').val());

  });

    $('#file-message').on('keyup', function() {
    calculateSMSs(3);

    $('#totalPhoneNumber').val($('#display_file_number_count').text());
    $('#totalMessageCount').val($('#totalsmscount').val());

  });


  $('#saveAsDraft').click(function() {
    $('#status').val('Draft');
    return true;
  });

  $('#SendSMSNow').click(function() {
    $('#status').val('Queue');
    return true;
  });


  $('.scheduleDateSection').hide();
  $('.scheduleTimeSection').hide();
  $('.scheduleMessage').on('change', function() {
    if ($('.scheduleMessage').is(':checked')) {
      $('.scheduleMessage').val('1');

      flatpickr('#scheduleDate', {
        dateFormat: 'Y-m-d',
        minDate: 'today',
        defaultDate: 'today',
        disableMobile: true,
        allowInput: true,
        clickOpens: true,
        onChange: function(selectedDates, dateStr, instance) {
          // Ensure the input is properly updated and focusable
          instance.input.value = dateStr;
          instance.input.dispatchEvent(new Event('input', { bubbles: true }));
        }
      });

      flatpickr('.scheduleTime', {
        enableTime: true,
        noCalendar: true,
        dateFormat: 'H:i',
        time_24hr: true,
        defaultDate: new Date(),
        disableMobile: true,
        allowInput: true,
        clickOpens: true,
        onChange: function(selectedDates, dateStr, instance) {
          // Ensure the input is properly updated and focusable
          instance.input.value = dateStr;
          instance.input.dispatchEvent(new Event('input', { bubbles: true }));
        }
      });

      $('.scheduleDateSection').show();
      $('.scheduleTimeSection').show();
      $('#scheduleDate').prop('required', true);
      $('.scheduleTime').prop('required', true);
    } else {
      $('.scheduleMessage').val('0');
      $('.scheduleDateSection').hide();
      $('.scheduleTimeSection').hide();
      $('#scheduleDate').prop('required', false);
      $('.scheduleTime').prop('required', false);
    }
  });

  $('.maskingOptions').hide();
  $('.nonMaskingOptions').show();
  $('input[name="masking_type"]').on('change', function() {
    if ($(this).val() === 'Masking') {
      $('.senderIdNonMasking').prop('disabled', true);
      $('.senderIdMasking').prop('disabled', false);
      $('.nonMaskingOptions').hide().find('select').prop('required', false);
      $('.maskingOptions').show().find('select').prop('required', true);
    } else {
      $('.senderIdMasking').prop('disabled', true);
      $('.senderIdNonMasking').prop('disabled', false);
      $('.maskingOptions').hide().find('select').prop('required', false);
      $('.nonMaskingOptions').show().find('select').prop('required', true);
    }
  });


});
