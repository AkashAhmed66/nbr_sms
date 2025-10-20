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
  </script>
  @vite(['resources/js/report-running-list-table.js'])
@endsection

@section('content')
  <!-- Filter Card -->
  <div class="card mb-4">
    <div class="card-header">
      <h5 class="card-title mb-0">Search Filters</h5>
    </div>
    <div class="card-body">
      <form id="filterForm" class="row g-3">
        <div class="col-md-3">
          <label class="form-label" for="from_date">From Date</label>
          <input type="date" id="from_date" name="from_date" class="form-control" />
        </div>
        <div class="col-md-3">
          <label class="form-label" for="to_date">To Date</label>
          <input type="date" id="to_date" name="to_date" class="form-control" />
        </div>
        <div class="col-md-3">
          <label class="form-label" for="campaign_id">Campaign ID</label>
          <select id="campaign_id" name="campaign_id" class="form-select">
            <option value="">Select Campaign</option>
            @if(isset($campaigns))
              @foreach($campaigns as $campaign)
                <option value="{{ $campaign }}">{{ $campaign }}</option>
              @endforeach
            @endif
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label" for="user_id">User</label>
          <select id="user_id" name="user_id" class="form-select">
            <option value="">Select User</option>
            @if(isset($users))
              @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->username }})</option>
              @endforeach
            @endif
          </select>
        </div>
        <div class="col-12">
          <button type="button" id="filterBtn" class="btn btn-primary me-2">
            <i class="ri-search-line me-1"></i>Apply Filter
          </button>
          <button type="button" id="resetBtn" class="btn btn-outline-secondary">
            <i class="ri-refresh-line me-1"></i>Reset
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Data Table Card -->
  <div class="card">
    <div class="card-header pb-0">
      <h5 class="card-title mb-0">Running Campaigns</h5>
    </div>
    <div class="card-datatable table-responsive">
      <table class="datatables-users table" id="datatable">
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
