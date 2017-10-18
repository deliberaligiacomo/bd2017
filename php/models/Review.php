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
         * Constructor
         * @param integer $bookId The option book id (for the form rendering)
         */
        public function __construct($bookId = null) {
            if ($bookId)
                $this->id_book = $bookId;
        }

        /**
         * Renders the template of the current element
         */
        public function render() {
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
            echo '<a class="btn btn btn-outline-success" href="' . Defaults::DEFAULT_BASE_URL . '/php/rest/add-grade.php?type=review&grade=' . Defaults::SCORE_UP . '&userId=' . AuthenticationService::getUserId() . '&reviewId=' . $this->id_review . '&prevUrl=' . $_SERVER["REQUEST_URI"] . '"><i class="fa fa-thumbs-o-up"></i></a>
                  <a class="btn btn btn-outline-danger" href="' . Defaults::DEFAULT_BASE_URL . '/php/rest/add-grade.php?type=review&grade=' . Defaults::SCORE_DOWN . '&userId=' . AuthenticationService::getUserId() . '&reviewId=' . $this->id_review . '&prevUrl=' . $_SERVER["REQUEST_URI"] . '"><i class="fa fa-thumbs-o-down"></i></a>
                  <a class="btn btn btn-outline-primary" href="' . Defaults::DEFAULT_BASE_URL . '/php/rest/add-grade.php?type=review&grade=' . Defaults::SCORE_REMOVE . '&userId=' . AuthenticationService::getUserId() . '&reviewId=' . $this->id_review . '&prevUrl=' . $_SERVER["REQUEST_URI"] . '"><i class="fa fa-times"></i></a>
                  <a class="btn btn btn-outline-primary">' . $this->score . '</a>
                    </div>
                            <div class="review-block-title">' . $this->title . '</div>
                            <div class="review-block-description">' . $this->text . '</div>
                                
                            <a class="btn btn-outline-primary btn-sm comments-dialog-opener" data-review-id=' . $this->id_review . ' data-toggle="modal" data-target="#comments" style="margin-left: calc(100% - 85px);margin-top: 10px;">
                                Comments
                            </a>
                    </div>
                </div>
                </div>
            ';
        }

        public function renderForm() {
            echo '
                <form action="' . Defaults::DEFAULT_BASE_URL . '/php/rest/add-review.php" method="post" name="AddReviewForm">
                        <div class="review-block" style="min-width: 100%">
                            <span>' . AuthenticationService::getFullName() . '</span>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="review-block-rate">
                                        <button type="button" class="btn btn-warning btn-xs" id="star1" onclick="setStar(1)">
                                            <span class="fa fa-star" aria-hidden="true"></span>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-xs" id="star2" onclick="setStar(2)">
                                            <span class="fa fa-star" aria-hidden="true"></span>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-xs" id="star3" onclick="setStar(3)">
                                            <span class="fa fa-star" aria-hidden="true"></span>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-xs" id="star4" onclick="setStar(4)">
                                            <span class="fa fa-star" aria-hidden="true"></span>
                                        </button>
                                        <button type="button" class="btn btn-warning btn-xs" id="star5" onclick="setStar(5)">
                                            <span class="fa fa-star" aria-hidden="true"></span>
                                        </button>
                                    </div>
                                    <div class="review-block-title">
                                        <input type="text" placeholder="Title" name="Title" class="form-control">
                                    </div>
                                    <div class="review-block-description">
                                        <textarea placeholder="Description" name="Text" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <input type="submit" value="Submit" class="btn btn-sm" style="margin-left: calc(100% - 60px); margin-top: 10px;"/>
                        </div>
                        <input type="hidden" name="Grade" id="Grade" value="5"/>    
                        <input type="hidden" name="IdBook" value="' . $this->id_book . '"/>                       
                        <input type="hidden" name="PrevUrl" value="' . $_SERVER['REQUEST_URI'] . '"/>
                        <input type="hidden" name="IdAuthor" value="' . AuthenticationService::getUserId() . '"/>
                    </form>

                    <script>
                        function setStar(value) {
                            for (i = 1; i <= 5; i++) {
                                if (i <= value) {
                                    $("#star" + i).removeClass("btn-grey");
                                } else {
                                    $("#star" + i).addClass("btn-grey");
                                }
                            }
                            $("#Grade").val(value);
                        }
                    </script>
                ';
        }

    }

?>