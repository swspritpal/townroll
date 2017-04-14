<nav class="navbar navbar-default menu">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#frontend-navbar-collapse">
                <span class="sr-only"><?php echo e(trans('labels.general.toggle_navigation')); ?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            
            <img src="../img/logo.png" class="MainLogo"/>
        </div><!--navbar-header-->

        <div class="collapse navbar-collapse " id="frontend-navbar-collapse">
            <!-- <ul class="nav navbar-nav">
                <li><?php echo e(link_to_route('frontend.macros', trans('navs.frontend.macros'))); ?></li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <?php if(config('locale.status') && count(config('locale.languages')) > 1): ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <?php echo e(trans('menus.language-picker.language')); ?>

                            <span class="caret"></span>
                        </a>

                        <?php echo $__env->make('includes.partials.lang', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    </li>
                <?php endif; ?>

                <?php if($logged_in_user): ?>
                    <li><?php echo e(link_to_route('frontend.user.dashboard', trans('navs.frontend.dashboard'))); ?></li>
                <?php endif; ?>

                <?php if(! $logged_in_user): ?>
                    <li><?php echo e(link_to_route('frontend.auth.login', trans('navs.frontend.login'))); ?></li>

                    <?php if(config('access.users.registration')): ?>
                        <li><?php echo e(link_to_route('frontend.auth.register', trans('navs.frontend.register'))); ?></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <?php echo e($logged_in_user->name); ?> <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <?php if (access()->allow('view-backend')): ?>
                                <li><?php echo e(link_to_route('admin.dashboard', trans('navs.frontend.user.administration'))); ?></li>
                            <?php endif; ?>

                            <li><?php echo e(link_to_route('frontend.user.account', trans('navs.frontend.user.account'))); ?></li>
                            <li><?php echo e(link_to_route('frontend.auth.logout', trans('navs.general.logout'))); ?></li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul> -->
        </div><!--navbar-collapse-->
    </div><!--container-->
</nav>