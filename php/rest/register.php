<?php
    require_once('../services/Authentication.php');
    require_once('../services/Defaults.php');

    $isRegistred = false;

    // if a username and a password is given in POST, then try to login
    if(isset($_POST["Username"]) &&
        isset($_POST["Password"]) && 
        isset($_POST["RepeatPassword"]) &&
        isset($_POST["Email"]) &&
        isset($_POST["FirstName"]) &&
        isset($_POST["LastName"]) &&
        isset($_POST["BirthDate"])){
        $isRegistred = Authentication::register($_POST["Username"], md5($_POST["Password"]),$_POST["FirstName"],$_POST["LastName"],$_POST["Email"],$_POST["BirthDate"]);
    }
       
    if($isRegistred){
        // return on the previous page
        header('Location: '. Defaults::DEFAULT_BASE_URL .'/php/register.php?signup=true');
    }else{
         // return on theregister page with a GET param
        header('Location: '. Defaults::DEFAULT_BASE_URL .'/php/register.php?errors=true');
    }

?>