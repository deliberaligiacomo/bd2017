<?php
// psql --host dblab.dsi.unive.it --username a2016u104

class Database { 
    /** local development */
//    /** The database connection url */
//    private $dbUrl = 'localhost';
//    /** The database port */
//    private $dbPort = '5432';
//    /** The databse name */
//    private $dbName = 'orsini';
//    /** The database user */
//    private $dbUser = 'postgres'; 
//    /** The database password for the above user */
//    private $dbPassword = 'root';

    /** Unive development */
    /** The database connection url */
     private $dbUrl = 'dblab.dsi.unive.it';
     /** The database port */
     private $dbPort = '5432';
     /** The databse name */
     private $dbName = 'a2016u104';
     /** The database user */
     private $dbUser = 'a2016u104'; 
     /** The database password for the above user */
     private $dbPassword = 'XPhVUqk6';

    /** The application settings instance */
    private static $instance = null;

    /** Mark private for singleton use */
    private function __construct() {
      // TODO: inject databse info
      // $this->$database = Container.inject(Databse);
    }

    /** Returns the databse instance */
    public static function getInstance() {
        if(Database::$instance == null)
            Database::$instance = new Database();
       return Database::$instance;
    } 

     /** Returns the a new connection */
    public function getConnection(){
        $connectionString = 'pgsql:host=' . $this->dbUrl . ';port=' . $this->dbPort . ';dbname=' . $this->dbName;
        $connection = new PDO($connectionString,$this->dbUser,$this->dbPassword);
        $connection -> setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connection;
    }
} 

?>