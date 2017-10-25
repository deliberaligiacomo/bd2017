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
         * Adds a review grade (+1/-1)
         * @param integer $reviewId The review id         
         * @param integer $userId The user id
         * @param integer $grade The grade
         * @return boolean
         */
        public static function addReviewGrade($reviewId, $userId, $grade) {
            try {
                $dbconn = Database::getInstance()->getConnection();

                $statement = $dbconn->prepare('
                SELECT *
                FROM 
                    grades_reviews
                WHERE 
                        id_user = :userId
                        AND
                        id_review = :reviewId
                ');
                $statement->bindParam(":userId", $userId, PDO::PARAM_INT);
                $statement->bindParam(":reviewId", $reviewId, PDO::PARAM_INT);
                $statement->execute();
                $isExisting = $statement->rowCount() == 1;

                if ($isExisting) {
                    $currentValue = $statement->fetchAll()[0]["grade"];
                    $statement = $dbconn->prepare('
                        UPDATE
                            grades_reviews
                        SET
                            grade = :grade
                        WHERE 
                                id_user = :userId
                                AND
                                id_review = :reviewId
                    ');
                    $statement->bindParam(":userId", $userId, PDO::PARAM_INT);
                    $statement->bindParam(":reviewId", $reviewId, PDO::PARAM_INT);
                    $statement->bindParam(":grade", $grade, PDO::PARAM_INT);
                    $statement->execute();
                    return $statement->rowCount() == 1;
                }

                // ELSE NEW GRADE 
                $statement = $dbconn->prepare('
                INSERT INTO grades_reviews VALUES (
                        :userId,
                        :reviewId,
                        :grade
                    )
                ');
                $currentScore = $grade == Defaults::SCORE_DOWN ? -1 : 1;
                $statement->bindParam(":userId", $userId, PDO::PARAM_INT);
                $statement->bindParam(":reviewId", $reviewId, PDO::PARAM_INT);
                $statement->bindParam(":grade", $currentScore, PDO::PARAM_INT);
                $statement->execute();
                return $statement->rowCount() == 1;
            } catch (PDOException $e) {
                LogsService::logException($e);
                return false;
            }
        }

        /**
         * Given a word returns the book's title (one or more) that contains that word.
         * @param string keyword The keywork to search
         * @return Array<Book>
         */
        public static function research($keyword = null, $sort = Defaults::DESC) {
            try {
                if (!isset($keyword))
                    $keyword = null;
                if (!isset($sort))
                    $sort = Defaults::DESC;

                $dbconn = Database::getInstance()->getConnection();
                $key = ($keyword ? $keyword : '');
                $query = '
                SELECT 
                    b.id, 
                    b.title, 
                    b.image, 
                    b.genre, 
                    AVG(r.grade) AS rate,
                    concat_ws(\'\', a.firstName, \' \', a.lastname) as authorfullname
                FROM 
                    books AS b JOIN books_authors AS ba 
                    ON ba.id_book= b.id JOIN authors AS a ON a.id= ba.id_author
                    LEFT JOIN reviews AS r ON r.id_book=b.id
                WHERE 
                    lower(b.title) LIKE lower(:key)
                        OR
                    lower(concat_ws(\'\', a.firstName, \' \', a.lastname)) LIKE lower(:key)
                        OR
                    lower(b.genre) LIKE lower(:key)
                GROUP BY 
                    b.id, 
                    b.title, 
                    b.image, 
                    b.genre, 
                    authorfullname
                ORDER BY 
                    rate '. $sort . ' NULLS LAST';
                $statement = $dbconn->prepare($query);
                $statement->bindValue(":key", '%' . $keyword . '%');
                $row = $statement->execute();

                $books = array();
                while (($row = $statement->fetch(PDO::FETCH_ASSOC)) !== false) {
                    $book = new Book();
                    $book->id = $row['id'];
                    $book->title = $row['title'];
                    $book->image = $row['image'];
                    $book->genre = $row['genre'];
                    $book->author = $row['authorfullname'];
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
                            b.*,
                            concat_ws(\'\', a.firstname, \' \', a.lastname) as author
                        FROM books b JOIN books_authors ba ON ba.id_book = b.id
                        JOIN authors a ON ba.id_author = a.id
                        WHERE
                            b.id = :bookId
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
                            concat_ws(\'\', users.firstname, \' \', users.lastname) as author,
                            SUM(coalesce(grades_reviews.grade,0)) as score
                        FROM 
                            reviews JOIN users ON reviews.id_author = users.id
                            LEFT JOIN grades_reviews ON reviews.id_review = grades_reviews.id_review
                        WHERE
                            id_book = :bookId
                        GROUP BY reviews.id_review,author
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
                            COUNT(CASE WHEN grade = 1 THEN grade END) AS "oneStar",
                            COUNT(CASE WHEN grade = 2 THEN grade END) AS "twoStar",
                            COUNT(CASE WHEN grade = 3 THEN grade END) AS "threeStar",
                            COUNT(CASE WHEN grade = 4 THEN grade END) AS "fourStar",
                            COUNT(CASE WHEN grade = 5 THEN grade END) AS "fiveStar"
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
