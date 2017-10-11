<?php
    require_once( __DIR__ . '/../services/AuthenticationService.php');
    require_once( __DIR__ . '/../services/Defaults.php');
?>
<!-- Modal -->
<div class="modal fade" id="comments" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Comments</h4>
            </div>
            <div class="modal-body">
                <!-- START COMMENTS -->

                <div class="card comment">
                    <div class="card-header">
                        drigon commented 5 hours ago
                    </div>
                    <div class="card-block">
                        <p class="card-text p-1">With supporting text below as a natural lead-in to additional content.</p>
                    </div>
                </div>
                <div class="card comment comment-1">
                    <div class="card-header">
                        drigon commented 5 hours ago
                    </div>
                    <div class="card-block">
                        <p class="card-text p-1">With supporting text below as a natural lead-in to additional content.</p>
                    </div>
                </div>

                <!-- END COMMENTS -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary btn-sm"  data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>