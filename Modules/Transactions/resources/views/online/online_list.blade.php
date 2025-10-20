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

@section('page-script')
  <script>
    const tableHeaders = @json($tableHeaders);
    const ajaxUrl = @json($ajaxUrl);
    const title = @json($title);
    const userInfo = @json($userInfo);
    const userGroup = {{ auth()->user()->id_user_group; }};
  </script>
 @vite(['resources/js/online-list-table.js', 'resources/js/online-management.js'])

@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    console.log('Add Online Transaction Form Loaded');
    $('.select2-transaction').select2({
      placeholder: 'Select an option',
      allowClear: true
    });
  });
</script>
@section('content')
  <div class="card">

    <div class="card-datatable table-responsive">
      <table class="dt-advanced-search table table-bordered" id="datatable">
        <thead>
        <tr>
          @foreach($tableHeaders as $key=>$tableHeader)
            <th>{{ $tableHeader }}</th>
          @endforeach
        </tr>
        </thead>
      </table>
    </div>
  </div>
@endsection

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
  #datatable td:first-child,
  #datatable thead th:first-child {
    width: 60px !important;
    text-align: center !important;
    font-size: 0.95em !important;
    padding-left: 6px !important;
    padding-right: 6px !important;
  }
  #datatable tbody tr:nth-child(odd) td {
    background-color: #f8f9fa !important;
  }
</style>
