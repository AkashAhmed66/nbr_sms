<!-- ADD NEW RECORD -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddRecord" aria-labelledby="offcanvasAddUserLabel">
      <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add User Group</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body mx-0 flex-grow-0 h-100">
        <form class="add-new-user pt-0" id="addNewUserGroupForm">
          <input type="hidden" name="id" id="user_id">
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" class="form-control" id="add-title" placeholder="" name="title" aria-label="" />
            <label for="add-title">Title</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-comment" class="form-control" placeholder="" aria-label="" name="comment" />
            <label for="add-comment">Comment</label>
          </div>
          
          <div class="form-floating form-floating-outline mb-5">
            <select id="add-status" name="status" class="select2 form-select">
              <option value="">Select</option>
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
            <label for="country">Status</label>
          </div>
          <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
          <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </form>
      </div>
    </div><?php /**PATH C:\xampp\htdocs\nbr\Modules/Users\resources/views/group/create.blade.php ENDPATH**/ ?>