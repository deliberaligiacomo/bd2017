<?php
    require_once('../services/AuthenticationService.php');
    require_once('../services/BooksService.php');
    require_once('../services/Defaults.php');

    $isReviewAdded = false;

    // if a username and a password is given in POST, then try to login
    if(isset($_POST["Title"]) &&
        isset($_POST["Image"]) && 
        isset($_POST["Genre"]) &&
        isset($_POST["Author"])){
        $isReviewAdded = BooksService::addBook($_POST["Title"],$_POST["Image"],$_POST["Genre"],$_POST["Author"]);
    }
       
    if($isReviewAdded){
        // return on the previous page
        header('Location: '. Defaults::DEFAULT_BASE_URL .'/php/add-book.php?added=true');
    }else{
         // return on the register page with a GET param
        header('Location: '. Defaults::DEFAULT_BASE_URL .'/php/add-book.php?errors=true');
    }

?>