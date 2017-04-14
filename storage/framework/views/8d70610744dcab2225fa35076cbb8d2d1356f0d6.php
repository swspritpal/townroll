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

            <?php echo Html::style('//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'); ?>

            <?php echo Html::style('//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css'); ?>

        
            <?php echo Html::style(asset('css/vendor/select2.min.css')); ?>

            <?php echo Html::style(asset('css/jcrop/jcrop.min.css')); ?>  
        <?php echo $__env->yieldContent('after-styles'); ?>

         <?php echo e(Html::style(asset('css/style.css'))); ?>

         <?php echo e(Html::style(asset('css/ionicons.css'))); ?>


        <!-- Scripts -->
        <script>
            var APP_URL = <?php echo json_encode(url('/')); ?>

            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
                'APP_URL' =>  route('frontend.index'),
            ]); ?>
        </script>
   

    </head>
    <body >
        <?php echo $__env->make('frontend.includes.nav', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php echo $__env->make('frontend.includes.popups', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php echo $__env->make('frontend.includes.popups.signup-form', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php echo $__env->make('frontend.includes.popups.locate-me', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php echo $__env->make('frontend.includes.popups.post-add', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <div id="page-contents">
            <div class="container-fluid" >
                <?php echo $__env->make('includes.partials.logged-in-as', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                <div class="row paddingUnsetMobile">
                    <?php echo $__env->make('frontend.includes.left', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    
                    <?php echo $__env->yieldContent('content'); ?>
                    <?php echo $__env->make('frontend.includes.right', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </div><!-- container -->
            </div>
        </div>

        <!-- Scripts -->
        <?php echo $__env->yieldContent('before-scripts'); ?>
            <?php echo Html::script(asset('js/frontend.a681807aa6b858fa3d6c.js')); ?>



            <?php echo Html::script(asset('js/jquery_002.js')); ?>

            <?php echo Html::script(asset('js/script.js')); ?>

            <?php echo Html::script(asset('js/jquery.jscroll.min.js')); ?>

            <?php echo Html::script(asset('js/jq-ajax-progress.min.js')); ?>


            <?php echo Html::script(asset('js/jquery.validate.min.js')); ?>

            <?php echo Html::script(asset('js/signup_form.js')); ?>

        <?php echo $__env->yieldContent('after-scripts'); ?>

        <?php echo $__env->make('includes.partials.ga', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        
        <?php echo Html::script(asset('js/custom.js')); ?>

 
    </body>
</html>