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
            <div class="modal-body" id="commentsList">
                <!-- START COMMENTS -->


                <!-- END COMMENTS -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary btn-sm"  data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on("click", ".comments-dialog-opener", function () {
        var reviewId = $(this).attr("data-review-id");
        $.getJSON("<?php echo Defaults::DEFAULT_BASE_URL; ?>/php/rest/get-comments.php?reviewId=" + reviewId, function (data) {
            var comments = [];
            $.each(data, function (key, val) {
                comments.push(`
                    <div class="card comment">
                        <div class="card-header">
                            ` + val.userfullname + ` commented ` + moment(val.date_comment).startOf('day').fromNow() + `
                        </div>
                        <div class="card-block">
                            <p class="card-text p-1">` + val.text + `</p>
                        </div>
                    </div>
                `);
            });


            if (comments.length > 0)
                $("#commentsList").html(comments.join());
            else
                $("#commentsList").html("<div class='alert alert-warning'>No comments</div>");
        });
    });
</script>
