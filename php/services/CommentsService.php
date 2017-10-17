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
        public static function getCommentsByReviewId($reviewId) {
            try {
                $dbconn = Database::getInstance()->getConnection();
                $statement = $dbconn->prepare('
                    SELECT
                        c.*,
                        COALESCE(SUM(gc.grade),0) as score,
                        concat_ws(\'\', u.firstName, \' \', u.lastname) as userfullname
                    FROM 
                        comments c JOIN users u ON c.id_user = u.id
                        LEFT JOIN grades_comments gc ON gc.id_comment = c.id_comment
                    WHERE id_review = :id_review AND c.id_ref_comm IS NULL
                    GROUP BY c.id_comment, userfullname
                ');
                $statement->bindParam(":id_review", $reviewId);
                $row = $statement->execute();
                $comments = array();
                while (($row = $statement->fetch(PDO::FETCH_ASSOC)) !== false) {
                    $comment = new Comment();
                    $comment->id_comment = $row['id_comment'];
                    $comment->text = $row['text'];
                    $comment->score = $row['score'];
                    $comment->id_user = $row['id_user'];
                    $comment->userfullname = $row['userfullname'];
                    $comment->id_review = $row['id_review'];
                    $comment->id_ref_comm = $row['id_ref_comm'];
                    $comment->date_comment = $row['date_comment'];
                    $comment->canEdit = $row['id_user'] == AuthenticationService::getUserId();
                    $comment->children = self::getCommentsByParentId($row['id_comment']);
                    $comments[] = $comment;
                }
                return $comments;
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

        /**
         * Given a parent id comment, return all the hierarchic tree
         * @param integer $parentId
         * @return Array<Comment>
         */
        public static function getCommentsByParentId($parentId) {
            try {
                $dbconn = Database::getInstance()->getConnection();
                $statement = $dbconn->prepare('
                    SELECT 
                        c.*,
                        COALESCE(SUM(gc.grade),0) as score,
                        concat_ws(\'\', u.firstName, \' \', u.lastname) as userfullname
                    FROM 
                        comments c JOIN users u ON c.id_user = u.id
                        LEFT JOIN grades_comments gc ON gc.id_comment = c.id_comment
                    WHERE 
                        c.id_ref_comm = :parentId
                    GROUP BY c.id_comment,userfullname
                ');
                $statement->bindParam(":parentId", $parentId);
                $row = $statement->execute();
                $comments = array();
                while (($row = $statement->fetch(PDO::FETCH_ASSOC)) !== false) {
                    $comment = new Comment();
                    $comment->id_comment = $row['id_comment'];
                    $comment->text = $row['text'];
                    $comment->score = $row['score'];
                    $comment->id_user = $row['id_user'];
                    $comment->userfullname = $row['userfullname'];
                    $comment->id_review = $row['id_review'];
                    $comment->id_ref_comm = $row['id_ref_comm'];
                    $comment->date_comment = $row['date_comment'];
                    $comment->canEdit = $row['id_user'] == AuthenticationService::getUserId();
                    $comment->children = self::getCommentsByParentId($row['id_comment']);
                    $comments[] = $comment;
                }
                return $comments;
            } catch (PDOException $e) {
                LogsService::logException($e);
                return null;
            }
        }

        /**
         * Adds a comment grade (+1/-1)
         * @param integer $commentId The comment id         
         * @param integer $userId The user id
         * @param integer $grade The grade
         * @return boolean
         */
        public static function addCommentGrade($commentId, $userId, $grade) {
            try {
                $dbconn = Database::getInstance()->getConnection();

                $statement = $dbconn->prepare('
                    SELECT 
                        *
                    FROM 
                        grades_comments
                    WHERE 
                            id_user = :userId
                            AND
                            id_comment = :commentId
                ');
                $statement->bindParam(":userId", $userId, PDO::PARAM_INT);
                $statement->bindParam(":commentId", $commentId, PDO::PARAM_INT);
                $statement->execute();
                $isExisting = $statement->rowCount() == 1;

                if ($isExisting) {
                    $currentValue = $statement->fetchAll()[0]["grade"];
                    $statement = $dbconn->prepare('
                        UPDATE
                            grades_comments
                        SET
                            grade = :grade
                        WHERE 
                                id_user = :userId
                                AND
                                id_comment = :commentId
                    ');
                    $statement->bindParam(":userId", $userId, PDO::PARAM_INT);
                    $statement->bindParam(":commentId", $commentId, PDO::PARAM_INT);
                    $statement->bindParam(":grade", $grade, PDO::PARAM_INT);
                    $statement->execute();
                    if ($statement->rowCount() == 1)
                        return self::getScore($commentId);
                    return null;
                }

                // ELSE NEW GRADE 
                $statement = $dbconn->prepare('
                INSERT INTO grades_comments VALUES (
                        :userId,
                        :commentId,
                        :grade
                    )
                ');
                $currentScore = $grade == Defaults::SCORE_DOWN ? -1 : 1;
                $statement->bindParam(":userId", $userId, PDO::PARAM_INT);
                $statement->bindParam(":commentId", $commentId, PDO::PARAM_INT);
                $statement->bindParam(":grade", $currentScore, PDO::PARAM_INT);
                $statement->execute();
                if ($statement->rowCount() == 1)
                    return self::getScore($commentId);
                return null;
            } catch (PDOException $e) {
                LogsService::logException($e);
                return false;
            }
        }

        public static function getScore($commentId) {
            try {
                $dbconn = Database::getInstance()->getConnection();

                $statement = $dbconn->prepare('
                SELECT 
                    SUM(grade) as score
                FROM 
                    grades_comments
                WHERE 
                        id_comment = :commentId
                GROUP BY id_comment
                ');
                $statement->bindParam(":commentId", $commentId, PDO::PARAM_INT);
                $statement->execute();
                $result = $statement->fetchAll()[0];
                return $result;
            } catch (PDOException $e) {
                LogsService::logException($e);
                return false;
            }
        }

    }

?>