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

        /**
         * The score remove value
         */
        const SCORE_REMOVE = 0;

        /**
         * The score up value
         */
        const SCORE_UP = 1;

        /**
         * The score down value
         */
        const SCORE_DOWN = -1;

    }

?>