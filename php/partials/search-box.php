<!-- The navbar search box -->

<div class="input-group input-group-sm" id="searchBox">
    <input id="keyword" type="text" class="form-control form-control-sm" value="<?php echo isset($_GET["keyword"]) ? $_GET["keyword"] : ""; ?>" placeholder="<?php echo $isLogged ? $_SESSION["Username"] . " search" : "Search" ?> a book..." aria-label="Search for...">
    <span class="input-group-btn hidden" id="cancelButtonGroup">
        <button class="btn btn-secondary btn-sm" type="button" id="cancelButton">&times;</button>
    </span>
    <span class="input-group-btn">
        <button class="btn btn-secondary btn-sm" type="button">
            <?php
            if (isset($_GET["sort"]) && $_GET["sort"] == Defaults::ASC) {
                echo '<i class="fa fa-sort-numeric-asc" id="bookSort"></i>';
            } else {
                echo '<i class="fa fa-sort-numeric-desc" id="bookSort"></i>';
            }
            ?>
        </button>
    </span>
    <span class="input-group-btn">
        <button class="btn btn-secondary btn-sm" type="button" id="searchButton">Go!</button>
    </span>
</div>