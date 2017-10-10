<?php

    require_once('../services/AuthenticationService.php');
    require_once('../services/BooksService.php');
    require_once('../services/Defaults.php');

    $isReviewAdded = false;

    // if a username and a password is given in POST, then try to login
    if (isset($_POST["Title"]) &&
            isset($_POST["Text"]) &&
            isset($_POST["Grade"]) &&
            isset($_POST["IdBook"]) &&
            isset($_POST["PrevUrl"]) &&
            isset($_POST["IdAuthor"])) {
        $isReviewAdded = BooksService::addReview($_POST["Title"], $_POST["Text"], $_POST["Grade"], $_POST["IdAuthor"], $_POST["IdBook"]);
    }

    if ($isReviewAdded && isset($_POST["PrevUrl"])) {
        header('Location: ' . $_POST["PrevUrl"]);
    } else {
        // return on the register page with a GET param
        header('Location: ' . Defaults::DEFAULT_BASE_URL);
    }
?>