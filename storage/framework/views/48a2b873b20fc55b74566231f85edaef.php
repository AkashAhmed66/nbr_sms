<?php $__env->startSection('title', $title); ?>

<!-- Vendor Styles -->
<?php $__env->startSection('vendor-style'); ?>
  <?php echo app('Illuminate\Foundation\Vite')([
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/@form-validation/form-validation.scss',
    'resources/assets/vendor/libs/animate-css/animate.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
  ]); ?>
<?php $__env->stopSection(); ?>

<!-- Vendor Scripts -->
<?php $__env->startSection('vendor-script'); ?>
  <?php echo app('Illuminate\Foundation\Vite')([
    'resources/assets/vendor/libs/moment/moment.js',
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js',
    'resources/assets/vendor/libs/cleavejs/cleave.js',
    'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
  ]); ?>
<?php $__env->stopSection(); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- jsPDF and autoTable for PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
<!-- SheetJS for Excel generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<?php $__env->startSection('page-script'); ?>
  <script>
    const tableHeaders = <?php echo json_encode($tableHeaders, 15, 512) ?>;
    const ajaxUrl = <?php echo json_encode($ajaxUrl, 15, 512) ?>;
    const title = <?php echo json_encode($title, 15, 512) ?>;
	$(document).ready(function () {
      // Handle form submission
      $(".dt_adv_search").on("submit", function (e) {
        e.preventDefault();

        var formData = $(this).serialize(); // Get form data
        console.log(formData);
        $('#datatable').DataTable().ajax.url(ajaxUrl + '?' + formData).load(); // Reload table with new data
      });

      // Add PDF and Excel buttons after DataTable initialization
      setTimeout(function() {
        if (!$('.dt-action-buttons .export-buttons').length) {
          $('.dt-action-buttons').prepend(
            '<div class="export-buttons d-flex align-items-center me-3">' +
            '<button type="button" id="download-pdf" class="btn btn-sm btn-outline-success me-6">' +
            '<i class="ri-file-pdf-line me-1"></i>PDF' +
            '</button>' +
            '<button type="button" id="download-excel" class="btn btn-sm btn-outline-primary">' +
            '<i class="ri-file-excel-line me-1"></i>Excel' +
            '</button>' +
            '</div>'
          );
        }
      }, 100);

      // Handle PDF download (using event delegation)
      $(document).on('click', '#download-pdf', function() {
        downloadPDF();
      });

      // Handle Excel download (using event delegation)
      $(document).on('click', '#download-excel', function() {
        downloadExcel();
      });
    });

    async function downloadPDF() {
      try {
        // Show loading state
        $('#download-pdf').prop('disabled', true).html('<i class="ri-loader-line me-1"></i>Loading...');
        
        // Get current form data
        var formData = $('.dt_adv_search').serialize();
        
        // Fetch all data from server (without pagination)
        const response = await fetch(ajaxUrl + '?' + formData + '&length=-1&draw=1&start=0', {
          method: 'GET',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        
        if (!response.ok) {
          throw new Error('Failed to fetch data');
        }
        
        const result = await response.json();
        const data = result.data || [];
        
        if (data.length === 0) {
          Swal.fire({
            icon: 'warning',
            title: 'No Data',
            text: 'No data available to export'
          });
          return;
        }
        
        // Initialize jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4'); // landscape orientation
        
        // Add title
        doc.setFontSize(16);
        doc.setFont(undefined, 'bold');
        doc.text(title || 'Day Wise Log Report', 15, 15);
        
        // Add date range if available
        const fromDate = $('input[name="from_date"]').val();
        const toDate = $('input[name="to_date"]').val();
        if (fromDate && toDate) {
          doc.setFontSize(12);
          doc.setFont(undefined, 'normal');
          doc.text(`Period: ${fromDate} to ${toDate}`, 15, 25);
        }
        
        // Prepare table headers
        const headers = [];
        headers.push('#'); // Serial number
        Object.values(tableHeaders).forEach(header => {
          headers.push(header);
        });
        
        // Prepare table data
        const tableData = [];
        data.forEach((row, index) => {
          const rowData = [];
          rowData.push(index + 1); // Serial number
          
          Object.keys(tableHeaders).forEach(key => {
            let cellValue = row[key] || '';
            // Convert to string and handle long text
            cellValue = String(cellValue);
            if (cellValue.length > 50) {
              cellValue = cellValue.substring(0, 47) + '...';
            }
            rowData.push(cellValue);
          });
          
          tableData.push(rowData);
        });
        
        // Generate table with autoTable
        doc.autoTable({
          head: [headers],
          body: tableData,
          startY: fromDate && toDate ? 35 : 25,
          theme: 'striped',
          headStyles: {
            fillColor: [105, 108, 255], // Primary color
            textColor: 255,
            fontStyle: 'bold',
            fontSize: 8
          },
          bodyStyles: {
            fontSize: 7,
            cellPadding: 2
          },
          columnStyles: {
            0: { cellWidth: 10, halign: 'center' } // Serial number column
          },
          styles: {
            overflow: 'linebreak',
            cellWidth: 'wrap'
          },
          margin: { top: 10, left: 10, right: 10 },
          didDrawPage: function (data) {
            // Add page number
            const pageCount = doc.internal.getNumberOfPages();
            const currentPage = doc.internal.getCurrentPageInfo().pageNumber;
            doc.setFontSize(8);
            doc.text(
              `Page ${currentPage} of ${pageCount}`,
              data.settings.margin.left,
              doc.internal.pageSize.height - 10
            );
          }
        });
        
        // Generate filename with timestamp
        const now = new Date();
        const timestamp = now.getFullYear() + 
          String(now.getMonth() + 1).padStart(2, '0') + 
          String(now.getDate()).padStart(2, '0') + '_' +
          String(now.getHours()).padStart(2, '0') + 
          String(now.getMinutes()).padStart(2, '0');
        
        const filename = `day_wise_log_${timestamp}.pdf`;
        
        // Save the PDF
        doc.save(filename);
        
        // Show success message
        Swal.fire({
          icon: 'success',
          title: 'PDF Generated!',
          text: `Report exported successfully as ${filename}`,
          timer: 3000,
          showConfirmButton: false
        });
        
      } catch (error) {
        console.error('PDF generation error:', error);
        Swal.fire({
          icon: 'error',
          title: 'Export Failed',
          text: 'Failed to generate PDF. Please try again.'
        });
      } finally {
        // Reset button state
        $('#download-pdf').prop('disabled', false).html('<i class="ri-file-pdf-line me-1"></i>PDF');
      }
    }

    async function downloadExcel() {
      try {
        // Show loading state
        $('#download-excel').prop('disabled', true).html('<i class="ri-loader-line me-1"></i>Loading...');
        
        // Get current form data
        var formData = $('.dt_adv_search').serialize();
        
        // Fetch all data from server (without pagination)
        const response = await fetch(ajaxUrl + '?' + formData + '&length=-1&draw=1&start=0', {
          method: 'GET',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        
        if (!response.ok) {
          throw new Error('Failed to fetch data');
        }
        
        const result = await response.json();
        const data = result.data || [];
        
        if (data.length === 0) {
          Swal.fire({
            icon: 'warning',
            title: 'No Data',
            text: 'No data available to export'
          });
          return;
        }
        
        // Prepare Excel data
        const worksheet_data = [];
        
        // Add title row
        worksheet_data.push([title || 'Day Wise Log Report']);
        
        // Add date range if available
        const fromDate = $('input[name="from_date"]').val();
        const toDate = $('input[name="to_date"]').val();
        if (fromDate && toDate) {
          worksheet_data.push([`Period: ${fromDate} to ${toDate}`]);
        }
        
        // Add empty row
        worksheet_data.push([]);
        
        // Add headers
        const headers = ['#']; // Serial number
        Object.values(tableHeaders).forEach(header => {
          headers.push(header);
        });
        worksheet_data.push(headers);
        
        // Add data rows
        data.forEach((row, index) => {
          const rowData = [index + 1]; // Serial number
          Object.keys(tableHeaders).forEach(key => {
            let cellValue = row[key] || '';
            // Convert to string
            cellValue = String(cellValue);
            rowData.push(cellValue);
          });
          worksheet_data.push(rowData);
        });
        
        // Create workbook and worksheet
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet(worksheet_data);
        
        // Set column widths
        const colWidths = [];
        colWidths.push({wch: 5}); // Serial number column
        Object.keys(tableHeaders).forEach(() => {
          colWidths.push({wch: 15}); // Default width for other columns
        });
        ws['!cols'] = colWidths;
        
        // Style the header row
        const headerRowIndex = fromDate && toDate ? 3 : 2; // Adjust based on whether date range is shown
        for (let col = 0; col < headers.length; col++) {
          const cellAddress = XLSX.utils.encode_cell({r: headerRowIndex, c: col});
          if (!ws[cellAddress]) ws[cellAddress] = {};
          ws[cellAddress].s = {
            font: { bold: true },
            fill: { fgColor: { rgb: "4F81BD" } },
            alignment: { horizontal: "center" }
          };
        }
        
        // Add worksheet to workbook
        XLSX.utils.book_append_sheet(wb, ws, 'Day Wise Log');
        
        // Generate filename with timestamp
        const now = new Date();
        const timestamp = now.getFullYear() + 
          String(now.getMonth() + 1).padStart(2, '0') + 
          String(now.getDate()).padStart(2, '0') + '_' +
          String(now.getHours()).padStart(2, '0') + 
          String(now.getMinutes()).padStart(2, '0');
        
        const filename = `day_wise_log_${timestamp}.xlsx`;
        
        // Save the Excel file
        XLSX.writeFile(wb, filename);
        
        // Show success message
        Swal.fire({
          icon: 'success',
          title: 'Excel Generated!',
          text: `Report exported successfully as ${filename}`,
          timer: 3000,
          showConfirmButton: false
        });
        
      } catch (error) {
        console.error('Excel generation error:', error);
        Swal.fire({
          icon: 'error',
          title: 'Export Failed',
          text: 'Failed to generate Excel file. Please try again.'
        });
      } finally {
        // Reset button state
        $('#download-excel').prop('disabled', false).html('<i class="ri-file-excel-line me-1"></i>Excel');
      }
    }
  </script>
  <?php echo app('Illuminate\Foundation\Vite')(['resources/js/day-wise-log-table.js']); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
  <div class="card">
<div class="card-header pb-0">

      <!--Search Form -->
      <form class="dt_adv_search">
        <div class="row">
          <div class="col-12">
            <div class="row g-5">
              <div class="col-12 col-sm-6 col-lg-3">
                <div class="form-floating form-floating-outline">
                  <input type="datetime-local" class="form-control dt-input" name="from_date"
                    value="<?php echo e(\Carbon\Carbon::now('Asia/Dhaka')->startOfDay()->format('Y-m-d\TH:i')); ?>"
                    max="<?php echo e(\Carbon\Carbon::now('Asia/Dhaka')->format('Y-m-d\TH:i')); ?>">
                  <label>Date From</label>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-lg-3">
                <div class="form-floating form-floating-outline">
                  <input type="datetime-local" class="form-control dt-input" name="to_date"
                    value="<?php echo e(\Carbon\Carbon::now('Asia/Dhaka')->format('Y-m-d\TH:i')); ?>"
                    max="<?php echo e(\Carbon\Carbon::now('Asia/Dhaka')->format('Y-m-d\TH:i')); ?>">
                  <label>Date To</label>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-lg-3">
                <div class="form-floating form-floating-outline">
                  <select id="add-user" name="user_id" class="select2 form-select dt-input">
                      <option value="">Select</option>
                      <?php $__currentLoopData = $userLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <option value="<?php echo e($userList->id); ?>"><?php echo e($userList->name); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </select>
                  <label for="country">User</label>
                </div>
              </div>
			        <div class="col-12 col-sm-6 col-lg-3">
                <div class="form-floating form-floating-outline">
                  <button type="submit" class="btn btn-primary me-sm-3 me-1 mt-1 data-submit">Submit</button>
                </div>
              </div>
            </div>

          </div>
        </div>
      </form>
    </div>
    <div class="card-datatable table-responsive">
      <table class="dt-advanced-search table table-bordered" id="datatable">
        <thead>
        <tr>
          <?php $__currentLoopData = $tableHeaders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$tableHeader): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <th><?php echo e($tableHeader); ?></th>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tr>
        </thead>
      </table>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<style>
  #datatable td {
    padding-top: 3px !important;
    padding-bottom: 3px !important;
    line-height: 2 !important;
    white-space: nowrap !important;
    vertical-align: middle !important;
  }
  #datatable thead th {
    padding-top: 3px !important;
    padding-bottom: 3px !important;
    height: 35px !important;
    white-space: nowrap !important;
    vertical-align: middle !important;
  }
  #users-table td:first-child,
  #users-table thead th:first-child {
    width: 20px !important;
    text-align: center !important;
    font-size: 0.95em !important;
    padding-left: 6px !important;
    padding-right: 6px !important;
  }
  #datatable tbody tr:nth-child(odd) td {
    background-color: #f8f9fa !important;
  }
</style>

<?php echo $__env->make('layouts/layoutMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\metronetsms\Modules/Reports\resources/views/day-wise-log.blade.php ENDPATH**/ ?>