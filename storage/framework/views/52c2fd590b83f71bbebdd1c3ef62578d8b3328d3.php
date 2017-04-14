<?php echo e($post->onPostShowing($post)); ?>


<?php echo $__env->make('frontend.includes.popups.post-view', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php echo $__env->make('frontend.includes.popups.post-liked', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<!-- Post Content
================================================= -->
  <div class="post-content">
    <div class="post-container">
        <img src="<?php echo e($post->user->picture); ?>" alt="user" class="profile-photo-md pull-left">
        
      <div class="post-detail">
        <div class="user-info">
          <a href="javascript:void(0);" data-toggle="modal" data-target="#myProfile" class="profile-link"><?php echo e($post->user->username); ?></a> 
          <span class="GroupInfo">Published a post in 

              <?php if (! (empty($post->categories))): ?>
                <?php $__currentLoopData = $post->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post_category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <a href="javascript:void(0)"><?php echo e($post_category->name); ?></a>,
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              <?php endif; ?>
               
          </span> 
          <p class="text-muted"> <?php echo e($post->created_at); ?></p>
          <div class="reportPost">
            <a href="javascript:void(0);"   data-toggle="modal" data-target="#reportPost">
              <span class="reportPostIcon"><i class="fa fa-exclamation-triangle" aria-hidden="true" ></i> Report post</span>
              
            </a> 
          </div>
        </div>
          <?php if (! (empty($post->image_path))): ?>
            <img src="<?php echo e(asset(env('POST_IMAGES_FOLDER').$post->image_path)); ?>" alt="post-image" class="img-responsive post-image">
          <?php endif; ?>

        
        <div class="line-divider"></div>
        <div class="post-text clearfix">
          <?php if (! (empty($post->content))): ?>
            <p class="postTextLimit">
              <?php if(strlen($post->content) > 350): ?>
                <?php echo e(str_limit($post->content,env('DEFAULT_HOME_PAGE_POST_CONTENT_LENGTH',350))); ?>

                 ...<a href="javascript:void(0);"  data-toggle="modal" data-target="#viewPost"  >Read more </a>
                
              <?php else: ?>
                <?php echo e($post->content); ?>


              <?php endif; ?>
              <i class="em em-anguished"></i> <i class="em em-anguished"></i> <i class="em em-anguished"></i>
            </p>
          <?php endif; ?>
 
        </div>
        <?php echo $__env->make('frontend.widget.comment', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>
  </div>
</div>


<!-- Post Content
================================================= -->
