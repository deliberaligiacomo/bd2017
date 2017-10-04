<?php
require_once( __DIR__ . '/../services/Defaults.php');
?>
<div class="col-md-6">
    <form action="<?php echo Defaults::DEFAULT_BASE_URL . '/php/rest/add-author.php'; ?>" method="post" name="AddAuthorForm">
        <h3>Add author</h3>
        <br>
        <div class="form-group">
            <input type="text" class="form-control form-control-sm" name="FirstName" placeholder="First name" required="true" autocomplete="off">
        </div>
        <div class="form-group">
            <input type="text" class="form-control form-control-sm" name="LastName" placeholder="Last name" required="true" autocomplete="off"/>
        </div>
        <div class="form-group">
            <input type="date" class="form-control form-control-sm" name="BirthDate" placeholder="BirthDate" />
        </div>
        <div class="form-group">
            <input type="text" class="form-control form-control-sm" name="Nationality" placeholder="Nationality" />
        </div>  	  
        <button class="btn btn-sm btn-primary"  name="Submit" value="Add" type="Submit">Add</button>  			
    </form>
</div>