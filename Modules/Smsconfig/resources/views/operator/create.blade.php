<!-- ADD NEW RECORD -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddRecord" aria-labelledby="offcanvasAddRecordLabel">
  <div class="offcanvas-header border-bottom">
    <h5 id="offcanvasAddRecordLabel" class="offcanvas-title">Add New Operator</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body mx-0 flex-grow-0 h-100">
        <form class="add-new-user pt-0" id="addNewOperatorForm">
          
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" class="form-control" id="add-user-fullname" placeholder="John Doe" name="full_name" aria-label="John Doe" />
            <label for="add-operator-fullname">Operator Name</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-shortname" class="form-control" placeholder="Enter short name" aria-label="Sombor" name="short_name" />
            <label for="add-operator-shortname">Short Name</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-prefix" class="form-control" placeholder="Prefix" aria-label="GP" name="prefix" />
            <label for="add-prefix">Prefix</label>
          </div>

          <div class="form-floating form-floating-outline mb-5">
            <select id="country_id" name="country_id" class="select2 form-select">
              <option value="">Select country</option>
              @foreach($countries as $country)
              <option value="{{ $country->id }}">{{ $country->name }}</option>
             @endforeach
            </select>
            <label for="country">Country</label>
          </div>

          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-ton" class="form-control" placeholder="Ton" aria-label="ton" name="ton" />
            <label for="add-ton">TON</label>
          </div>
		  <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-npi" class="form-control" placeholder="NPI" aria-label="npi" name="npi" />
            <label for="add-npi">NPI</label>
          </div>
          <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
          <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </form>
      </div>
</div>
