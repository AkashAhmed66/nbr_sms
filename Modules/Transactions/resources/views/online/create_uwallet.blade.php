<!-- ADD NEW RECORD -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddRecord" aria-labelledby="offcanvasAddUserLabel">
      <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add User Wallet</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      
      <div class="offcanvas-body mx-0 flex-grow-0 h-100">
        <form class="add-new-user pt-0" id="addNewUsersWalletForm">
        <div class="form-floating form-floating-outline mb-5">
            <select id="add-user" name="user_id" class="select2 form-select" data-placeholder="Select User">
                <option value="">Select User</option>
                @foreach($userLists as $userList)
                 <option value="{{ $userList->id }}">{{ $userList->name }} ({{ $userList->username }})</option>
                @endforeach
            </select>
            <label for="add-user">User</label>
        </div>
  
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" class="form-control" id="add-non-masking-balance" placeholder="" name="balance" aria-label="" />
            <label for="add-non-masking-balance">Balance</label>
          </div>

          <div class="form-floating form-floating-outline mb-5">
            <select id="add-balance-type" name="balance_type" class="select2 form-select">
                <option value="">Select</option>
                 <option value="Debit">Debit</option>
                 <option value="Credit">Credit</option>
            </select>
            <label for="country">Balance Type</label>
        </div>
          <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
          <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </form>
      </div>
    </div>

<script>
$(document).ready(function() {
    // Initialize Select2 for user selection
    $('#add-user').select2({
        placeholder: 'Search and select a user',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#offcanvasAddRecord'),
        templateResult: function(data) {
            if (!data.id) {
                return data.text;
            }
            
            // Custom template for dropdown options
            var $result = $(
                '<div class="select2-result">' +
                    '<div class="select2-result__title">' + data.text + '</div>' +
                '</div>'
            );
            return $result;
        },
        templateSelection: function(data) {
            return data.text;
        }
    });

    // Initialize Select2 for balance type
    $('#add-balance-type').select2({
        placeholder: 'Select Balance Type',
        allowClear: true,
        width: '100%',
        dropdownParent: $('#offcanvasAddRecord'),
        minimumResultsForSearch: Infinity // Disable search for simple dropdown
    });

    // Reset Select2 when offcanvas is hidden
    $('#offcanvasAddRecord').on('hidden.bs.offcanvas', function () {
        $('#add-user').val(null).trigger('change');
        $('#add-balance-type').val(null).trigger('change');
    });

    // Reinitialize Select2 when offcanvas is shown
    $('#offcanvasAddRecord').on('shown.bs.offcanvas', function () {
        $('#add-user').select2({
            placeholder: 'Search and select a user',
            allowClear: true,
            width: '100%',
            dropdownParent: $('#offcanvasAddRecord')
        });
    });
});
</script>
