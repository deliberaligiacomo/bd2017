<?php

    /**
     * A Comment
     */
    class Comment {

        /**
         * The comment identifier
         * @var integer
         */
        public $id_comment;

        /**
         * The text content
         * @var string
         */
        public $text;

        /**
         * The comment score
         * @var integer
         */
        public $score;

        /**
         * The user identifier
         * @var integer
         */
        public $id_user;
        
        /**
         * The user full name
         * @var string
         */
        public $userfullname;

        /**
         * The review id
         * @var integer
         */
        public $id_review;

        /**
         * The parent comment identifier
         * @var integer
         */
        public $id_ref_comm;
        
        /**
         * The comment datetime
         * @var string
         */
        public $date_comment;
        
        
        /**
         * The children comment of the current comment
         * @var Array<Comment> 
         */
        public $children;

    }

?>