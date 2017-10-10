<?php

    /**
     * A Book review summary
     */
    class BookReviewSummary {

        /**
         * The total numer of reviews
         * @var integer
         */
        public $total;

        /**
         * The avarage value
         * @var float
         */
        public $avarage;

        /**
         * The number of reviews with one star
         * @var integer
         */
        public $oneStar;

        /**
         * The number of reviews with two star
         * @var integer
         */
        public $twoStar;

        /**
         * The number of reviews three two star
         * @var integer
         */
        public $threeStar;

        /**
         * The number of reviews with four star
         * @var integer
         */
        public $fourStar;

        /**
         * The number of reviews with five star
         * @var integer
         */
        public $fiveStar;

        public function getTemplate() {
            $currentTotal = $this->total || 1;
            
            echo '
                <div class="row">
                    <div class="col-sm-6">
                        <div class="rating-block">
                            <h4>Average user rating</h4>
                            <h2 class="bold padding-bottom-7">' . ($this->avarage ? $this->avarage : '0') . ' <small>/ 5</small></h2>';
                            for ($i = 1; $i <= 5; $i++) {
                                echo '
                                    <button type="button" class="btn btn-warning btn-xs ' . (($i*1.0) > $this->avarage ? 'btn-grey' : '') . '" aria-label="Left Align">
                                        <span class="fa fa-star" aria-hidden="true"></span>
                                    </button>
                                ';
                            }
                            echo'
                        </div>
                    </div>
                    <!-- STARS -->
                    <div class="col-sm-6 rating-block">
                        <h4>Rating breakdown</h4>
                        <div class="pull-left">
                            <div class="pull-left" style="width:35px; line-height:1;">
                                <div style="height:9px; margin:5px 0;">5 <span class="fa fa-star"></span></div>
                            </div>
                            <div class="pull-left" style="width:180px;">
                                <div class="progress" style="height:9px; margin:8px 0;">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="5" style="width: ' . $this->fiveStar/$currentTotal*100 . '%"></div>
                                </div>
                            </div>
                            <div class="pull-right" style="margin-left:10px;">' . $this->fiveStar . '</div>
                        </div>
                        

                        <div class="pull-left">
                            <div class="pull-left" style="width:35px; line-height:1;">
                                <div style="height:9px; margin:5px 0;">4 <span class="fa fa-star"></span></div>
                            </div>
                            <div class="pull-left" style="width:180px;">
                                <div class="progress" style="height:9px; margin:8px 0;">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="5" style="width: ' . $this->fourStar/$currentTotal*100 . '%"></div>
                                </div>
                            </div>
                            <div class="pull-right" style="margin-left:10px;">' . $this->fourStar . '</div>
                        </div>
                        
                        <div class="pull-left">
                            <div class="pull-left" style="width:35px; line-height:1;">
                                <div style="height:9px; margin:5px 0;">3 <span class="fa fa-star"></span></div>
                            </div>
                            <div class="pull-left" style="width:180px;">
                                <div class="progress" style="height:9px; margin:8px 0;">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="5" style="width: ' . $this->threeStar/$currentTotal*100 . '%"></div>
                                </div>
                            </div>
                            <div class="pull-right" style="margin-left:10px;">' . $this->threeStar . '</div>
                        </div>

                        <div class="pull-left">
                            <div class="pull-left" style="width:35px; line-height:1;">
                                <div style="height:9px; margin:5px 0;">2 <span class="fa fa-star"></span></div>
                            </div>
                            <div class="pull-left" style="width:180px;">
                                <div class="progress" style="height:9px; margin:8px 0;">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="5" style="width: ' . $this->twoStar/$currentTotal*100 . '%"></div>
                                </div>
                            </div>
                            <div class="pull-right" style="margin-left:10px;">' . $this->twoStar . '</div>
                        </div>
                        
                        <div class="pull-left">
                            <div class="pull-left" style="width:35px; line-height:1;">
                                <div style="height:9px; margin:5px 0;">1 <span class="fa fa-star"></span></div>
                            </div>
                            <div class="pull-left" style="width:180px;">
                                <div class="progress" style="height:9px; margin:8px 0;">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="5" style="width: ' . $this->oneStar/$currentTotal*100 . '%"></div>
                                </div>
                            </div>
                            <div class="pull-right" style="margin-left:10px;">' . $this->oneStar . '</div>
                        </div>

                    </div>
                </div>                
            ';
        }

    }

?>