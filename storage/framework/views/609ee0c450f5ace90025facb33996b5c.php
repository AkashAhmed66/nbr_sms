<!-- ADD NEW RECORD -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddRecord" aria-labelledby="offcanvasAddUserLabel">
      <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add Rate</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body mx-0 flex-grow-0 h-100">
        <form class="add-new-user pt-0" id="addNewRateForm">
          <input type="hidden" name="id" id="user_id">
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" class="form-control" id="add-rate-name" placeholder="" name="rate_name" aria-label="John Doe" />
            <label for="add-rate-name">Rate Name</label>
          </div>
   
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="masking-rate" class="form-control" placeholder="" aria-label="" name="masking_rate" />
            <label for="add-selling-masking-rate">Masking Rate</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="nonmasking-rate" name="nonmasking_rate" class="form-control" placeholder="" aria-label="jdoe1" />
            <label for="add-buying-nonmasking-rate">Nonmasking Rate</label>
          </div>
          <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
          <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </form>
      </div>
    </div><?php /**PATH C:\xampp\htdocs\metronetsms\Modules/Smsconfig\resources/views/rate/create.blade.php ENDPATH**/ ?>