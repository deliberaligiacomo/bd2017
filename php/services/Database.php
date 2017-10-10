<?php
    require(__DIR__ . '/../models/DatabaseConnection.php');

    // psql --host dblab.dsi.unive.it --username a2016u104

    /**
     * Rappresent the application database connection
     */
    class Database {

        /**
         * The database connection configuration. Read from settings.json
         * @var Models\DatabaseConnection
         */
        private $dbConfig = null;

        /**
         * The application database instance
         * @var Database
         */
        private static $instance = null;

        /**
         * Mark private for singleton use
         */
        private function __construct() {
            $settings = file_get_contents(__DIR__ . "/settings.json");
            $configs = json_decode($settings, true);
            if ($this->isDebug())
                $this->dbConfig = $configs["Debug"]["Database"];
            else
                $this->dbConfig = $configs["Stage"]["Database"];
        }

        /**
         * Returns the databse instance
         * @return Database
         */
        public static function getInstance() {
            if (Database::$instance == null)
                Database::$instance = new Database();
            return Database::$instance;
        }

        /**
         * Returns a new connection
         * @return Connection
         */
        public function getConnection() {
            $connectionString = 'pgsql:host=' . $this->dbConfig["Url"] . ';port=' . $this->dbConfig["Port"] . ';dbname=' . $this->dbConfig["Name"];
            $connection = new PDO($connectionString, $this->dbConfig["User"], $this->dbConfig["Password"]);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $connection;
        }

        /**
         * Return true if the current REMOTE_ADDRESS is local
         * @return boolean 
         */
        private function isDebug() {
            $whitelist = array('127.0.0.1', '::1', 'localhost');
            return in_array($_SERVER['REMOTE_ADDR'], $whitelist);
        }

    }

?>