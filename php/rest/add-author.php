<?php
    require_once('../services/AuthenticationService.php');
    require_once('../services/AuthorsService.php');
    require_once('../services/Defaults.php');

    $authorId = false;

    // if a username and a password is given in POST, then try to login
    if(isset($_POST["FirstName"]) &&
        isset($_POST["LastName"]) && 
        isset($_POST["BirthDate"]) &&
        isset($_POST["Nationality"])){
        $authorId = AuthorsService::addAuthor($_POST["FirstName"], $_POST["LastName"], $_POST["BirthDate"], $_POST["Nationality"]);
    }
       
    if($authorId != null && $authorId > 0){
        // return on the previous page
        header('Location: '. Defaults::DEFAULT_BASE_URL .'/php/add-book.php?added=true');
    }else{
         // return on the register page with a GET param
        header('Location: '. Defaults::DEFAULT_BASE_URL .'/php/add-book.php?errors=true');
    }

?>