<?php
    require_once(__DIR__ . '/../services/AuthenticationService.php');
    require_once(__DIR__ . '/../services/AuthorsService.php');
    require_once(__DIR__ . '/../services/Defaults.php');


    if (!AuthenticationService::isLoggedIn()) {
        die();
    }

    $authors = AuthorsService::getAuthors();
?>
<div class="col-md-6">
    <form action="<?php echo Defaults::DEFAULT_BASE_URL . '/php/rest/add-book.php'; ?>" method="post" name="AddBookForm">
        <h3>Add book</h3>
        <br>
        <div class="form-group">
            <input type="text" class="form-control form-control-sm" name="Title" placeholder="Title" autofocus="true" required="true" autocomplete="off">
        </div>
        <div class="form-group">
            <input type="text" class="form-control form-control-sm" name="Image" placeholder="Image url" required="true" autocomplete="off"/>
        </div>
        <div class="form-group">
            <input type="text" class="form-control form-control-sm" name="Genre" placeholder="Genre" required="true" />
        </div>
        <div class="form-group">
            <select name="Author" class="form-control">
                <?php
                    foreach ($authors as $author) {
                        echo '<option value="' . $author->id . '">' . $author->firstname . ' ' . $author->lastname . '</option>';
                    }
                ?>
            </select>
        </div>	  	  
        <button class="btn btn-sm btn-primary"  name="Submit" value="Add" type="Submit">Add</button>  			
    </form>
</div>