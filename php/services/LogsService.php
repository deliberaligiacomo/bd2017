<?php
    require(__DIR__ . '/Defaults.php'); 
    
    /**
     * Provides methods to log information or expection in a log file
     */
    class LogsService {

        /**
         * Append the given string in the specified file
         * @param type $filePath string: The file path
         * @param type $contentToAppend string: The string to append
         */
        private static function log($filePath, $contentToAppend) {
            // Write the contents to the file, 
            // using the FILE_APPEND flag to append the content to the end of the file
            // and the LOCK_EX flag to prevent anyone else writing to the file at the same time
            file_put_contents($filePath, $contentToAppend, FILE_APPEND | LOCK_EX);
        }

        /**
         * Logs in the 'logs/exceptions.txt' the given excpetion object
         * @param type $exceptionObj object
         */
        public static function logException($exceptionObj) {
            $now = new DateTime();
            $str = "---------------\n";
            $str .= $now->format('Y-m-d H:i:s');
            $str .= "\n";
            $str .= $exceptionObj->__toString();
            $str .= "\n---------------\n";
            self::log(__DIR__ . "/../../" . Defaults::LOG_EXCEPTION_FILE, $str);
        }

    }

?>