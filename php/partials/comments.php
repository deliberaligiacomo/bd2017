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

                <template v-if="comments.length">
                    <comment
                        v-for="comment in comments"
                        v-bind:comment="comment"
                        v-bind:level="0"
                        v-bind:key="comment.id_comment">
                    </comment>
                </template>
                <div class="alert alert-warning" v-else>
                    {{emptyMessage}}
                </div>

                <!-- END COMMENTS -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary btn-sm"  data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    Vue.prototype.window = window;
    Vue.http.headers.common['content-type'] = 'application/json';
    
    Vue.component('comment', {
    name: 'comment',
            props: ["comment","level","isLoading"],
            template: `
                <div>
                    <div v-bind:class="'card comment-' + level">
                        <div class="card-header">
                                {{comment.userfullname}} commented {{window.moment(comment.date_comment).startOf('day').fromNow()}}
                                <template v-if="comment.id_comment > 0">
                                    <a class="btn btn-sm btn-outline-success" v-on:click="gradeUp"><i class="fa fa-thumbs-o-up"></i></a>
                                    <a class="btn btn-sm btn-outline-danger"  v-on:click="gradeDown"><i class="fa fa-thumbs-o-down"></i></a>
                                    <a class="btn btn-sm btn-outline-primary" v-on:click="gradeRemove"><i class="fa-times"></i></a>
                                    <a class="btn btn-sm btn-outline-primary">{{comment.score}}</a>
                                    <i class="fa fa-reply" v-on:click="reply"></i>
                                </template>
                        </div>
                        <div class="card-block">
                            <template v-if="!(comment.id_comment > 0)">
                                <input type="text" class="form-control" v-model="comment.text" style="max-width: calc(100% - 38px); display: inline"/>
                                <button class="btn btn-sm btn-outline-success">
                                    <i class="fa fa-save" v-on:click="save" v-if="!isLoading"></i>
                                    <i class="fa fa-circle-o-notch fa-spin" v-else></i>
                                </button>
                            </template>
                            <p class="card-text p-1" v-else>{{comment.text}}</p>
                        </div>
                    </div>
                    <template v-if="comment.children && comment.children.length > 0">
                        <comment
                            v-for="child in comment.children"
                            v-bind:comment="child"
                            v-bind:level="level+1"
                            v-bind:key="child.id_comment">
                        </comment>
                    </template>
                </div>
            `,
            methods: {
                save: function () {
                    if(this.comment.text && this.comment.text.length){
                        this.isLoading = true;
                        this.$http.post(
                             "<?php echo Defaults::DEFAULT_BASE_URL; ?>/php/rest/add-comment.php",
                             this.comment
                        ).then(data => data.json())
                        .then(data => {
                            if(data != null){
                                this.comment.id_comment = data;
                                this.refresh();
                                this.isLoading = true;
                            }
                        }).catch((ex)=>{
                            console.error(ex);
                            this.isLoading = true;
                        });
                    }
                },
                refresh(){
                    this.$forceUpdate();
                },
                reply(){
                    if(!this.comment.children)
                        this.comment.children = [];
                    if(this.comment.children.length == 0 || (this.comment.children[this.comment.children.length - 1].id_comment > 0)){
                        var newComment = JSON.parse(JSON.stringify(newEmptyComment));
                        newComment.id_ref_comm = this.comment.id_comment || null;
                        this.comment.children.push(newComment);
                    }
                },
                gradeUp(){
                    var url = '<?php echo Defaults::DEFAULT_BASE_URL?>/php/rest/add-comment-grade.php?type=comment&grade=<?php echo Defaults::SCORE_UP?>&userId=<?php echo AuthenticationService::getUserId()?>&commentId=' + this.comment.id_comment + '&prevUrl=<?php echo $_SERVER["REQUEST_URI"]?>';
                    this.$http.get(url)
                                .then(data => data.json())
                                .then(data =>{
                                    if(data)
                                         this.comment.score = data;
                    });
                },
                gradeDown(){
                    var url = '<?php echo Defaults::DEFAULT_BASE_URL?>/php/rest/add-comment-grade.php?type=comment&grade=<?php echo Defaults::SCORE_DOWN?>&userId=<?php echo AuthenticationService::getUserId()?>&commentId=' + this.comment.id_comment + '&prevUrl=<?php echo $_SERVER["REQUEST_URI"]?>';
                    this.$http.get(url)
                                .then(data => data.json())
                                .then(data =>{
                                    if(data)
                                         this.comment.score = data;
                    });
                },
                gradeRemove(){
                    var url = '<?php echo Defaults::DEFAULT_BASE_URL?>/php/rest/add-comment-grade.php?type=comment&grade=<?php echo Defaults::SCORE_REMOVE?>&userId=<?php echo AuthenticationService::getUserId()?>&commentId=' + this.comment.id_comment + '&prevUrl=<?php echo $_SERVER["REQUEST_URI"]?>';
                    this.$http.get(url)
                                .then(data => data.json())
                                .then(data =>{
                                    if(data)
                                         this.comment.score = data;
                    });
                }
            }
    });
    var commentsSection = new Vue({
    el: '#commentsList',
            data: {
                comments: [],
                emptyMessage: "No comments found"
            }
    });
    var newEmptyComment = null;
    $(document).on("click", ".comments-dialog-opener", function () {
        var reviewId = $(this).attr("data-review-id");
        
            var d = new Date();
            d.setTime( d.getTime() + d.getTimezoneOffset()*60*1000 );
        newEmptyComment = {
                userfullname: "<?php echo AuthenticationService::getFullName() ?>",
                id_user: <?php echo AuthenticationService::getUserId() ?>,
                id_review: Number(reviewId),
                date_comment: d.toISOString(),
                score: 0
            };
        commentsSection.comments = [];
        $.getJSON("<?php echo Defaults::DEFAULT_BASE_URL; ?>/php/rest/get-comments.php?reviewId=" + reviewId, function (data) {
            commentsSection.comments = data || [];
            commentsSection.comments.push(newEmptyComment);
        });
    });
</script>
