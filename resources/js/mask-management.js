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
  var mask = null; // Store the current operator ID for edit

  // Delete Record
  $(document).on('click', '.delete-record', function () {
    var button = $(this);
    var mask_id = button.data('id');

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
          url: `${baseUrl}sms-config/mask-delete/${mask_id}`,
          success: function (response) {
            window.location.href = `${baseUrl}sms-config/mask-list`;
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
          text: 'Mask has been deleted!',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: 'Mask is not deleted!',
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
    var mask_id = $(this).data('id');

    isEditMode = true;
    mask = mask_id; // Store the operator ID

    // get data
    $.get(`${baseUrl}sms-config/mask\/${mask_id}\/edit`, function (data) {
      // Check if the data is a string and needs to be parsed
      let jsonData;
      try {
        jsonData = typeof data === 'string' ? JSON.parse(data) : data;
      } catch (e) {
        console.error('Failed to parse JSON:', e);
        return;
      }

      $('#add-mask').val(jsonData.mask);
    });
  });

  // validating form and updating user's data
  const addNewMaskForm = document.getElementById('addNewMaskForm');

  // user form validation
  const fv = FormValidation.formValidation(addNewMaskForm, {
    fields: {
      mask: {
        validators: {
          notEmpty: {
            message: 'Please enter mask'
          },
          stringLength: {
            min: 3,
            max: 12,
            message: 'Mask must be min 3 and max 12 characters long'
          },
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

    var url = isEditMode ? `${baseUrl}sms-config/mask-update/${mask}` : `${baseUrl}sms-config/mask-store`;
    var method = isEditMode ? 'PUT' : 'POST';
    //console.log($('#addNewOperatorForm').serialize());
    // adding or updating user when form successfully validate
    $.ajax({
      data: $('#addNewMaskForm').serialize(),
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
          text: `Mask ${response.status} Successfully.`,
          customClass: {
            confirmButton: 'btn btn-success'
          }
        }).then(() => {
          // Redirect or reload after the alert
          window.location.href = `${baseUrl}sms-config/mask-list`;
        });
        isEditMode = false; // Reset the edit mode
        mask = null; // Reset the operator ID
      },
      error: function (err) {
        let errorMsg = 'An error occurred';
        if (err.responseJSON && err.responseJSON.message) {
          errorMsg = err.responseJSON.message;
        } else if (err.responseText) {
          errorMsg = err.responseText;
        }
        // offCanvasForm.offcanvas('hide');
        Swal.fire({
          title: 'Error',
          text: errorMsg,
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
    mask = null; // Clear the stored operator ID
  });

});
