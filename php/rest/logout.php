<?php
    require_once('../services/AuthenticationService.php');

    // if logged in, destroy the session
    if(isset($_SESSION["Username"]))
        unset($_SESSION["Username"]);

    // return on the previous page
    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>