<?php 

$countriesList=getCountiesList();

if(is_username_unique(clean_username(Auth::user()->name))){
  $username_suggest=clean_username(Auth::user()->name);
}else{
  $username_suggest="";
}

?>

<!-- Sign up form Modal -->
<?php if(empty(Auth::user()->username) || empty(Auth::user()->city_id)): ?>

  <?php echo e(Form::hidden('signup_form_fields','true', array('id' => 'need_signup'))); ?>


  <?php echo e(Form::hidden('signup_default_state','', array('id' => 'signup_default_state'))); ?>

  <?php echo e(Form::hidden('signup_default_city','', array('id' => 'signup_default_city'))); ?>


  <div class="modal fade" id="signup_form_modal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
          <h5 class="modal-title" ><i class="ion-compose myIcons" ></i>Fil this out</h5>
          
        </div>
        <div class="modal-body">
          <?php echo Form::open(['class'=>'form-horizontal signup-form','id'=>'signup_form']); ?>


                <div class=" <?php echo e($errors->has('username') ? ' has-error' : ''); ?>">

                  <?php echo e(Form::label('username', 'Username', array('class' => 'mylabel'))); ?>

                  <?php echo e(Form::text('username', $username_suggest, array('class' => 'field form-control convert_space_into_underscore'))); ?>


                  <?php if($errors->has('username')): ?>
                      <span class="help-block">
                          <strong><?php echo e($errors->first('username')); ?></strong>
                      </span>
                  <?php endif; ?>
                </div>

                <div class="">
                  <?php echo e(Form::label('country', 'Country', array('class' => 'mylabel'))); ?>


                  <?php echo e(Form::select('country',$countriesList,
                        null,
                      ['class' => 'form-control','id'=>'signup_form_country']
                    )); ?>

                </div>
                <div class="">
                  <?php echo e(Form::label('state', 'State', array('class' => 'mylabel'))); ?>

                  <?php echo e(Form::select('state', [
                       '0' => 'Select State'
                       ],
                        null,
                      ['class' => 'form-control','id'=>'signup_form_state']
                    )); ?>

                </div>
                <div class="">
                  <?php echo e(Form::label('city', 'City', array('class' => 'mylabel'))); ?>

                  <?php echo e(Form::select('city', [
                       '0' => 'Select city'],
                        null,
                      ['class' => 'form-control','id'=>'signup_form_city']
                    )); ?>

                </div>          
        </div>
        <div class="modal-footer">
          <!-- <button type="button" class="btn btn-default pull right" data-dismiss="modal">Post</button> -->
          <?php echo e(Form::button('Save', array('class' => 'btn btn-primary pull right','id'=>'save_signup'))); ?>

        </div>

        <?php echo Form::close(); ?>


      </div>
    </div>
  </div>
<?php endif; ?>


 <!-- #signup_form -->
   