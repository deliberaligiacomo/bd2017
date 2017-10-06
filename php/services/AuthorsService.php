<?php 
require_once(__DIR__ . '/AuthenticationService.php');
require_once(__DIR__ . '/../models/Book.php');
require_once(__DIR__ . '/../models/Author.php');

/**
 * Provides all Author CRUD operations.
 */
class AuthorsService { 
    /**
    *
    * Adds a new author
    *
    * @param firstName The author first name
    * @param lastName The author last name
    * @param birhDate The author birth date
    * @param nationality The author nationality (string)
    * @return The new added author id
    *
    */
    public static function addAuthor($firstName,$lastName,$birhDate,$nationality){
         try{
            if(!$birhDate || strlen($birhDate))
                $birhDate = null;
            if(!$nationality || strlen($nationality))
                $nationality = null;

            $dbconn = Database::getInstance()->getConnection();
            $authorStatment = $dbconn->prepare('
                INSERT INTO authors VALUES (
                    DEFAULT,
                    :firstName,
                    :lastName,
                    :birhDate,
                    :nationality
                )
            ');
            $authorStatment->bindParam(":firstName", $firstName, PDO::PARAM_STR);
            $authorStatment->bindParam(":lastName", $lastName, PDO::PARAM_STR);
            $authorStatment->bindParam(":birhDate", $birhDate, PDO::PARAM_STR);
            $authorStatment->bindParam(":nationality", $nationality, PDO::PARAM_STR);
            $authorStatment->execute();
            $isAdded = $authorStatment->rowCount() == 1;
            if($isAdded)
                return $dbconn->lastInsertId("authors_id_seq");
            else
                return 0;
        }catch (PDOException $e) { 
            echo $e->getMessage();
            return 0;
        }
    }

    /**
    *
    * Returns all the authors
    *
    * @return Array<Author>
    *
    */
    public static function getAuthors()
    {
        try {
            $dbconn = Database::getInstance()->getConnection();
            $statement = $dbconn->prepare('
                SELECT 
                    *
                FROM 
                    authors
                '
            );
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_CLASS,"Author");
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }
} 

?>