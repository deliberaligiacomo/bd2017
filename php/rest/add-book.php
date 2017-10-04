<?php
    require_once('../services/Authentication.php');
    require_once('../services/BookService.php');
    require_once('../services/Defaults.php');

    $isBookAdded = false;

    // if a username and a password is given in POST, then try to login
    if(isset($_POST["Title"]) &&
        isset($_POST["Image"]) && 
        isset($_POST["Genre"]) &&
        isset($_POST["Author"])){
        $isBookAdded = BookService::addBook($_POST["Title"],$_POST["Image"],$_POST["Genre"],$_POST["Author"]);
    }
       
    if($isBookAdded){
        // return on the previous page
        header('Location: '. Defaults::DEFAULT_BASE_URL .'/php/add-book.php?added=true');
    }else{
         // return on the register page with a GET param
        header('Location: '. Defaults::DEFAULT_BASE_URL .'/php/add-book.php?errors=true');
    }

?>