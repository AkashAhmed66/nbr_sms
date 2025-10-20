<!-- ADD NEW RECORD -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddRecord" aria-labelledby="offcanvasAddRecordLabel">
  <div class="offcanvas-header border-bottom">
    <h5 id="offcanvasAddRecordLabel" class="offcanvas-title">Add New Route</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body mx-0 flex-grow-0 h-100">
        <form class="add-new-user pt-0" id="addNewRouteForm">
        
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" class="form-control" id="add-operator-prefix" placeholder="John Doe" name="operator_prefix" aria-label="John Doe" />
            <label for="add-operator-prefix">Operator</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <select id="channel_id" name="channel_id" class="select2 form-select">
              <option value="">Select</option>
              @foreach($serviceProviders as $sp)
              <option value="{{ $sp->id }}">{{ $sp->name }}</option>
              @endforeach
            </select>
            <label for="country">Channel</label>
          </div>
          <!--<div class="form-floating form-floating-outline mb-5">
            <select id="channel_id" name="channel_id" class="select2 form-select">
              <option value="">Select</option>
              <option value="1">Channel 1</option>
              <option value="2">Channel 2</option>
            </select>
            <label for="country">Channel</label>
          </div>-->
          <div class="form-floating form-floating-outline mb-5">
            <select id="has_mask" name="has_mask" class="select2 form-select">
              <option value="">Select</option>
              <option value="1">YES</option>
              <option value="2">NO</option>
            </select>
            <label for="country">Has Mask</label>
          </div>

          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-default-mask" class="form-control" placeholder="Enter default mask" aria-label="Sombor" name="default_mask" />
            <label for="add-default-mask">Default Mask</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-cost" class="form-control" placeholder="Cost" aria-label="cost" name="cost" />
            <label for="add-cost">Cost</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-success-rate" class="form-control" placeholder="Success rate" aria-label="GP" name="success_rate" />
            <label for="add-cost">Success Rate</label>
          </div>
          <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
          <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </form>
      </div>
</div>
