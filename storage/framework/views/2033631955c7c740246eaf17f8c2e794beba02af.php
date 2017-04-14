<?php echo e(Form::model($logged_in_user, ['route' => 'frontend.user.profile.update', 'class' => 'form-horizontal', 'method' => 'PATCH'])); ?>


    <div class="form-group">
        <?php echo e(Form::label('name', trans('validation.attributes.frontend.name'), ['class' => 'col-md-4 control-label'])); ?>

        <div class="col-md-6">
            <?php echo e(Form::input('text', 'name', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.frontend.name')])); ?>

        </div>
    </div>

    <?php if($logged_in_user->canChangeEmail()): ?>
        <div class="form-group">
            <?php echo e(Form::label('email', trans('validation.attributes.frontend.email'), ['class' => 'col-md-4 control-label'])); ?>

            <div class="col-md-6">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> <?php echo e(trans('strings.frontend.user.change_email_notice')); ?>

                </div>

                <?php echo e(Form::input('email', 'email', null, ['class' => 'form-control', 'placeholder' => trans('validation.attributes.frontend.email')])); ?>

            </div>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <?php echo e(Form::submit(trans('labels.general.buttons.update'), ['class' => 'btn btn-primary', 'id' => 'update-profile'])); ?>

        </div>
    </div>

<?php echo e(Form::close()); ?>