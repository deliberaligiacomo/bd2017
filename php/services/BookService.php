<?php 
require_once(__DIR__ . '/Authentication.php');
require_once(__DIR__ . '/Book.php');
require_once(__DIR__ . '/Author.php');

class BookService { 
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
                return $dbconn->lastInsertId();
            else
                return 0;
        }catch (PDOException $e) { 
            echo $e->getMessage();
            return 0;
        }
    }


    /**
    *
    * Adds a new book
    *
    * @param title The book title
    * @param image The book image url
    * @param genre The book genre
    * @param authorId The book authorId 
    * @return The new added book id
    *
    */
    public static function addBook($title, $image,$genre,$authorId){
         try{
            $dbconn = Database::getInstance()->getConnection();
            $statement = $dbconn->prepare('
                INSERT INTO books VALUES (
                    DEFAULT,
                    :title,
                    :image,
                    :genre
                )
            ');
            $statement->bindParam(":title", $title, PDO::PARAM_STR);
            $statement->bindParam(":image", $image, PDO::PARAM_STR);
            $statement->bindParam(":genre", $genre, PDO::PARAM_STR);
            $statement->execute();
            $isBookAdded = $statement->rowCount() == 1;

            if($isBookAdded){
                $statement = $dbconn->prepare('
                    INSERT INTO books_authors VALUES (
                        :authorId,
                        :bookId
                    )
                ');
                $bookId = $dbconn->lastInsertId();
                $statement->bindParam(":authorId",$authorId, PDO::PARAM_INT);
                $statement->bindParam(":bookId",$bookId, PDO::PARAM_INT);
                $statement->execute();
                $isLinkAdded = $statement->rowCount() == 1;
            }

            return $isBookAdded && $isLinkAdded;

        } catch (PDOException $e) { 
            echo $e->getMessage();
            return false;
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