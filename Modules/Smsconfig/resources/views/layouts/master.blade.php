@extends('layouts/layoutMaster')

@section('title', 'User Management - Crud App')

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
  @vite(['resources/js/list-table.js', 'resources/js/keyword-management.js'])
@endsection

@section('content')
  <div class="card">
    <div class="card-header pb-0">
      <h5 class="card-title mb-0">Search Filter</h5>
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
    <!-- Offcanvas to add new user -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
      <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add User</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body mx-0 flex-grow-0 h-100">
        <form class="add-new-user pt-0" id="addNewUserForm">
          <input type="hidden" name="id" id="user_id">
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" class="form-control" id="add-user-fullname" placeholder="John Doe" name="name" aria-label="John Doe" />
            <label for="add-user-fullname">Full Name</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-user-email" class="form-control" placeholder="john.doe@example.com" aria-label="john.doe@example.com" name="email" />
            <label for="add-user-email">Email</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-user-contact" class="form-control phone-mask" placeholder="+1 (609) 988-44-11" aria-label="john.doe@example.com" name="userContact" />
            <label for="add-user-contact">Contact</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-user-company" name="company" class="form-control" placeholder="Web Developer" aria-label="jdoe1" />
            <label for="add-user-company">Company</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <select id="country" class="select2 form-select">
              <option value="">Select</option>
              <option value="Australia">Australia</option>
              <option value="Bangladesh">Bangladesh</option>
              <option value="Belarus">Belarus</option>
              <option value="Brazil">Brazil</option>
              <option value="Canada">Canada</option>
              <option value="China">China</option>
              <option value="France">France</option>
              <option value="Germany">Germany</option>
              <option value="India">India</option>
              <option value="Indonesia">Indonesia</option>
              <option value="Israel">Israel</option>
              <option value="Italy">Italy</option>
              <option value="Japan">Japan</option>
              <option value="Korea">Korea, Republic of</option>
              <option value="Mexico">Mexico</option>
              <option value="Philippines">Philippines</option>
              <option value="Russia">Russian Federation</option>
              <option value="South Africa">South Africa</option>
              <option value="Thailand">Thailand</option>
              <option value="Turkey">Turkey</option>
              <option value="Ukraine">Ukraine</option>
              <option value="United Arab Emirates">United Arab Emirates</option>
              <option value="United Kingdom">United Kingdom</option>
              <option value="United States">United States</option>
            </select>
            <label for="country">Country</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <select id="user-role" class="form-select">
              <option value="subscriber">Subscriber</option>
              <option value="editor">Editor</option>
              <option value="maintainer">Maintainer</option>
              <option value="author">Author</option>
              <option value="admin">Admin</option>
            </select>
            <label for="user-role">User Role</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <select id="user-plan" class="form-select">
              <option value="basic">Basic</option>
              <option value="enterprise">Enterprise</option>
              <option value="company">Company</option>
              <option value="team">Team</option>
            </select>
            <label for="user-plan">Select Plan</label>
          </div>
          <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
          <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </form>
      </div>
    </div>
  </div>
@endsection
