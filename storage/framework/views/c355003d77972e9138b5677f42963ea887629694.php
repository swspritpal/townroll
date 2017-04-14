<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 groupsWrapper" style="overflow-x: auto;">

        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 chatGroups">
            <a href="javascript:void(0);"  class="locate-me-popup"><img src="<?php echo e(asset('img/icons/locateMeNew.png')); ?>" class="imgCircle groupProfile" />
            <div class="groupTitle">Locate me</div></a>
        </div>
        <?php if (! (empty($categories))): ?>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user_category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('frontend.index', ['cat' =>$user_category->id ])); ?>" class="filter-posts <?php echo e(( (app('request')->has('cat') && app('request')->input('cat') == $user_category->id) ? 'place-active':'')); ?>" data-filter-by="cat" data-filter-id="<?php echo e($user_category->id); ?>">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-3 chatGroups">
                        <img src='<?php echo asset("img/goole_places_image/$user_category->place_image_path" ); ?>' class="imgCircle groupProfile"  />
                        <div class="groupTitle"><?php echo $user_category->name; ?></div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>     


            <div style="clear:both"> </div>
        <!-- <div class="viewAllGroups hidden-xs hidden-sm"><span class="pull-right"><a href="javascript:void(0);" class="viewAllGroupLink">View all</a> </span> </div> -->

    </div>

    <div style="clear:both"> </div>