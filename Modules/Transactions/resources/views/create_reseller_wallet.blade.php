<!-- ADD NEW RECORD -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddRecord" aria-labelledby="offcanvasAddUserLabel">
    <div class="offcanvas-header border-bottom">
      <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add Reseller Wallet</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 h-100">
      <form class="add-new-user pt-0" id="addNewResellerrWalletForm">

        <div class="form-floating form-floating-outline mb-5">
          <input type="text" class="form-control" id="add-non-masking-balance" placeholder="John Doe" name="non_masking_balance" aria-label="John Doe" />
          <label for="add-non-masking-balance">Non Masking Balance</label>
        </div>
        <div class="form-floating form-floating-outline mb-5">
          <input type="text" id="add-masking-balance" class="form-control" placeholder="" aria-label="john.doe@example.com" name="masking_balance" />
          <label for="add-masking-balance">Masking Balance</label>
        </div>
        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>
    </div>
  </div>