<?php
    require_once('../services/AuthenticationService.php');
    require_once('../services/Defaults.php');

    $isLogged = false;

    // if a username and a password is given in POST, then try to login
    if(isset($_POST["Username"]) && isset($_POST["Password"])){
        $isLogged = AuthenticationService::login($_POST["Username"], md5($_POST["Password"]));
    }
       
    if($isLogged){
        // return on the previous page
        header('Location: '. Defaults::DEFAULT_BASE_URL .'/php/login.php');
    }else{
         // return on the login page with a GET param
        header('Location: '. Defaults::DEFAULT_BASE_URL .'/php/login.php?wrongCredentials=true');
    }

?>