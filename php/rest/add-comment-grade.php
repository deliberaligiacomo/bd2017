<?php

    require_once('../services/CommentsService.php');
    require_once('../services/Defaults.php');

    // if a username and a password is given in POST, then try to login
    if (isset($_GET["type"]) &&
            isset($_GET["userId"]) &&
            isset($_GET["grade"])) {
        echo json_encode(CommentsService::addCommentGrade($_GET["commentId"], $_GET["userId"], $_GET["grade"]));
    } else {
        echo "null";
    }
?>