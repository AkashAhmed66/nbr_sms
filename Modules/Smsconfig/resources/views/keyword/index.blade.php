@extends('layouts/layoutMaster')

@section('title', $title)

<!-- Vendor Styles -->
@section('vendor-style')
  @vite([
    'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.scss',
    'resources/assets/vendor/libs/select2/select2.scss',
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


@section('content')
  <div class="container-fluid">
    <div class="row">
      <!-- Add Keyword Form -->
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title mb-0">
              <i class="ri-add-line me-2"></i>Add New Keyword
            </h5>
          </div>
          <div class="card-body">
            <form action="{{ route('keyword-store') }}" method="POST" id="keywordForm">
              @csrf
              <div class="mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" required>
                <div class="form-text">Enter keyword title</div>
              </div>

              <div class="mb-3">
                <label for="keywords" class="form-label">Keywords <span class="text-danger">*</span></label>
                <textarea class="form-control" id="keywords" name="keywords" rows="4" required placeholder="Enter keywords separated by commas or line breaks"></textarea>
                <div class="form-text">Enter multiple keywords separated by commas or new lines</div>
              </div>

              <div class="mb-3">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select class="form-select" id="status" name="status" required>
                  <option value="">Select Status</option>
                  <option value="Active">Active</option>
                  <option value="Inactive">Inactive</option>
                </select>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                  <i class="ri-save-line me-2"></i>Add Keyword
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Keywords Table -->
      <div class="col-md-8">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
              <i class="ri-list-check me-2"></i>Keywords Management
            </h5>
            <div class="d-flex gap-2">
              <button class="btn btn-outline-secondary btn-sm" onclick="refreshTable()">
                <i class="ri-refresh-line me-1"></i>Refresh
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover" id="keywordsTable">
                <thead class="table-dark">
                  <tr>
                    <th style="width: 50px;">#</th>
                    <th style="width: 80px;">ID</th>
                    <th style="width: 100px;">User ID</th>
                    <th style="width: 150px;">Title</th>
                    <th>Keywords</th>
                    <th style="width: 100px;">Status</th>
                    <th style="width: 130px;">Created At</th>
                    <th style="width: 130px;">Updated At</th>
                    <th style="width: 100px;">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($keywords as $index => $keyword)
                    <tr>
                      <td class="text-center">{{ $index + 1 }}</td>
                      <td class="text-center">
                        <span class="badge bg-primary">{{ $keyword->id ?? 'N/A' }}</span>
                      </td>
                      <td class="text-center">{{ $keyword->user_id ?? 'N/A' }}</td>
                      <td>
                        <strong>{{ $keyword->title ?? 'No Title' }}</strong>
                      </td>
                      <td>
                        <div class="keyword-text" style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
                          {{ Str::limit($keyword->keywords ?? 'No Keywords', 100) }}
                        </div>
                        @if(strlen($keyword->keywords ?? '') > 100)
                          <button class="btn btn-link btn-sm p-0" onclick="showFullKeywords('{{ $keyword->keywords }}')">
                            <small>Show More</small>
                          </button>
                        @endif
                      </td>
                      <td>
                        @if(($keyword->status ?? '') === 'Active')
                          <span class="badge bg-success">Active</span>
                        @elseif(($keyword->status ?? '') === 'Inactive')
                          <span class="badge bg-secondary">Inactive</span>
                        @else
                          <span class="badge bg-warning">Unknown</span>
                        @endif
                      </td>
                      <td>
                        <small>{{ $keyword->created_at ? $keyword->created_at->format('Y-m-d H:i') : 'N/A' }}</small>
                      </td>
                      <td>
                        <small>{{ $keyword->updated_at ? $keyword->updated_at->format('Y-m-d H:i') : 'N/A' }}</small>
                      </td>
                      <td>
                        <div class="btn-group btn-group-sm">
                          <button class="btn btn-outline-primary btn-sm" onclick="editKeyword({{ $keyword->id ?? 0 }})">
                            <i class="ri-edit-line"></i>
                          </button>
                          <button class="btn btn-outline-danger btn-sm" onclick="deleteKeyword({{ $keyword->id ?? 0 }})">
                            <i class="ri-delete-bin-line"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="9" class="text-center py-4">
                        <div class="d-flex flex-column align-items-center">
                          <i class="ri-inbox-line text-muted" style="font-size: 3rem;"></i>
                          <p class="text-muted mt-2 mb-0">No keywords found</p>
                          <small class="text-muted">Add your first keyword using the form on the left</small>
                        </div>
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Keywords Detail Modal -->
  <div class="modal fade" id="keywordsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Full Keywords</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <pre id="fullKeywordsContent" class="bg-light p-3 rounded" style="white-space: pre-wrap;"></pre>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('page-script')
<script>
function refreshTable() {
    location.reload();
}

function showFullKeywords(keywords) {
    document.getElementById('fullKeywordsContent').textContent = keywords;
    new bootstrap.Modal(document.getElementById('keywordsModal')).show();
}

function editKeyword(id) {
    // TODO: Implement edit functionality
    alert('Edit keyword with ID: ' + id);
}

function deleteKeyword(id) {
    if (confirm('Are you sure you want to delete this keyword?')) {
        // TODO: Implement delete functionality
        alert('Delete keyword with ID: ' + id);
    }
}

// Form validation
document.getElementById('keywordForm').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const keywords = document.getElementById('keywords').value.trim();
    const status = document.getElementById('status').value;

    if (!title || !keywords || !status) {
        e.preventDefault();
        alert('Please fill in all required fields');
        return false;
    }
});
</script>
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
