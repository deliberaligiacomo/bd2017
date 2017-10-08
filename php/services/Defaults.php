<?php

    /**
     * A static class with all default values used all across the application
     */
    class Defaults {

        /** Default ASC mode */
        const ASC = 'asc';

        /** Defualt DESC mode */
        const DESC = 'desc';

        /** Server default base starting url */
        const DEFAULT_BASE_URL = '/bd2017';
        
        /**
         * The file where exception will be logged in. Relative path from web root.
         */
        const LOG_EXCEPTION_FILE = '/php/logs/exceptions.txt';

    }

?>