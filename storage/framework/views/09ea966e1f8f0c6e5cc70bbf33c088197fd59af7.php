<table class="table table-striped table-hover">
    <tr>
        <th><?php echo e(trans('labels.frontend.user.profile.avatar')); ?></th>
        <td><img src="<?php echo e($logged_in_user->picture); ?>" class="user-profile-image" /></td>
    </tr>
    <tr>
        <th><?php echo e(trans('labels.frontend.user.profile.name')); ?></th>
        <td><?php echo e($logged_in_user->name); ?></td>
    </tr>
    <tr>
        <th><?php echo e(trans('labels.frontend.user.profile.email')); ?></th>
        <td><?php echo e($logged_in_user->email); ?></td>
    </tr>
    <tr>
        <th><?php echo e(trans('labels.frontend.user.profile.created_at')); ?></th>
        <td><?php echo e($logged_in_user->created_at); ?> (<?php echo e($logged_in_user->created_at->diffForHumans()); ?>)</td>
    </tr>
    <tr>
        <th><?php echo e(trans('labels.frontend.user.profile.last_updated')); ?></th>
        <td><?php echo e($logged_in_user->updated_at); ?> (<?php echo e($logged_in_user->updated_at->diffForHumans()); ?>)</td>
    </tr>
</table>