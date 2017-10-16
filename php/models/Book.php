<?php
    
    /**
     * A Book
     */
    class Book {

        /**
         * The book identifier
         * @var type number
         */
        public $id;

        /**
         * The book title
         * @var type string
         */
        public $title;

        /**
         * The book image url
         * @var type string
         */
        public $image;

        /**
         * The book genre
         * @var type string
         */
        public $genre;

        /**
         * The book rating (number of stars)
         * @var type float
         */
        public $rating;

        /**
         * The autho's book full name
         * @var type string
         */
        public $author;
        
        public function render($hasRate=false){
            $this->rating = BooksService::getBookReviewSummary($this->id);
            if (!$this->rating)
                $this->rating = new BookReviewSummary ();
            echo'<div class="row ">
                <div class="col-md-4">
                    <img src="' . $this->image . '" height="200"/>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-xs-12">
                        <a href="./php/book.php?id=' . $this->id . '"><h4>' . $this->title . '<small>&nbsp;' . $this->author . '</small></h4></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                        <i>' . $this->genre . '</i>
                        </div>
                    </div>';
                    if(hasRate){ echo'
                            <div class="row">
                                <div class="col-xs-12">
                                <div class="rating-block">
                                    <h4>Average user rating</h4>
                                    <h2 class="bold padding-bottom-7">' . ($this->rating->avarage ? $this->rating->avarage : '0') . ' <small>/ 5</small></h2>';
                                        for ($i = 1; $i <= 5; $i++) {
                                        echo '
                                            <button type="button" class="btn btn-warning btn-xs ' . (($i*1.0) > $this->rating->avarage ? 'btn-grey' : '') . '" aria-label="Left Align">
                                                <span class="fa fa-star" aria-hidden="true"></span>
                                            </button>
                                        ';
                                    }
                                    echo'
                                </div>
                                </div>
                            </div>';
                     } echo'
                    </div>
                </div>';
        }

    }

?>