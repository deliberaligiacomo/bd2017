<?php
    require_once(__DIR__  . '/../services/Defaults.php');
?>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>


<script>
    // START NAVBAR SEARCH BOX

    /* 
    * Checks if the cancel button should be visible or not
    */
    $(document).ready(function() {
        checkCancelButton();
    });

    /*
    * When a click in the GO! button is recived, navigates to the index page with the get keyword param valorized with the input value 
    */
    $(document).on("click", "#searchButton", function() {
        // when click on the Go button
        searchBook();
    });

    $(document).on("click", "#cancelButton", function() {
        // when click on the X button
        $("#keyword").val("");
        $(this).hide();
        $("#searchButton").click();
    });

    /** 
    * Every time a button is pressed in the input text, checks if the cancel button should be visible or not.
    * If the button pressed is the Enter (invio), search the book with the current keyword.
    */
    $(document).on("keyup", "#keyword", function(e) {
        // when enter in the keyword input text
        checkCancelButton();

        if (e.which == 13)
            searchBook();
    });

    /** 
    * Shows or hides the cancel button if the keyword input is valorized or empty
    */
    function checkCancelButton() {
        var keyword = $("#keyword").val();
        if (!keyword || keyword.length == 0)
            $("#cancelButtonGroup").addClass("hidden");
        else
            $("#cancelButtonGroup").removeClass("hidden");
    }

    /**
    * Navigates to the index page with the get keyword param valorized with the input value 
    */
    function searchBook() {
        // navigate to homepage with a get parameter
        var keyword = $("#keyword").val();
        var currentSort = "<?php echo isset($_GET["sort"]) ? $_GET["sort"] : Defaults::DESC ?>";
        var defaultBaseUrl = "<?php echo Defaults::DEFAULT_BASE_URL; ?>";
        // if no value is provided, navigate without the param
        if (!keyword || keyword.length == 0)
            window.location.href = defaultBaseUrl + currentSort ? '?sort=' + currentSort : '';
        else
            window.location.href = defaultBaseUrl + "/?keyword=" + keyword  + (currentSort ? '&sort=' + currentSort : '');
    }

    /* 
    * When click on sort change button in navbar
    */
    $(document).on("click","#bookSort",function(){
        var currentSort = "<?php echo isset($_GET["sort"]) ? $_GET["sort"] : Defaults::DESC ?>";
        var currentKeyword = "<?php echo isset($_GET["keyword"]) ? $_GET["keyword"] : null ?>";
        var defaultAsc = "<?php echo Defaults::ASC; ?>";
        var defaultDesc= "<?php echo Defaults::DESC; ?>";
        var defaultBaseUrl = "<?php echo Defaults::DEFAULT_BASE_URL; ?>";

        if (!currentKeyword || currentKeyword.length == 0)
            window.location.href = defaultBaseUrl + currentSort ? '?sort=' + (currentSort == defaultDesc ? defaultAsc : defaultDesc) : '';
        else{
            window.location.href = defaultBaseUrl + "/?keyword=" + currentKeyword  + (currentSort ? '&sort=' + (currentSort == defaultDesc ? defaultAsc : defaultDesc) : '');
        }
    });
    // END NAVBAR SEARCH BOX
</script>