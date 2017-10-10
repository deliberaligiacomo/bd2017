<?php

    /**
     * A book review
     */
    class Review {

        /**
         * The review id
         * @var integer 
         */
        public $id_review;

        /**
         * The review title
         * @var string
         */
        public $title;

        /**
         * The review text content
         * @var string 
         */
        public $text;

        /**
         * The review number of stars (1-5)
         * @var integer 
         */
        public $grade;

        /**
         * The total review score (+1/-1)
         * @var integer
         */
        public $score;

        /**
         * The review author's id
         * @var integer 
         */
        public $id_author;
        
        /**
         * The review author full name
         * @var string 
         */
        public $author;

        /**
         * The review book's id
         * @var integer
         */
        public $id_book;

        /**
         * Returns the row HTML template of the current element
         * @return string The row html template
         */
        public function getTemplate() {
            echo '
                <div class="review-block">
                <span>' . $this->author . '</span>
                <div class="row">
                    <div class="col-sm-12">
                            <div class="review-block-rate">';
            for ($i = 1; $i <= 5; $i++) {
                echo '
                    <button type="button" class="btn btn-warning btn-xs ' . ($i > $this->grade ? 'btn-grey' : '') . '" aria-label="Left Align">
                        <span class="fa fa-star" aria-hidden="true"></span>
                    </button>
                ';
            }
            echo '</div>
                            <div class="review-block-title">' . $this->title . '</div>
                            <div class="review-block-description">' . $this->text . '</div>
                    </div>
                </div>
                </div>
            ';
        }

    }

?>