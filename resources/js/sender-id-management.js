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
  var senderId = null; // Store the current operator ID for edit

  // Delete Record
  $(document).on('click', '.delete-record', function () {
    var button = $(this);
    var sender_id = button.data('id');
  
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
          url: `${baseUrl}sms-config/sender-id-delete/${sender_id}`,
          success: function (response) {
            window.location.href = `${baseUrl}sms-config/sender-id-list`;
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
          text: 'Sender ID has been deleted!',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: 'Sender ID is not deleted!',
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
      var sender_id = $(this).data('id');

      isEditMode = true;
      senderId = sender_id; // Store the operator ID

    // get data
    $.get(`${baseUrl}sms-config/sender-id\/${sender_id}\/edit`, function (data) {
        // Check if the data is a string and needs to be parsed
      let jsonData;
      try {
          jsonData = typeof data === 'string' ? JSON.parse(data) : data;
      } catch (e) {
          console.error('Failed to parse JSON:', e);
          return;
      }
      
      
     
      $('#add-senderID').val(jsonData.senderID);
      $('#add-count').val(jsonData.count);
      $('#add-assigned-user-id').val(jsonData.assigned_user_id).trigger('change');
      
      //console.log('Full Name:', jsonData.count);
    });
  });

  // validating form and updating user's data
  const addNewSenderIDForm = document.getElementById('addNewSenderIDForm');

  // user form validation
  const fv = FormValidation.formValidation(addNewSenderIDForm, {
    fields: {
      senderID: {
        validators: {
          notEmpty: {
            message: 'Please enter senderID'
          },
          stringLength: {
            min: 13,
            max: 13,
            message: 'Sender ID must be max 13 characters long'
          },
        }
      },
      count: {
        validators: {
          notEmpty: {
            message: 'Please enter count'
          }
        }
      },
      assigned_user_id: {
        validators: {
          notEmpty: {
            message: 'Please enter assigned user'
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

    var url = isEditMode ? `${baseUrl}sms-config/sender-id-update/${senderId}` : `${baseUrl}sms-config/sender-id-store`;
    var method = isEditMode ? 'PUT' : 'POST';
    //console.log($('#addNewOperatorForm').serialize());
    // adding or updating user when form successfully validate
    $.ajax({
      data: $('#addNewSenderIDForm').serialize(),
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
          text: `Sender ID ${response.status} Successfully.`,
          customClass: {
            confirmButton: 'btn btn-success'
          }
        }).then(() => {
          // Redirect or reload after the alert
          window.location.href = `${baseUrl}sms-config/sender-id-list`;
        });
        isEditMode = false; // Reset the edit mode
        senderId = null; // Reset the operator ID
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
    senderId = null; // Clear the stored operator ID
  });

});
