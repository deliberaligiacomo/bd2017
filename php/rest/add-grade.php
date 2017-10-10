<?php

    require_once('../services/AuthenticationService.php');
    require_once('../services/BooksService.php');
    require_once('../services/Defaults.php');

    $isReviewAdded = false;

    // if a username and a password is given in POST, then try to login
    if (isset($_GET["type"]) &&
            isset($_GET["reviewId"]) &&
            isset($_GET["userId"]) &&
            isset($_GET["prevUrl"])&&
            isset($_GET["grade"])) {
        $isReviewAdded = BooksService::addReviewGrade($_GET["reviewId"], $_GET["userId"], $_GET["grade"]);
    }

    if ($isReviewAdded && isset($_GET["prevUrl"])) {
        header('Location: ' . $_GET["prevUrl"]);
    } else {
        // return on the register page with a GET param
        header('Location: ' . Defaults::DEFAULT_BASE_URL);
    }
?>