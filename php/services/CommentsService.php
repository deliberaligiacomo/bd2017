<?php

require_once(__DIR__ . '/AuthenticationService.php');
require_once(__DIR__ . '/../models/Comment.php');

/**
 * Provides all Comments CRUD operations.
 */
class CommentsService {

    /**
     * Returns all the comments of a review
     * @return Array<Comment>
     */
    public static function getComments($reviewId) {
        try {
            $dbconn = Database::getInstance()->getConnection();
            $statement = $dbconn->prepare('
                SELECT 
                    *,
                    concat_ws(\'\', u.firstName, \' \', u.lastname) as userfullname
                FROM 
                    comments c JOIN users u ON c.id_user = u.id
                WHERE id_review = :id_review
                '
            );
            $statement->bindParam(":id_review", $reviewId);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_CLASS, "Comment");
        } catch (PDOException $e) {
            LogsService::logException($e);
            return null;
        }
    }

    /**
     * Add a new comment
     * @param Comment $comment
     * @return boolean
     */
    public static function addComment($comment) {
        try {
            $dbconn = Database::getInstance()->getConnection();
            $statement = $dbconn->prepare('
                INSERT INTO COMMENTS VALUES ( 
                    DEFAULT,
                    :text,
                    :id_user,
                    :id_review,
                    :id_ref_comm,
                    :date_comment
                )'
            );
            $statement->bindParam(":text", $comment->text);
            $statement->bindParam(":id_user", $comment->id_user);
            $statement->bindParam(":id_review", $comment->id_review);
            $statement->bindParam(":id_ref_comm", $comment->id_ref_comm);
            $statement->bindParam(":date_comment", $comment->date_comment);
            $statement->execute();
            $isAdded = $statement->rowCount() == 1;
            if ($isAdded)
                return $dbconn->lastInsertId("comments_id_comment_seq");
            else
                return 0;
        } catch (PDOException $e) {
            LogsService::logException($e);
            return null;
        }
    }

    /**
     * Deletes a comment and all its children, given its id
     * @param integer $commentId
     * @return boolean
     */
    public static function deleteAuthor($commentId) {
        if ($commentId == null || $commentId < 0) {
            return false;
        }
        try {
            $dbconn = Database::getInstance()->getConnection();
            $query = '
                        DELETE
                        FROM 
                            comments
                        WHERE
                            id_comment = :commentId
                    ';

            $statement = $dbconn->prepare($query);
            $statement->bindParam(":commentId", $commentId, PDO::PARAM_INT);
            $result = $statement->execute();
            return $result->rowCount() == 1;
        } catch (PDOException $e) {
            LogsService::logException($e);
            return null;
        }
    }

}

?>