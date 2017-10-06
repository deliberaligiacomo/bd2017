<?php 
ob_start();
session_start();
require_once(__DIR__ . '/Database.php');

class AuthenticationService { 

    /**
    *
    * Given a username and a MD5 password returns true if a user with the given credentials exisits or not.
    *
    * @param username The user username
    * @param password The user password (in MD5)
    * @return boolean
    *
    */
    public static function login($username, $password){
        try{
            $dbconn = Database::getInstance()->getConnection();
            $statement = $dbconn->prepare('SELECT username FROM users WHERE lower(username) = lower(:username) AND password = :password');
            $statement->bindParam(":username",$username,PDO::PARAM_STR);
            $statement->bindParam(":password",$password,PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_NUM);
            if($result[0] != null){
                // set session username
                $_SESSION["Username"] = $result[0];
                return true;
            }
            // destry the session username and return false
            unset($_SESSION["Username"]);
            return false;
        } catch (PDOException $e) { 
            echo $e->getMessage();
            return false;
        }
    }

    /**
    *
    * Returns true if a session username exisits, false othwrwise.
    *
    * @return boolean
    *
    */
    public static function isLoggedIn(){
        return  (isset($_SESSION["Username"]) && $_SESSION["Username"] != null) ? true : false; 
    }

    /**
 *
 * Register a new user
 *
 * @param username The user username
 * @param password The user password (in MD5)
 * @param firstName The user name
 * @param lastName The user surname
 * @param email The user email
 * @param birthdate The user birthdate
 * @return boolean
 *
 */
public static function register($username, $password,$firstName,$lastName,$email,$birthdate){
      try{
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
        $statement->bindParam(":username",$username,PDO::PARAM_STR);
        $statement->bindParam(":password",$password,PDO::PARAM_STR);
        $statement->bindParam(":firstName",$name,PDO::PARAM_STR);
        $statement->bindParam(":lastName",$surname,PDO::PARAM_STR);
        $statement->bindParam(":email",$email,PDO::PARAM_STR);
        $statement->bindParam(":birthdate",$birthdate,PDO::PARAM_STR);
        $statement->execute();
        return $statement->rowCount() == 1;
    } catch (PDOException $e) { 
         echo $e->getMessage();
        return false;
    }
}

} 

?>