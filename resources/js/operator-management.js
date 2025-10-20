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
  var currentOperatorId = null; // Store the current operator ID for edit

  // Delete Record
  $(document).on('click', '.delete-record', function () {
    var button = $(this);
    var operator_id = button.data('id');
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
          url: `${baseUrl}sms-config/operator-delete/${operator_id}`,
          success: function (response) {
            window.location.href = `${baseUrl}sms-config/operator-list`;
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
          text: 'The Operator has been deleted!',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: 'The Operator is not deleted!',
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
      var operator_id = $(this).data('id');

      isEditMode = true;
      currentOperatorId = operator_id; // Store the operator ID

    // get data
    $.get(`${baseUrl}sms-config/operator\/${operator_id}\/edit`, function (data) {
        // Check if the data is a string and needs to be parsed
      let jsonData;
      try {
          jsonData = typeof data === 'string' ? JSON.parse(data) : data;
      } catch (e) {
          console.error('Failed to parse JSON:', e);
          return;
      }
      
      
      //$('#add-operator-id').val(jsonData.id);
      $('#add-user-fullname').val(jsonData.full_name);
      $('#add-shortname').val(jsonData.short_name);
      $('#add-prefix').val(jsonData.prefix);
      $('#add-ton').val(jsonData.ton);
      $('#country_id').val(jsonData.country_id).trigger('change');
      $('#add-npi').val(jsonData.npi);
      //console.log('Full Name:', jsonData.country_id);
    });
  });

  // validating form and updating user's data
  const addNewOperatorForm = document.getElementById('addNewOperatorForm');

  // user form validation
  const fv = FormValidation.formValidation(addNewOperatorForm, {
    fields: {
      full_name: {
        validators: {
          notEmpty: {
            message: 'Please enter full name'
          }
        }
      },
      short_name: {
        validators: {
          notEmpty: {
            message: 'Please enter short name'
          }
        }
      },
	  prefix: {
        validators: {
          notEmpty: {
            message: 'Please enter prefix'
          }
        }
      },
	  country_id: {
        validators: {
          notEmpty: {
            message: 'Please select country'
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

    //console.log(currentOperatorId);
    //console.log($('#addNewOperatorForm').serialize());

    var url = isEditMode ? `${baseUrl}sms-config/operator-update/${currentOperatorId}` : `${baseUrl}sms-config/operator-store`;
    var method = isEditMode ? 'PUT' : 'POST';
    //console.log($('#addNewOperatorForm').serialize());
    // adding or updating user when form successfully validate
    $.ajax({
      data: $('#addNewOperatorForm').serialize(),
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
          text: `Operator ${response.status} Successfully.`,
          customClass: {
            confirmButton: 'btn btn-success'
          }
        }).then(() => {
          // Redirect or reload after the alert
          window.location.href = `${baseUrl}sms-config/operator-list`;
        });
        isEditMode = false; // Reset the edit mode
        currentOperatorId = null; // Reset the operator ID
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
    currentOperatorId = null; // Clear the stored operator ID
  });

});
