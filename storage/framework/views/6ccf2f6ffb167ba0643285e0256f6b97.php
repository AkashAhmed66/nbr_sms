<!-- ADD NEW RECORD -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddRecord" aria-labelledby="offcanvasAddUserLabel">
      <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add Sender ID</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body mx-0 flex-grow-0 h-100">
        <form class="add-new-user pt-0" id="addNewSenderIDForm">

          <div class="form-floating form-floating-outline mb-5">
            <select id="add-user" name="user_id" class="select2 form-select">
              <option value="">Select</option>
              <?php $__currentLoopData = $userLists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userList): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($userList->id); ?>"><?php echo e($userList->name); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <label for="country">User</label>
          </div>
          
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" class="form-control" id="add-senderID" placeholder="" name="senderID" aria-label="" />
            <label for="add-senderID">Sender ID</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" id="add-count" class="form-control" placeholder="" aria-label="" name="count" />
            <label for="add-count">Count</label>
          </div>

          <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
          <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </form>
      </div>
    </div>
<?php /**PATH C:\xampp\htdocs\nbr\Modules/Smsconfig\resources/views/sender_id/create.blade.php ENDPATH**/ ?>