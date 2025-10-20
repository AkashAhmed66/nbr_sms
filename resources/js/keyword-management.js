/**
 * Page User List
 */

'use strict';

$(function () {
  var offCanvasForm = $('#offcanvasAddUser');

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  var isEditMode = false; // Track if it's an edit operation
  var keyWordId = null; // Store the current operator ID for edit

  // Tag management logic
  let tags = [];

  function createTag(label) {
    const div = document.createElement('div');
    div.setAttribute('class', 'tag');
    const span = document.createElement('span');
    span.innerHTML = label;
    const close = document.createElement('span');
    close.innerHTML = '&times;';
    close.setAttribute('class', 'close');
    close.setAttribute('data-item', label);
    div.appendChild(span);
    div.appendChild(close);
    return div;
  }

  function resetTags() {
    document.querySelectorAll('.tag').forEach(tag => tag.remove());
  }

  function addTags() {
    resetTags();
    tags.slice().reverse().forEach(tag => {
      const tagElement = createTag(tag);
      document.querySelector('.tag-container').prepend(tagElement);
    });
    document.querySelector('#add-keywords').value = tags.join(',');
  }

  function handleTagInput(event) {
    const tag = event.target.value.trim();
    // Ensure the new tag is unique and non-empty
    if (tag.length > 0 && !tags.includes(tag)) {
      tags.push(tag); // Append new tag to the tags array
      addTags(); // Re-render all tags
    }
    event.target.value = ''; // Clear the input field after adding the tag
  }

  // Event to handle tag input and add on 'Enter' or 'Space'
  document.querySelector('#tag-input').addEventListener('keypress', function (e) {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault(); // Prevent unnecessary space or enter from being added
      handleTagInput(e);
    }
  });

  // Handle click event for closing/removing a tag
  document.addEventListener('click', function (e) {
    if (e.target.classList.contains('close')) {
      const tagLabel = e.target.getAttribute('data-item');
      const index = tags.indexOf(tagLabel);
      tags.splice(index, 1); // Remove the clicked tag
      addTags(); // Re-render all tags
    }
  });

// Delete Record
  $(document).on('click', '.delete-record', function () {
    var button = $(this);
    var keyword_id = button.data('id');
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
          url: `${baseUrl}sms-config/keyword-delete/${keyword_id}`,
          success: function (response) {
            window.location.href = `${baseUrl}sms-config/keyword-list`;
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
          text: 'The Keyword list has been deleted!',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      } else if (result.dismiss === Swal.DismissReason.cancel) {
        Swal.fire({
          title: 'Cancelled',
          text: 'The Keyword list is not deleted!',
          icon: 'error',
          customClass: {
            confirmButton: 'btn btn-success'
          }
        });
      }
    });
  });
  // Edit record event
  $(document).on('click', '.edit-record', function () {
    var keyword_id = $(this).data('id');
    isEditMode = true;
    keyWordId = keyword_id; // Store the operator ID

    // Get data from the server for editing
    $.get(`${baseUrl}sms-config/keyword\/${keyword_id}\/edit`, function (data) {
      let jsonData;
      try {
        jsonData = typeof data === 'string' ? JSON.parse(data) : data;
      } catch (e) {
        console.error('Failed to parse JSON:', e);
        return;
      }

      // Populate the title field
      $('#add-title').val(jsonData.title);

      // Split keywords string from the server into an array and ensure existing tags are kept
      tags = jsonData.keywords.split(',').map(tag => tag.trim());
      addTags();  // Render existing tags from the database
    });
  });

  // Form validation and submission
  const addNewBlackListForm = document.getElementById('addNewBlackListForm');

  const fv = FormValidation.formValidation(addNewBlackListForm, {
    fields: {
      title: {
        validators: {
          notEmpty: {
            message: 'Please enter title'
          }
        }
      },
      keywords: {
        validators: {
          notEmpty: {
            message: 'Please enter keywords'
          }
        }
      }
    },
    plugins: {
      trigger: new FormValidation.plugins.Trigger(),
      bootstrap5: new FormValidation.plugins.Bootstrap5({
        eleValidClass: '',
        rowSelector: function (field, ele) {
          return '.mb-5';
        }
      }),
      submitButton: new FormValidation.plugins.SubmitButton(),
      autoFocus: new FormValidation.plugins.AutoFocus()
    }
  }).on('core.form.valid', function () {
    var url = isEditMode ? `${baseUrl}sms-config/keyword-update/${keyWordId}` : `${baseUrl}sms-config/keyword-store`;
    var method = isEditMode ? 'PUT' : 'POST';

    // Submit the form
    $.ajax({
      data: $('#addNewBlackListForm').serialize(),
      url: url,
      type: method,
      success: function (response) {
        offCanvasForm.offcanvas('hide');

        Swal.fire({
          icon: 'success',
          title: `Successfully ${response.status}!`,
          text: `Keyword list ${response.status} Successfully.`,
          customClass: {
            confirmButton: 'btn btn-success'
          }
        }).then(() => {
          window.location.href = `${baseUrl}sms-config/keyword-list`;
        });

        isEditMode = false; // Reset the edit mode
        keyWordId = null; // Reset the operator ID
      },
      error: function (err) {
        console.log(err.responseText);
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

  // Clear form data and tags when offcanvas is hidden
  offCanvasForm.on('hidden.bs.offcanvas', function () {
    fv.resetForm(true);
    isEditMode = false; // Reset the edit mode
    keyWordId = null; // Clear the stored operator ID
    tags = []; // Reset the tags array
    resetTags(); // Clear the tags in the UI
  });

});
