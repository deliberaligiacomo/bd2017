<?php

require_once(__DIR__ . "/Authentication.php");
require_once(__DIR__ . "/Defaults.php");
require_once(__DIR__ . "/Book.php");

class Search
{

    
    /**
    *
    * Given a word returns the book's title (one or more) that contains that word, else returns all the titles
    *
    * @param keyword The keywork to search
    * @return Array<Book>
    *
    */
    public static function research($keyword = null, $sort = Defaults::ASC)
    {
        try {
            $dbconn = Database::getInstance()->getConnection();
            $query = '
                SELECT 
                    b.id,
                    b.title,
                    b.image,
                    b.genre,
                    a.firstName AS authorFirstName,
                    a.lastName AS authorLastName
                FROM 
                    books AS b JOIN
                    books_authors AS ba ON ba.id_book = b.id 
                    JOIN authors AS a ON ba.id_author = a.id
                    ' . ($keyword ? "
                                        WHERE 
                                            lower(b.title) LIKE '%' || lower(:keyword) || '%'"
                    : ''
            );
            $statement = $dbconn->prepare($query);
            if($keyword)
                $statement->bindParam(":keyword", $keyword, PDO::PARAM_STR);
          
            $statement->execute();

            $books = array();
            while (($row = $statement->fetch(PDO::FETCH_ASSOC)) !== false) {
                $book = new Book();
                $book->id = $row['id'];
                $book->title = $row['title'];
                $book->image = $row['image'];
                $book->genre = $row['genre'];
                $book->author = $row['authorfirstname'] . ' ' . $row['authorlastname'];
                $book->rating = 0;
                $books[] = $book;
            }
            return $books;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return null;
        }
    }

    /**
    *
    * Returns the list of books ordered by feedback, starting from the highliest rated
    *
    * @param keyword The keywork to search
    * @return Array<Book>
    *
    */
    public static function listByFeedback()
    {
        try {
            $dbconn = Database::getInstance()->getConnection();
            $query='
                SELECT d.id, 
                    b.title, 
                    b.image, 
                    b.genre, 
                    a.firstName AS authorFirstName, 
                    a.lastName AS authorLastName, 
                    r.text, 
                    AVG(r.score) AS rating, 
                    SUM(r.grade)
                FROM books AS b JOIN 
                     reviews AS r ON b.id=r.id_book 
                     JOIN authors AS a ON a.id= r.id_authors
                GROUP BY b.id
                ORDER BY DESC';
            $statement = $dbconn->prepare($query);
            $statement->execute();
            $books = array();
            while (($row = $statement->fetch(PDO::FETCH_ASSOC)) !== false) {
                $book = new Book();
                $book->id = $row['id'];
                $book->title = $row['title'];
                $book->image = $row['image'];
                $book->genre = $row['genre'];
                $book->author = $row['authorfirstname'] . ' ' . $row['authorlastname'];
                $book->rating = $row['rating'];
                $books[] = $book;
            }
            return $books;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
