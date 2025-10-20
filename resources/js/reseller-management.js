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
  var resellerId = null; // Store the current operator ID for edit

  // Delete Record
  $(document).on('click', '.delete-record', function () {
    var button = $(this);
    var reseller_id = button.data('id');
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
          url: `${baseUrl}users/reseller-delete/${reseller_id}`,
          success: function () {
            window.location.href = `${baseUrl}users/reseller-list`;
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
          text: 'The reseller has been deleted!',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: 'The reseller is not deleted!',
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
      var reseller_id = $(this).data('id');

      isEditMode = true;
      resellerId = reseller_id; // Store the operator ID

    // get data
    $.get(`${baseUrl}users/reseller\/${reseller_id}\/edit`, function (data) {
        // Check if the data is a string and needs to be parsed
      let jsonData;
      try {
          jsonData = typeof data === 'string' ? JSON.parse(data) : data;
      } catch (e) {
          console.error('Failed to parse JSON:', e);
          return;
      }
      
      
      //$('#add-operator-id').val(jsonData.id);
      $('#add-reseller-name').val(jsonData.reseller_name);
      $('#add-phone').val(jsonData.phone);
      $('#add-sms-rate').val(jsonData.sms_rate_id).trigger('change');
      $('#add-email').val(jsonData.email);
      $('#add-address').val(jsonData.address);
      $('#add-thana').val(jsonData.thana);
      $('#add-district').val(jsonData.district);
      $('#add-tps').val(jsonData.tps);
      $('#add-url').val(jsonData.district);
      //console.log('Full Name:', jsonData.full_name);
    });
  });

  // validating form and updating user's data
  const addNewResellerForm = document.getElementById('addNewResellerForm');

  // user form validation
  const fv = FormValidation.formValidation(addNewResellerForm, {
    fields: {
      reseller_name: {
        validators: {
          notEmpty: {
            message: 'Please enter name'
          }
        }
      },
      phone: {
        validators: {
          notEmpty: {
            message: 'Please enter phone'
          }
        }
      },
      email: {
        validators: {
          notEmpty: {
            message: 'Please enter email'
          }
        }
      },
      address: {
        validators: {
          notEmpty: {
            message: 'Please enter address'
          }
        }
      },
      thana: {
        validators: {
          notEmpty: {
            message: 'Please enter thana'
          }
        }
      },
      district: {
        validators: {
          notEmpty: {
            message: 'Please enter district'
          }
        }
      },
      sms_rate_id: {
        validators: {
          notEmpty: {
            message: 'Please enter sms rate'
          }
        }
      },
      tps: {
        validators: {
          notEmpty: {
            message: 'Please enter tps'
          }
        }
      },
      url: {
        validators: {
          notEmpty: {
            message: 'Please enter url'
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

    var url = isEditMode ? `${baseUrl}users/reseller-update/${resellerId}` : `${baseUrl}users/reseller-store`;
    var method = isEditMode ? 'PUT' : 'POST';
    //console.log($('#addNewOperatorForm').serialize());
    // adding or updating user when form successfully validate
    $.ajax({
      data: $('#addNewResellerForm').serialize(),
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
          text: `Reseller ${response.status} Successfully.`,
          customClass: {
            confirmButton: 'btn btn-success'
          }
        }).then(() => {
          // Redirect or reload after the alert
          window.location.href = `${baseUrl}users/reseller-list`;
        });
        isEditMode = false; // Reset the edit mode
        resellerId = null; // Reset the operator ID
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
    resellerId = null; // Clear the stored operator ID
  });

});
