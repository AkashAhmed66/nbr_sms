<!-- ADD NEW RECORD -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasOpenForm" aria-labelledby="offcanvasAddUserLabel">
  <div class="offcanvas-header border-bottom">
    <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Upload CSV/Excel</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body mx-0 flex-grow-0 h-100">
    <form class="add-new-user pt-0" id="importPhonebookGroupForm" enctype="multipart/form-data">

      <input type="hidden" name="id" id="user_id">
      <div class="form-floating form-floating-outline mb-5">
        <input type="text" class="form-control" id="" placeholder="" name="name" aria-label="John Doe" />
        <label for="add-name">Group Name</label>
      </div>

      <div class="form-floating form-floating-outline mb-5">
        <div class="mb-3">
          <label for="importFile" class="form-label">Import CSV/Excel</label>
          <input type="file" id="importFile" name="importFile" class="form-control" accept=".csv, .xls, .xlsx" required>
          <div class="text-start mt-2">
            <a href="javascript:void(0)" onclick="downloadExcel()" class="btn btn-sm btn-outline-primary">
              <i class="ri-download-line me-1"></i>Download Sample File
            </a>
          </div>
        </div>
        <div id="mobileCount" class="mt-2 text-muted"></div>
      </div>


      <!--<div class="form-floating form-floating-outline mb-5">
          <select id="add-type" name="type" class="select2 form-select">
            <option value="">Select</option>
            <option value="Public">Public</option>
            <option value="Private">Private</option>
          </select>
          <label for="country">Type</label>
        </div>
        <div class="form-floating form-floating-outline mb-5">
          <select id="add-reseller" name="reseller_id" class="select2 form-select">
            <option value="">Select</option>
            @foreach($resellers as $reseller)
        <option value="{{ $reseller->id }}">{{ $reseller->reseller_name }}</option>

      @endforeach
      </select>
      <label for="country">Reseller</label>
    </div>

    <div class="form-floating form-floating-outline mb-5">
      <select id="add-status" name="status" class="select2 form-select">
        <option value="">Select</option>
        <option value="Active">Active</option>
        <option value="Inactive">Inactive</option>
      </select>
      <label for="country">Status</label>
    </div>-->


      <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
      <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
    </form>
  </div>
</div>

<!-- Loader Overlay -->
<div id="pageLoader"
     style="display:none;position:fixed;top:0;left:0;z-index:9999;width:100vw;height:100vh;background:rgba(255,255,255,0.7);text-align:center;">
  <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);">
    <div class="spinner"
         style="border: 8px solid #f3f3f3;border-top: 8px solid #3498db;border-radius: 50%;width: 60px;height: 60px;animation: spin 1s linear infinite;"></div>
    <div style="margin-top:10px;">Processing file, please wait...</div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.1/xlsx.full.min.js"></script>

<script>
  $(document).ready(function() {

    // Show loader
    function showLoader() {
      $('#pageLoader').show();
    }

    // Hide loader
    function hideLoader() {
      $('#pageLoader').hide();
    }


    $(document).on('change', '#importFile', function(e) {
      // Get the file input element and the file
      var fileInput = $(this)[0];
      var file = fileInput.files[0];

      // Check if the file is an Excel or CSV file
      if (file) {
        var fileExtension = file.name.split('.').pop().toLowerCase();

        showLoader();

        if (fileExtension === 'xlsx' || fileExtension === 'xls') {
          // Handle Excel files
          var reader = new FileReader();

          reader.onload = function(event) {
            var data = event.target.result;

            // Parse the Excel file using SheetJS
            var workbook = XLSX.read(data, { type: 'binary' });
            var firstSheet = workbook.Sheets[workbook.SheetNames[0]];
            var json = XLSX.utils.sheet_to_json(firstSheet, { header: 1 }); // Read as array of arrays

            // Count the number of mobile numbers (assuming they are in the first column)
            var mobileNumbers = 0;
            json.forEach(function(row) {
              const val = row[0] && row[0].toString().trim();
              if (val && val.match(/^(8801\d{9}|01\d{9})$/)) {
                mobileNumbers++;
              }
            });

            // Display the count below the file input
            $('#mobileCount').text('Total mobile numbers: ' + mobileNumbers);
            hideLoader();
          };

          reader.onerror = function() {
            $('#mobileCount').text('Error reading Excel file.');
            hideLoader();
          };

          reader.readAsBinaryString(file);

        } else if (fileExtension === 'csv') {
          var reader = new FileReader();

          reader.onload = function(event) {
            try {
              var csvData = event.target.result;

              // Split the CSV content into lines
              var lines = csvData.split(/\r?\n/); // Handles both Windows (\r\n) and Unix (\n) line breaks
              var mobileNumbers = 0;

              // Define a regex for validating mobile numbers
              const mobileRegex = /^(\+88)?01[3-9]\d{8}$/;

              // Loop through each line
              lines.forEach(function(line) {
                var row = line.split(','); // Split the line into columns
                var phoneNumber = row[0]?.trim(); // Get the first column and remove extra spaces

                // Validate the phone number
                if (phoneNumber && mobileRegex.test(phoneNumber)) {
                  mobileNumbers++;
                }
              });

              // Display the count below the file input
              $('#mobileCount').text('Total mobile numbers: ' + mobileNumbers);
            } catch (error) {
              $('#mobileCount').text('Error reading CSV file.');
              console.error(error);
            }
          };

          reader.onerror = function() {
            $('#mobileCount').text('Error reading CSV file.');
            hideLoader();
          };

          reader.readAsText(file);
        }
      }
    });


    $(document).on('submit', '#importPhonebookGroupForm', function(e) {
      e.preventDefault();

      let formData = new FormData(this);

      $.ajax({
        url: `${baseUrl}phonebook/group-import`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          Swal.fire({
            icon: 'success',
            title: 'Import Successful',
            text: 'Group and contacts have been imported successfully.',
            customClass: {
              confirmButton: 'btn btn-success'
            }
          }).then(() => {
            window.location.href = `${baseUrl}phonebook/group-list`;
          });
        },
        error: function(err) {
          console.error(err.responseText);
          Swal.fire({
            title: 'Error',
            text: 'An error occurred during the import process.',
            icon: 'error',
            customClass: {
              confirmButton: 'btn btn-danger'
            }
          });
        }
      });
    });
  });

  function downloadExcel() {
    fetch('/phonebook/download-excel')
      .then(response => {
        if (response.ok) {
          return response.blob();
        }
        throw new Error('Failed to download the file.');
      })
      .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = url;
        a.download = 'samplefile.xlsx';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
      })
      .catch(error => {
        console.error(error);
        alert('Unable to download the file.');
      });
  }
</script>
