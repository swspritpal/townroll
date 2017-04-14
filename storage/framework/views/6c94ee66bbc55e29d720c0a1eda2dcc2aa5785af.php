<?php  
  $post->comments=$post->lastest_comments->reverse();
 ?>

<?php $__env->startSection('after-scripts'); ?>
  <?php echo Html::script(asset('js/post-view.js')); ?>

  <?php echo Html::script(asset('js/comments/autosize.min.js')); ?>

  <?php echo Html::script(asset('js/comments/hightlight.js')); ?>

  <?php echo Html::script(asset('js/comments/imgLiquid-min.js')); ?>

  <?php echo Html::script(asset('js/comments/marked.js')); ?>

  <?php echo Html::script(asset('js/comments/comments.js')); ?>

<?php $__env->stopSection(); ?>

<!-- 'redirect'=>(isset($redirect) && $redirect ? $redirect:'') -->

<div class="comment-main-wrappper">

    <div class="reaction clearfix post-counter-bar">

        <a  href="javascript:void(0);"  class="btn text-grey hoverRed paddingUnset " data-post-id="<?php echo e($post->id); ?>">
          <i class=" fa  <?php echo e($post->liked() ? 'fa-heart' : 'fa-heart-o'); ?> post-like-click"></i>
        </a>
        <a href="javascript:void(0);" data-post-id="<?php echo e($post->id); ?>" class="btn text-grey hoverRed paddingUnset post-liked-users"> 
            <span class="post-like-counter"><?php echo e(!empty($post->like_count()) ? $post->like_count() : "0"); ?></span> Likes
        </a>

        <a class="btn text-grey hoverOlive " href="javascript:void(0);"  data-toggle="modal" data-target="#allComments"><i class="fa fa-comment-o"></i> <span class="home-comment-counter"><?php echo $post->comments_count; ?></span> Comments</a>
        <a class="btn text-grey hoverCyan post-view-by-users" href="javascript:void(0);"  data-post-id="<?php echo e($post->id); ?>">
          <i class="fa fa-eye"></i> <?php echo e(!empty($post->view_count()) ? $post->view_count() : "0"); ?>  Views
        </a>

        <span class="pull-right"> 
        <a class="btn text-grey hoverOrange paddingUnset" title="Boost post" data-toggle="modal" data-target="#postBoot"><i class="fa fa-rocket"></i> Boost</a> 
        <a class="btn text-grey hoverOrange paddingUnset" href="javascript:void(0);"  data-toggle="modal" data-target="#allBoost">(8)</a> </span>
        <div style="clear:both"> </div>
    </div>
    <div class="line-divider"></div>


    <div class="comments-container"
         data-api-url="<?php echo e(route('frontend.comment.show',[$post->id,
         'commentable_type'=>'App\Post',
         'last_comment_id'=>!empty($post->comments->last())? $post->comments->last()->id:0])); ?>">

         <?php if(!empty($post->comments_count) && $post->comments_count > env('DEFAULT_HOME_PAGE_POST_COMMENTS') ): ?>
           <p><a href="javascript:void(0);"> View more comments </a></p>
        <?php endif; ?>
        <?php echo $__env->make('frontend.comment.show',[ 'comments' => $post->comments,'post_user_id' =>$post->user->id ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    </div>

    <div class="clearfix comment-add-new">
        <div class="col-lg-1 col-md-1 col-sm-2 col-xs-2 paddingUnset">
          <img src="<?php echo e($logged_in_user->picture); ?>" alt="" class="profile-photo-sm">
        </div>
        <div class="col-lg-11 col-md-11 col-sm-10 col-xs-10 ">
            <?php echo Form::open(['route'=>'frontend.comment.store', 'class'=>'comment-form']); ?>

                <?php echo e(Form::hidden('commentable_id',$post->id, array('class' => 'commentable_id'))); ?>

                <?php echo e(Form::hidden('commentable_type','App\Post', array('class' => 'commentable_type'))); ?>

                <input placeholder="Post a comment" name="content" type="text" class="form-control markdown-content submit-input-enter comment-content">

            <?php echo Form::close(); ?>

        </div>
    </div>
</div> <!-- .comment-main-wrappper -->    