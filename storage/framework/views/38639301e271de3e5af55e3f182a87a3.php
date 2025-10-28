<!-- ADD NEW RECORD -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddRecord" aria-labelledby="offcanvasAddRecordLabel">
  <div class="offcanvas-header border-bottom">
    <h5 id="offcanvasAddRecordLabel" class="offcanvas-title">Add Message Template</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body mx-0 flex-grow-0 h-100">
    <form class="add-new-record pt-0" id="addNewTemplateForm">
      <div class="form-floating form-floating-outline mb-5">
        <input type="text" class="form-control" id="title" placeholder="Title" name="title" aria-label="Title" />
        <label for="title">Title</label>
      </div>

      <div class="form-floating form-floating-outline mb-5">
        <textarea class="form-control" rows="10" id="description" placeholder="Message" name="description" aria-label="Message"></textarea>
        <label for="message">Message</label>
      </div>

      <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
      <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
    </form>
  </div>
</div>
<?php /**PATH C:\xampp\htdocs\nbr\Modules/Messages\resources/views/template/create.blade.php ENDPATH**/ ?>