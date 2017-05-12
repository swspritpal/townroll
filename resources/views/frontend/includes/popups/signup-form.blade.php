<?php 

$countriesList=getCountiesList();

if(!is_username_unique(clean_username(Auth::user()->name))){
  $username_suggest=clean_username(Auth::user()->name);
}else{
  $username_suggest=clean_username(Auth::user()->name);
  $username_suggest_add_random=$username_suggest=clean_username(Auth::user()->name).mt_rand(10,22);
  if(!is_username_unique($username_suggest_add_random)){
    $username_suggest=$username_suggest_add_random;
  }else{
    $username_suggest="";  
  }
}

?>

<!-- Sign up form Modal -->
@if(empty(Auth::user()->username) || empty(Auth::user()->city_id))

  {{ Form::hidden('signup_form_fields','true', array('id' => 'need_signup')) }}

  {{ Form::hidden('signup_default_state','', array('id' => 'signup_default_state')) }}
  {{ Form::hidden('signup_default_city','', array('id' => 'signup_default_city')) }}

  <div class="modal fade" id="signup_form_modal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
          <h5 class="modal-title" ><i class="ion-compose myIcons" ></i>Fil this out</h5>
          
        </div>
        <div class="modal-body">
          {!! Form::open(['class'=>'form-horizontal signup-form','id'=>'signup_form']) !!}

                <div class=" {{ $errors->has('username') ? ' has-error' : '' }}">

                  {{ Form::label('username', 'Username', array('class' => 'mylabel')) }}
                  {{ Form::text('username', $username_suggest, array('class' => 'field form-control convert_space_into_underscore')) }}

                  @if ($errors->has('username'))
                      <span class="help-block">
                          <strong>{{ $errors->first('username') }}</strong>
                      </span>
                  @endif
                </div>

                <div class="">
                  {{ Form::label('country', 'Country', array('class' => 'mylabel')) }}

                  {{ Form::select('country',$countriesList,
                        null,
                      ['class' => 'form-control','id'=>'signup_form_country']
                    ) 
                  }}
                </div>
                <div class="">
                  {{ Form::label('state', 'State', array('class' => 'mylabel')) }}
                  {{ Form::select('state', [
                       '0' => 'Select State'
                       ],
                        null,
                      ['class' => 'form-control','id'=>'signup_form_state']
                    ) 
                  }}
                </div>
                <div class="">
                  {{ Form::label('city', 'City', array('class' => 'mylabel')) }}
                  {{ Form::select('city', [
                       '0' => 'Select city'],
                        null,
                      ['class' => 'form-control','id'=>'signup_form_city']
                    ) 
                  }}
                </div>          
        </div>
        <div class="modal-footer">
          <!-- <button type="button" class="btn btn-default pull right" data-dismiss="modal">Post</button> -->
          {{ Form::button('Save', array('class' => 'btn btn-primary pull right','id'=>'save_signup')) }}
        </div>

        {!! Form::close() !!}

      </div>
    </div>
  </div>
@endif


 <!-- #signup_form -->
   