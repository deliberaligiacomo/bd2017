<?php

    require_once(__DIR__ . '/AuthenticationService.php');
    require_once(__DIR__ . '/../models/Book.php');
    require_once(__DIR__ . '/../models/Author.php');
    require_once(__DIR__ . '/../models/Review.php');
    require_once(__DIR__ . '/../models/BookReviewSummary.php');

    /**
     * Provides all Book CRUD operations.
     */
    class BooksService {

        /**
         * Adds a new book
         * @param string title The book title
         * @param string image The book image url
         * @param string genre The book genre
         * @param integer authorId The book authorId 
         * @return The new added book id
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
         * Adds a new review
         * @param string $title
         * @param string $text
         * @param integer $grade
         * @param integer $IdAuthor
         * @param integer $idBook
         * @return boolean
         */
        public static function addReview($title, $text, $grade, $IdAuthor, $idBook) {
            try {
                $dbconn = Database::getInstance()->getConnection();
                $statement = $dbconn->prepare('
                INSERT INTO reviews VALUES (
                        DEFAULT,
                        :title,
                        :text,
                        :grade,
                        0,
                        :idAuthor,
                        :idBook
                    )
                ');
                $statement->bindParam(":title", $title, PDO::PARAM_STR);
                $statement->bindParam(":text", $text, PDO::PARAM_STR);
                $statement->bindParam(":grade", $grade, PDO::PARAM_INT);
                $statement->bindParam(":idAuthor", $IdAuthor, PDO::PARAM_INT);
                $statement->bindParam(":idBook", $idBook, PDO::PARAM_INT);
                $statement->execute();
                return $statement->rowCount() == 1;
            } catch (PDOException $e) {
                LogsService::logException($e);
                return false;
            }
        }

        /**
         * Given a word returns the book's title (one or more) that contains that word, else returns all the titles
         * @param string keyword The keywork to search
         * @return Array<Book>
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
         * Returns the list of books ordered by feedback, starting from the highliest rated
         * @param string keyword The keywork to search
         * @return Array<Book>
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
         * @param integer $bookId integer
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

        /**
         * Gets a Book given its id
         * @param $bookId integer
         * @return Book
         */
        public static function getBook($bookId) {
            if ($bookId == null || $bookId < 0) {
                return null;
            }
            try {
                $dbconn = Database::getInstance()->getConnection();
                $query = '
                        SELECT 
                            *
                        FROM 
                            books
                        WHERE
                            id = :bookId
                    ';

                $statement = $dbconn->prepare($query);
                $statement->bindParam(":bookId", $bookId, PDO::PARAM_INT);
                $statement->execute();
                $books = $statement->fetchAll(PDO::FETCH_CLASS, "Book");

                if (count($books) > 0)
                    return $books[0];

                return null;
            } catch (PDOException $e) {
                LogsService::logException($e);
                return null;
            }
        }

        /**
         * 
         * Return all the reviews of the given book
         * @param $bookId integer The book id
         * @return Array<Review>
         */
        public static function getReviews($bookId) {
            if ($bookId == null || $bookId < 0) {
                return null;
            }
            try {
                $dbconn = Database::getInstance()->getConnection();
                $query = '
                        SELECT 
                            reviews.*,
                            concat_ws(\'\', users.firstname, \' \', users.lastname) as author
                        FROM 
                            reviews JOIN users ON reviews.id_author = users.id
                        WHERE
                            id_book = :bookId
                    ';

                $statement = $dbconn->prepare($query);
                $statement->bindParam(":bookId", $bookId, PDO::PARAM_INT);
                $statement->execute();
                $reviews = $statement->fetchAll(PDO::FETCH_CLASS, "Review");

                return $reviews;
            } catch (PDOException $e) {
                LogsService::logException($e);
                return null;
            }
        }

        /**
         * Returns true if the user has already done a review on the specified book
         * @param integer $authorId The author id
         * @param integer $bookId The book id
         * @return boolean
         */
        public static function hasReview($authorId, $bookId) {
            if ($bookId == null || $bookId < 0 || $authorId == null || $authorId < 0) {
                return null;
            }
            try {
                $dbconn = Database::getInstance()->getConnection();
                $query = '
                        SELECT 
                            *
                        FROM 
                            reviews JOIN users ON reviews.id_author = users.id
                        WHERE
                            id_book = :bookId
                                AND
                            id_author = :authorId
                    ';

                $statement = $dbconn->prepare($query);
                $statement->bindParam(":bookId", $bookId, PDO::PARAM_INT);
                $statement->bindParam(":authorId", $authorId, PDO::PARAM_INT);
                $statement->execute();
                return $statement->rowCount() == 1;
            } catch (PDOException $e) {
                LogsService::logException($e);
                return null;
            }
        }

        /**
         * 
         * Return the book's reviews summary
         * @param $bookId integer The book id
         * @return BookReviewSummary
         */
        public static function getBookReviewSummary($bookId) {
            if ($bookId == null || $bookId < 0) {
                return null;
            }
            try {
                $dbconn = Database::getInstance()->getConnection();
                $query = '
                        SELECT 
                            COUNT(*) AS total,
                            ROUND(AVG(grade),1) AS avarage,
                            coalesce(COUNT(grade) FILTER (WHERE grade = 1),0) AS "oneStar",
                            coalesce(COUNT(grade) FILTER (WHERE grade = 2),0) AS "twoStar",
                            coalesce(COUNT(grade) FILTER (WHERE grade = 3),0) AS "threeStar",
                            coalesce(COUNT(grade) FILTER (WHERE grade = 4),0) AS "fourStar",
                            coalesce(COUNT(grade) FILTER (WHERE grade = 5),0) AS "fiveStar"
                        FROM 
                            reviews
                        WHERE
                            reviews.id_book = :bookId
                    ';

                $statement = $dbconn->prepare($query);
                $statement->bindParam(":bookId", $bookId, PDO::PARAM_INT);
                $statement->execute();
                $reviews = $statement->fetchAll(PDO::FETCH_CLASS, "BookReviewSummary");

                if (count($reviews) > 0)
                    return $reviews[0];

                return null;
            } catch (PDOException $e) {
                LogsService::logException($e);
                return null;
            }
        }

    }

?>