<div class="clearfix comment-show-blade">
  <?php if($comments->count() > 0): ?>
    <?php $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

      <p class="comment">
          <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 paddingUnset">
            <img src="<?php echo e($comment->user->picture); ?>" alt="" class="profile-photo-sm">
          </div>
            <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11 comment-info">

              <a href="#" class="profile-link"><?php echo e($comment->user->username); ?> </a>
                <!-- By default 1 comment showing, thats why using ZERO index -->   
                  <?php if(strlen($comment->content) > env('DEFAULT_HOME_PAGE_COMMENT_CONTENT_LENGTH')): ?>                 
                    <?php echo e(str_limit($comment->content,env('DEFAULT_HOME_PAGE_COMMENT_CONTENT_LENGTH',150))); ?>

                    <a href="javascript:void(0);"  data-toggle="modal" data-target="#viewSingleComment"  >Read more </a>';
                  <?php else: ?>
                    <?php echo e($comment->content); ?>

                  <?php endif; ?>

               <div class="comment-operation">
                  <?php if(access()->user()->id == $post_user_id): ?>
                      <a class="comment-operation-item swal-dialog-target"
                         href="javascript:void (0)"
                         data-url="<?php echo e(route('frontend.comment.destroy',$comment->id)); ?>">
                          Delete
                      </a>
                  <?php endif; ?>
                  <p class="replyComment">
                    <a class="comment-operation-reply"
                     title="Reply"
                     href="javascript:void(0);"
                     data-username="<?php echo e($comment->user->username); ?>"><i class="fa fa-reply replyIcon"></i> Reply </a>       
                  </p>
              </div>
             
            </div>
      </p>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  <?php endif; ?>
</div>

