<?php

    require_once('../services/CommentsService.php');

    $request_body = file_get_contents('php://input');
    $comment = json_decode($request_body);
    $result = null;
    if ($comment) {
        $result = CommentsService::addComment($comment);
    }
    echo json_encode($result);
?>