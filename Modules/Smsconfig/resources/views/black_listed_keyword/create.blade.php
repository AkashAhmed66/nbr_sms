<style>
        .tag-container {
            display: flex;
            flex-wrap: wrap;
            border: 1px solid #ccc;
            padding: 5px;
            border-radius: 5px;
            max-width: 400px;
        }

        .tag-container input {
            border: none;
            outline: none;
            flex-grow: 1;
        }

        .tag {
            background-color: #007BFF;
            color: white;
            border-radius: 3px;
            padding: 5px;
            margin-right: 5px;
            display: flex;
            align-items: center;
        }

        .tag .close {
            margin-left: 8px;
            cursor: pointer;
            color: white;
            font-weight: bold;
        }
    </style>

<!-- Offcanvas to add new user -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddRecord" aria-labelledby="offcanvasAddUserLabel">
    <div class="offcanvas-header border-bottom">
    <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Add Black List</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0 h-100">
    <form class="add-new-user pt-0" id="addNewBlackListForm">
        <input type="hidden" name="id" id="user_id">
        <div class="form-floating form-floating-outline mb-5">
        <input type="text" class="form-control" id="add-title" placeholder="Gov" name="title" aria-label="Gov" />
        <label for="add-title">Title</label>
        </div>
        <!--<div class="form-floating form-floating-outline mb-5">
        <input type="text" id="add-keywords" class="form-control" placeholder="" aria-label="" name="keywords" />
        <label for="add-keywords">Keywords</label>
        </div>-->
        <div class="form-floating form-floating-outline mb-5 tag-container">
       
            <input type="text" class="form-control" id="tag-input" placeholder="Add a tag">
            <input type="hidden" id="add-keywords" name="keywords">
            
        </div>
        
        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
    </form>
    </div>
</div>
