<!DOCTYPE html>
<html lang="<?php echo e(config('app.locale')); ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo $__env->yieldContent('title', app_name()); ?></title>

        <!-- Meta -->
        <meta name="description" content="<?php echo $__env->yieldContent('meta_description', 'Laravel 5 Boilerplate'); ?>">
        <meta name="author" content="<?php echo $__env->yieldContent('meta_author', 'Anthony Rappa'); ?>">
        <?php echo $__env->yieldContent('meta'); ?>

        <!-- Styles -->
        <?php echo $__env->yieldContent('before-styles'); ?>

        <!-- Check if the language is set to RTL, so apply the RTL layouts -->
        <!-- Otherwise apply the normal LTR layouts -->
        <?php if (session()->has('lang-rtl')): ?>
            <?php echo e(Html::style(getRtlCss(mix('css/frontend.css')))); ?>

        <?php else: ?>
            
            <?php echo e(Html::style(asset('css/frontend.7ff6e4f3636e72d7b511.css'))); ?>

        <?php endif; ?>

        <?php echo $__env->yieldContent('after-styles'); ?>

        <!-- Scripts -->
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>
    </head>
    <body id="app-layout">
        <div id="app">
            <?php echo $__env->make('includes.partials.logged-in-as', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php echo $__env->make('frontend.includes.loginHeader', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

            <div class="container-fluid">
                <?php echo $__env->make('includes.partials.messages', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <?php echo $__env->yieldContent('content'); ?>
            </div><!-- container -->
        </div><!--#app-->

        <!-- Scripts -->
        <?php echo $__env->yieldContent('before-scripts'); ?>
        <?php echo Html::script(asset('js/frontend.a681807aa6b858fa3d6c.js')); ?>

        <?php echo $__env->yieldContent('after-scripts'); ?>
          <?php echo e(Html::style(asset('css/style.css'))); ?>

          <?php echo e(Html::style(asset('css/ionicons.css'))); ?> 

        <?php echo $__env->make('includes.partials.ga', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </body>
</html>