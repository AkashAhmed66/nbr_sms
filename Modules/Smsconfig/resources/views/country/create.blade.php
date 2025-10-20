<!-- Offcanvas to add new user -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddRecord" aria-labelledby="offcanvasAddUserLabel">
      <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add Country</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body mx-0 flex-grow-0 h-100">
        
		<form class="add-new-user pt-0" id="addNewCountryForm">
          <input type="hidden" name="id" id="user_id">
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" class="form-control" id="add-iso" placeholder="iso" name="iso" aria-label="John Doe" />
            <label for="add-iso">ISO</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-name" class="form-control" placeholder="" aria-label="john.doe@example.com" name="name" />
            <label for="add-name">Naame</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-nickname" class="form-control phone-mask" placeholder="" aria-label="john.doe@example.com" name="nickname" />
            <label for="add-nickname">Nickname</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-iso3" name="iso3" class="form-control" placeholder="Web Developer" aria-label="jdoe1" />
            <label for="add-iso3">ISO3</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-numcode" name="numcode" class="form-control" placeholder="Web Developer" aria-label="jdoe1" />
            <label for="add-numcode">Numcode</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-phonecode" name="phonecode" class="form-control" placeholder="Web Developer" aria-label="jdoe1" />
            <label for="add-phonecode">Phonecode</label>
          </div>
          <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
          <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </form>
		
		
      </div>
    </div>