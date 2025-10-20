/**
 * Page User List
 */

'use strict';

$(function () {
    var offCanvasForm = $('#offcanvasAddRecord');

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  var isEditMode = false; // Track if it's an edit operation
  var rateId = null; // Store the current operator ID for edit

  // Delete Record
  $(document).on('click', '.delete-record', function () {
    var button = $(this);
    var rate_id = button.data('id');
    var url = button.data('route');
  
    // sweetalert for confirmation of delete
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, delete it!',
      customClass: {
        confirmButton: 'btn btn-primary me-3',
        cancelButton: 'btn btn-label-secondary'
      },
      buttonsStyling: false
    }).then(function (result) {
      if (result.value) {
        // delete the data
        $.ajax({
          type: 'DELETE',
          url: `${baseUrl}sms-config/rate-delete/${rate_id}`,
          success: function (response) {
            window.location.href = `${baseUrl}sms-config/rate-list`;
            dt_user.draw();
          },
          error: function (error) {
            console.log(error);
          }
        });

        // success sweetalert
        Swal.fire({
          icon: 'success',
          title: 'Deleted!',
          text: 'The Rate has been deleted!',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: 'The Rate is not deleted!',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });

  // edit record
  $(document).on('click', '.edit-record', function () {
      var rate_id = $(this).data('id');

      isEditMode = true;
      rateId = rate_id; // Store the operator ID

    // get data
    $.get(`${baseUrl}sms-config/rate\/${rate_id}\/edit`, function (data) {
        // Check if the data is a string and needs to be parsed
      let jsonData;
      try {
          jsonData = typeof data === 'string' ? JSON.parse(data) : data;
      } catch (e) {
          console.error('Failed to parse JSON:', e);
          return;
      }
      
      
      //$('#add-operator-id').val(jsonData.id);
      $('#add-rate-name').val(jsonData.rate_name);
      $('#masking-rate').val(jsonData.masking_rate);
      $('#nonmasking-rate').val(jsonData.nonmasking_rate);
      //$('#add-selling-nonmasking-rate').val(jsonData.selling_nonmasking_rate);
      //$('#add-email-rate').val(jsonData.email_rate);
      //$('#add-rate-type').val(jsonData.rate_type).trigger('change');
  
      //console.log('Full Name:', jsonData.iso);
    });
  });

  // validating form and updating user's data
  const addNewRateForm = document.getElementById('addNewRateForm');

  // user form validation
  const fv = FormValidation.formValidation(addNewRateForm, {
    fields: {
      rate_name: {
        validators: {
          notEmpty: {
            message: 'Please enter rate name'
          }
        }
      },
      masking_rate: {
        validators: {
          notEmpty: {
            message: 'Please enter masking rate'
          },
          numeric: {
            message: 'The masking rate must be a number'
          }
        }
      },
      nonmasking_rate: {
        validators: {
          notEmpty: {
            message: 'Please enter nonmasking rate'
          },
          numeric: {
            message: 'The nonmasking rate must be a number'
          }
        }
      }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        // Use this for enabling/changing valid/invalid class
        eleValidClass: '',
        rowSelector: function (field, ele) {
          // field is the field name & ele is the field element
          return '.mb-5';
        }
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      // Submit the form when all fields are valid
      // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  }).on('core.form.valid', function () {

    //console.log(countryId);
    //console.log($('#addNewOperatorForm').serialize());

    var url = isEditMode ? `${baseUrl}sms-config/rate-update/${rateId}` : `${baseUrl}sms-config/rate-store`;
    var method = isEditMode ? 'PUT' : 'POST';
    //console.log($('#addNewOperatorForm').serialize());
    // adding or updating user when form successfully validate
    $.ajax({
      data: $('#addNewRateForm').serialize(),
      url: url,
      type: method,
      success: function (response) {
		  
		  console.log(response);
        //datatable.draw();
        offCanvasForm.offcanvas('hide');

        // sweetalert
        Swal.fire({
          icon: 'success',
          title: `Successfully ${response.status}!`,
          text: `Rate ${response.status} Successfully.`,
          customClass: {
            confirmButton: 'btn btn-success'
          }
        }).then(() => {
          // Redirect or reload after the alert
          window.location.href = `${baseUrl}sms-config/rate-list`;
        });
        isEditMode = false; // Reset the edit mode
        rateId = null; // Reset the operator ID
      },
      error: function (err) {
		  console.log(err.responseText); // This will give you more details about the error
        offCanvasForm.offcanvas('hide');
        Swal.fire({
          title: 'Error',
          text: 'Something went wrong, please try again.',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });

  // clearing form data when offcanvas hidden
  offCanvasForm.on('hidden.bs.offcanvas', function () {
    fv.resetForm(true);
    isEditMode = false; // Reset the edit mode
    rateId = null; // Clear the stored operator ID
  });

});
