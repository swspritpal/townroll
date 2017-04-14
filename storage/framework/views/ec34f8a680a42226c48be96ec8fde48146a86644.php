<?php $__env->startSection('content'); ?>


    <div class="row">

            <div class="">
               <!--  <div class="panel-heading"><?php echo e(trans('labels.frontend.auth.login_box_title')); ?></div> -->
                	
                	<div class="col-md-8 loginPageLeft hidden-xs hidden-sm">
                		<img src="../img/connect-city.png"/>
                	</div>
                    
                	<div class="col-md-4 loginPageRight" style="border-left:#ccc solid thin">

	                    <div class="row text-center ">

	                    	<div class="LoginSocialLinks">
	                    	<h1> Join with us </h1>
	                    	<p> Login with         	</p>
	                        
	                        <a href="<?php echo url('/login/facebook');; ?>"><img src="../img/LoginWithFb.png"/></a>
	                        <hr class="SepratorLine"><p class="SepratorOR"> or </p>
	                         <a  href="<?php echo url('/login/google');; ?>"><img src="../img/LoginWithGoogle.png"/></a>
	                        </div>
	                    </div>
                    </div>

                </div><!-- panel body -->

            </div><!-- panel -->

    </div><!-- row -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>