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
  var userId = null; // Store the current operator ID for edit

  // Delete Record
  $(document).on('click', '.delete-record', function () {
    var button = $(this);
    var user_id = button.data('id');
  
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
          url: `${baseUrl}transactions/uwallet-delete/${user_id}`,
          success: function (response) {
            window.location.href = `${baseUrl}transactions/uwallet-list`;
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
          text: 'Wallet has been deleted!',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: 'The User is not deleted!',
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
      var user_id = $(this).data('id');

      isEditMode = true;
      userId = user_id; // Store the operator ID

    // get data
    $.get(`${baseUrl}transactions/uwallet\/${user_id}\/edit`, function (data) {
        // Check if the data is a string and needs to be parsed
      let jsonData;
      try {
          jsonData = typeof data === 'string' ? JSON.parse(data) : data;
      } catch (e) {
          console.error('Failed to parse JSON:', e);
          return;
      }

      $('#add-user').val(jsonData.user_id).trigger('change');
      $('#add-non-masking-balance').val(jsonData.balance);
      $('#add-balance-type').val(jsonData.balance_type).trigger('change');

      //console.log('Full Name:', jsonData.available_balance);
    });
  });

  // validating form and updating user's data
  const addNewUsersWalletForm = document.getElementById('addNewUsersWalletForm');

  // user form validation
  const fv = FormValidation.formValidation(addNewUsersWalletForm, {
    fields: {
      user_id: {
        validators: {
          notEmpty: {
            message: 'Please enter user name'
          }
        }
      },
      balance: {
        validators: {
          notEmpty: {
            message: 'Please select balance'
          }
        }
      },
      balance_type: {
        validators: {
          notEmpty: {
            message: 'Please select masking balance'
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

    //console.log(resellerId);
    //console.log($('#addNewOperatorForm').serialize());

    var url = isEditMode ? `${baseUrl}transactions/uwallet-update/${userId}` : `${baseUrl}transactions/uwallet-store`;
    var method = isEditMode ? 'PUT' : 'POST';
    console.log($('#addNewUsersWalletForm').serialize());
    // adding or updating user when form successfully validate
    $.ajax({
      data: $('#addNewUsersWalletForm').serialize(),
      url: url,
      type: method,
      success: function (response) {
      console.log(response);
      offCanvasForm.offcanvas('hide');

      Swal.fire({
        icon: 'success',
        title: `Successfully ${response.status}!`,
        text: `Wallet ${response.status} Successfully.`,
        customClass: {
        confirmButton: 'btn btn-success'
        }
      }).then(() => {
        window.location.href = `${baseUrl}transactions/uwallet-list`;
      });
      isEditMode = false;
      userId = null;
      },
      error: function (err) {
      offCanvasForm.offcanvas('hide');
      let errorMsg = 'Something went wrong, please try again.';
      if (err.responseJSON && err.responseJSON.message) {
        errorMsg = err.responseJSON.message;
      } else if (err.responseText) {
        errorMsg = err.responseText;
      }
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
    userId = null; // Clear the stored operator ID
  });

});
