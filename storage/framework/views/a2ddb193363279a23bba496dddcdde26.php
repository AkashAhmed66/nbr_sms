
    <!-- ADD NEW RECORD -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddRecord" aria-labelledby="offcanvasAddUserLabel">
      <div class="offcanvas-header border-bottom">
        <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add Contact</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body mx-0 flex-grow-0 h-100">
        <form class="add-new-user pt-0" id="addNewPhonebookContactForm">
          <div class="form-floating form-floating-outline mb-5">
            <select id="add-group" name="group_id" class="select2 form-select">
                <option value="">Select</option>
                <?php $__currentLoopData = $userGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                 <option value="<?php echo e($userGroup->id); ?>"><?php echo e($userGroup->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <label for="country">Group</label>
        </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" class="form-control" id="add-name-en" placeholder="" name="name_en" aria-label="John Doe" />
            <label for="add-name-en"> Name English</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" class="form-control" id="add-phone" placeholder="" name="phone" aria-label="John Doe" />
            <label for="add-phone">Phone</label>
          </div>
          <div class="form-floating form-floating-outline mb-5">
            <input type="text" class="form-control" id="add-email" placeholder="" name="email" aria-label="John Doe" />
            <label for="add-email">Email</label>
          </div>
        
          <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
          <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
        </form>
      </div>
    </div>
   <?php /**PATH C:\xampp\htdocs\metronetsms\Modules/Phonebook\resources/views/phonebook/create.blade.php ENDPATH**/ ?>