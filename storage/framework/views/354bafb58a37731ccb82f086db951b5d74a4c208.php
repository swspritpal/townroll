<?php $__env->startSection('content'); ?>
    <div class="col-md-6 borderRight postContent postContentHome " id="app">
        <?php echo $__env->make('frontend.includes.places', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php echo $__env->make('frontend.includes.posts.create', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <div class="progress home-post-progressbar" style="display: none;">
	    	<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">
	      		
	    	</div>
	  	</div>
        <div class="infinite-scroll append-new-post-content">
        	<?php echo $__env->renderEach('frontend.includes.posts.single',$posts,'post','frontend.includes.posts.empty'); ?>
            
        	<?php echo e($posts
                ->appends($sort_by)
                ->render()); ?> 
        </div>
        
    </div>
  	
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>