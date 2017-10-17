<?php

    ob_start();
    session_start();
    require_once(__DIR__ . '/Database.php');
    require_once(__DIR__ . '/LogsService.php');

    /**
     * Provides base methods for authenticating the users and all user CRUD operations.
     */
    class AuthenticationService {

        /**
         * Given a username and a MD5 password returns true if a user with the given credentials exisits or not.
         * @param username string The user username
         * @param password string The user password (in MD5)
         * @return boolean
         *
         */
        public static function login($username, $password) {
            try {
                $dbconn = Database::getInstance()->getConnection();
                $statement = $dbconn->prepare('
                    SELECT 
                        id, 
                        username,
                        concat_ws(\'\', firstName, \' \', lastname) as userfullname
                    FROM 
                        users 
                    WHERE 
                        lower(username) = lower(:username) AND password = :password
                ');
                $statement->bindParam(":username", $username, PDO::PARAM_STR);
                $statement->bindParam(":password", $password, PDO::PARAM_STR);
                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_NUM);
                if ($result[0] != null) {
                    // set session username
                    $_SESSION["UserId"] = $result[0];
                    $_SESSION["Username"] = $result[1];
                    $_SESSION["UserFullName"] = $result[2];
                    return true;
                }
                // destry the session username and return false
                unset($_SESSION["Username"]);
                unset($_SESSION["UserId"]);
                unset($_SESSION["UserFullName"]);
                return false;
            } catch (PDOException $e) {
                LogsService::logException($e);
                return false;
            }
        }

        /**
         * Returns true if a session username exisits, false othwrwise.
         * @return boolean
         */
        public static function isLoggedIn() {
            return (isset($_SESSION["Username"]) && $_SESSION["Username"] != null) ? true : false;
        }

        /**
         * Returns the current logged in user username or null
         * @return string
         */
        public static function getUsername() {
            return self::isLoggedIn() ? $_SESSION["Username"] : null;
        }
        
        /**
         * Returns the current logged in user fullname or null
         * @return string
         */
        public static function getFullName() {
            return self::isLoggedIn() ? $_SESSION["UserFullName"] : null;
        }

        /**
         * Returns the current logged in user id or null
         * @return integer
         */
        public static function getUserId() {
            return self::isLoggedIn() ? $_SESSION["UserId"] : null;
        }

        /**
         * Register a new user
         * @param string username The user username
         * @param string password The user password (in MD5)
         * @param string firstName The user name
         * @param string lastName The user surname
         * @param string email The user email
         * @param string birthdate The user birthdate
         * @return boolean
         *
         */
        public static function register($username, $password, $firstName, $lastName, $email, $birthdate) {
            try {
                $dbconn = Database::getInstance()->getConnection();
                $statement = $dbconn->prepare('INSERT INTO users VALUES (
            DEFAULT,
            :username,
            :password,
            :firstName,
            :lastName,
            :email,
            :birthdate
        )');
                $statement->bindParam(":username", $username, PDO::PARAM_STR);
                $statement->bindParam(":password", $password, PDO::PARAM_STR);
                $statement->bindParam(":firstName", $firstName, PDO::PARAM_STR);
                $statement->bindParam(":lastName", $lastName, PDO::PARAM_STR);
                $statement->bindParam(":email", $email, PDO::PARAM_STR);
                $statement->bindParam(":birthdate", $birthdate, PDO::PARAM_STR);
                $statement->execute();
                return $statement->rowCount() == 1;
            } catch (PDOException $e) {
                LogsService::logException($e);
                return false;
            }
        }

    }

?>