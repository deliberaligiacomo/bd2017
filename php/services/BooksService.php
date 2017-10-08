<?php

    require_once(__DIR__ . '/AuthenticationService.php');
    require_once(__DIR__ . '/../models/Book.php');
    require_once(__DIR__ . '/../models/Author.php');

    /**
     * Provides all Book CRUD operations.
     */
    class BooksService {

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
        public static function addBook($title, $image, $genre, $authorId) {
            try {
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

                if ($isBookAdded) {
                    $statement = $dbconn->prepare('
                    INSERT INTO books_authors VALUES (
                        :authorId,
                        :bookId
                    )
                ');
                    $bookId = $dbconn->lastInsertId("books_id_seq");
                    $statement->bindParam(":authorId", $authorId, PDO::PARAM_INT);
                    $statement->bindParam(":bookId", $bookId, PDO::PARAM_INT);
                    $statement->execute();
                    $isLinkAdded = $statement->rowCount() == 1;
                }

                return $isBookAdded && $isLinkAdded;
            } catch (PDOException $e) {
                LogsService::logException($e);
                return false;
            }
        }

        /**
         *
         * Given a word returns the book's title (one or more) that contains that word, else returns all the titles
         *
         * @param keyword The keywork to search
         * @return Array<Book>
         *
         */
        public static function research($keyword = null, $sort = Defaults::ASC) {
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
                                            lower(b.title) LIKE '%' || lower(:keyword) || '%'" : ''
                        );
                $statement = $dbconn->prepare($query);
                if ($keyword)
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
                LogsService::logException($e);
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
        public static function listByFeedback() {
            try {
                $dbconn = Database::getInstance()->getConnection();
                $query = '
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
                LogsService::logException($e);
                return false;
            }
        }

        /**
         * Deletes a Book given its id
         * @param $bookId integer
         * @return boolean
         */
        public static function deleteBook($bookId) {
            if ($bookId == null || $bookId < 0) {
                return false;
            }
            try {
                $dbconn = Database::getInstance()->getConnection();
                $query = '
                        DELETE
                        FROM 
                            books
                        WHERE
                            id = :bookId
                    ';

                $statement = $dbconn->prepare($query);
                $statement->bindParam(":bookId", $bookId, PDO::PARAM_INT);
                $result = $statement->execute();
                return $result->rowCount() == 1;
            } catch (PDOException $e) {
                LogsService::logException($e);
                return null;
            }
        }

    }

?>