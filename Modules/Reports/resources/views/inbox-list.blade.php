@extends('layouts/layoutMaster')

@section('title', $title)

<!-- Vendor Styles -->
@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss',
    'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/@form-validation/form-validation.scss',
    'resources/assets/vendor/libs/animate-css/animate.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss'
  ])
@endsection

<!-- Vendor Scripts -->
@section('vendor-script')
  @vite([
    'resources/assets/vendor/libs/moment/moment.js',
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js',
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/@form-validation/popular.js',
    'resources/assets/vendor/libs/@form-validation/bootstrap5.js',
    'resources/assets/vendor/libs/@form-validation/auto-focus.js',
    'resources/assets/vendor/libs/cleavejs/cleave.js',
    'resources/assets/vendor/libs/cleavejs/cleave-phone.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js'
  ])
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


@section('content')
  <div class="card">
    <div class="card-header pb-0">

      <!--Search Form -->
      <form class="dt_adv_search">
        <div class="row">
          <div class="col-12">
            <div class="row g-5">
              <div class="col-12 col-sm-6 col-lg-5">
                <div class="form-floating form-floating-outline">
                  <input type="datetime-local" value="{{ \Carbon\Carbon::now('Asia/Dhaka')->startOfDay()->format('Y-m-d\TH:i') }}" class="form-control dt-input" name="from_date"
                    max="{{ \Carbon\Carbon::now('Asia/Dhaka')->format('Y-m-d\TH:i') }}">
                  <label>Date From</label>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-lg-5">
                <div class="form-floating form-floating-outline">
                  <input type="datetime-local" value="{{ \Carbon\Carbon::now('Asia/Dhaka')->format('Y-m-d\TH:i') }}" class="form-control dt-input" name="to_date"
                    max="{{ \Carbon\Carbon::now('Asia/Dhaka')->format('Y-m-d\TH:i') }}">
                  <label>Date To</label>
                </div>
              </div>
              <div class="col-12 col-sm-6 col-lg-2">
                <div class="form-floating form-floating-outline mt-1 d-flex gap-2">
                  <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">
                    <i class="ri ri-filter-2-line"></i> Filter
                  </button>
                  <a href="#" id="download-excel-inbox" class="btn btn-primary me-sm-3 me-1">
                    <i class="ri ri-file-excel-2-fill"></i> Download
                  </a>
                </div>
              </div>
            </div>

          </div>
        </div>
      </form>
    </div>


    <div class="card-datatable table-responsive">
    <table class="table table-bordered" id="users-table">
        <thead>
            <tr>
                <th>SL No</th>
                <th>Sender</th>
                <th>Receiver</th>
                <th>Operator</th>
                <th>Message</th>
                <th>Received Time</th>
            </tr>
        </thead>
    </table>
    </div>
  </div>
@endsection

<style>
  #users-table td {
    padding-top: 3px !important;
    padding-bottom: 3px !important;
    line-height: 2 !important;
    white-space: nowrap !important;
    vertical-align: middle !important;
  }
  #users-table thead th {
    padding-top: 3px !important;
    padding-bottom: 3px !important;
    height: 35px !important;
    white-space: nowrap !important;
    vertical-align: middle !important;
  }
  #users-table td:first-child,
  #users-table thead th:first-child {
    width: 60px !important;
    text-align: center !important;
    font-size: 0.95em !important;
    padding-left: 6px !important;
    padding-right: 6px !important;
  }
  #users-table tbody tr:nth-child(odd) td {
    background-color: #f8f9fa !important;
  }
</style>


<script>
$(document).ready(function () {
  var table = $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        dom: "<'row'<'col-12'<'row'<'col-sm-5 d-flex align-items-center ms-12'l><'col-sm-6 text-end'B>>>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        buttons: [
            {
                extend: 'collection',
                text: 'Export',
                className: 'btn dropdown-toggle btn-outline-secondary me-4 waves-effect waves-light mt-6',
                buttons: [
                    { extend: 'copy', text: 'Copy' },
                    { extend: 'csv', text: 'CSV' },
                    { extend: 'excel', text: 'Excel' },
                    { extend: 'pdf', text: 'PDF' },
                    { extend: 'print', text: 'Print' }
                ]
            }
        ],
        lengthMenu: [[15, 25, 50, 100, -1], [15, 25, 50, 100, "All"]],
        pageLength: 10, // Default selected value
        ajax: {
            url: '{{ url('/reports/inbox-report') }}',
            data: function (d) {
                d.from_date = $('input[name=from_date]').val();
                d.to_date = $('input[name=to_date]').val();
            }
        },
        columns: [
          { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'sender', name: 'sender' },
            { data: 'receiver', name: 'receiver' },
            { data: 'operator_prefix', name: 'operator_prefix' },
            { data: 'message', name: 'message' },
            { data: 'created_at', name: 'created_at' }
        ],
    });
    // Submit form and reload DataTable
    $('.dt_adv_search').on('submit', function (e) {
        e.preventDefault();
        table.draw();
    });

    // on click Download Excel ajax call
    $('#download-excel-inbox').on('click', function(e) {
      e.preventDefault();
      var formData = $('.dt_adv_search').serialize();
      window.location.href = '{{ route('inbox-export') }}?' + formData;
    });
});
</script>



