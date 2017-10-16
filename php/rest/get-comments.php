<?php

require_once('../services/AuthenticationService.php');
require_once('../services/CommentsService.php');

// if a username and a password is given in POST, then try to login
if (isset($_GET["reviewId"])) {
    $result = CommentsService::getComments($_GET["reviewId"]);
    echo json_encode($result);
} else {
    echo json_encode([]);
}
?>