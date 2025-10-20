<!-- ADD NEW RECORD -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddRecord" aria-labelledby="offcanvasAddUserLabel">
      <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add Service Provider</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body mx-0 flex-grow-0 h-100">
        <form class="add-new-user pt-0" id="addNewServiceProviderForm">
          <input type="hidden" name="id" id="user_id">
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" class="form-control" id="add-provider-name" placeholder="John Doe" name="name" aria-label="John Doe" />
            <label for="add-user-fullname">Name</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-api-provider" class="form-control" placeholder="john.doe@example.com" aria-label="john.doe@example.com" name="api_provider" />
            <label for="add-api-provider">Api Provider</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <select id="add-provider-type" name="channel_type" class="select2 form-select">
              <option value="">Select</option>
              <option value="MAP">MAP</option>
              <option value="SMPP">SMPP</option>
              <option value="HTTP">HTTP</option>
            </select>
            <label for="country">Provider Type</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-url" class="form-control phone-mask" placeholder="+1 (609) 988-44-11" aria-label="john.doe@example.com" name="url" />
            <label for="add-url">URL</label>
          </div>
          
          <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
          <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </form>
      </div>
    </div>