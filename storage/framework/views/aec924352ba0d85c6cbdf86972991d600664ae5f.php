<?php $__currentLoopData = $viewed_users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  
  <?php $__currentLoopData = $user->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="row">
        <div class="col-lg-1 col-md-1 col-sm-2 col-xs-2 paddingUnset">
          <img src="<?php echo e($user->picture); ?>" alt="" class="profile-photo-sm">
        </div>
       <div class="col-lg-11 col-md-11 col-sm-10 col-xs-10 ">
          <a href="javascript:void(0);" class="profile-link"><?php echo e($user->username); ?></a>
        </div>
    </div>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


<?php if(!empty($viewed_users['0']->users_count)): ?>

  <?php 
    $more_users=(int) ($viewed_users['0']->users_count - env('DEFAULT_HOME_PAGE_VIEWED_USERS_LIMIT'));
   ?>

  <?php if($more_users > 0): ?>
    See more users <?php echo e($more_users); ?>

  <?php endif; ?>
<?php endif; ?>
