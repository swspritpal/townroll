<!-- Add post Modal -->
@php
  $user_categories=[];
@endphp

@section('after-scripts')
  {!! Html::script(asset('js/jcrop/Jcrop.min.js')) !!}
  {!! Html::script(asset('js/vendor/select2.min.js')) !!}

  {!! Html::script(asset('js/post.js')) !!}
@append


@unless(empty($categories))

  @php
    $user_categories['all']='Post this to all places';
    $home_city_place='';
  @endphp

  @foreach($categories as $category)
    
    @php
      if($category->city_id == $logged_in_user->city_id){
        $home_city_place=$category->id;
      }
      $user_categories[$category->id] =  $category->name;
    @endphp

  @endforeach
@endunless

{!! Form::open(['class'=>'','id'=>'new_post_form','enctype' => 'multipart/form-data']) !!}

<div class="modal fade" id="add_post" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content model-contentHeight">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h5 class="modal-title"><i class="ion-compose myIcons" ></i> Create a post  |  <a href="javascript:void(0);" class="post-upload-image-link"><i class="ion-image myIcons"></i> Photo/Album</a></h5>
      </div>

      <div class="modal-body popupHeightAdd ">
        <span class="post-content-error error" style="display: none;"></span>

        {{ Form::file('post_image', ['class' => 'hidden post_image_file_input','accept'=>"image/*" ]) }}

        {{ Form::hidden('is_post_have_image','no', array('id' => 'is_post_have_image')) }}
        {{ Form::hidden('is_crop_post_image','no', array('id' => 'is_crop_post_image')) }}

        {{ Form::hidden('image_x','', array('id' => 'image_x')) }}
        {{ Form::hidden('image_y','', array('id' => 'image_y')) }}
        {{ Form::hidden('image_w','', array('id' => 'image_w')) }}
        {{ Form::hidden('image_h','', array('id' => 'image_h')) }}


        <div class="row show-on-upload hidden">

          <div class="post-image-control">
            <!-- <a href="javascript:void(0);"><i class="ion-crop icon myIcons crop-tool-icon"></i></a> -->
            <a href="javascript:void(0);"><i class="icon myIcons" id="unhook" style="display:none;">Destroy Crop</i></a>

            <a href="javascript:void(0);"><i class="icon  ion-ios-cloud-upload myIcons" id="upload_crop_image" style="display:none;">Crop It</i></a>
            <a href="javascript:void(0);"><i class="ion-android-delete icon myIcons delete-post-image-icon pull-right"></i></a>
          </div>

          <div class="post-upload-image-wrapper">
            <img src="" style="max-width:100%;" class="post-upload-image-preview jcrop-init-target">
          </div>

        </div>

        <div class="form-group">
          <textarea  name="post_content" cols="60" rows="2" class="form-control suggest-user-list"  id="post_content" placeholder="Write here some thing..." autofocus></textarea>       
        </div>


        <div class="form-group">
          {{ Form::label('categories', 'Who can see this ?', array('class' => 'mylabel')) }}

          {{ Form::select('categories[]',$user_categories,
                $home_city_place,
              ['class' => 'form-control form-select col-md-12','id'=>'post_category_drop_down','multiple'=>'multiple','style'=>'width:100%;']
            ) 
          }}
          <span class="post-categories-error error" style="display: none;"></span>
        </div>
        


        <!-- Jcrop Option HTML for Furture Scope -->

        <!-- <div style="margin: .8em 0 .5em;" class="">
          <span class="requiresjcrop">
            <button id="setSelect" class="btn btn-mini">setSelect</button>
            <button id="animateTo" class="btn btn-mini">animateTo</button>
            <button id="release" class="btn btn-mini">Release</button>
            <button id="disable" class="btn btn-mini">Disable</button>
          </span>
          <button id="enable" class="btn btn-mini" style="display:none;">Re-Enable</button>
          <button id="unhook" class="btn btn-mini">Destroy!</button>
          <button id="rehook" class="btn btn-mini" style="display:none;">Attach Jcrop</button>
        </div>

        <fieldset class="optdual requiresjcrop">
          <legend>Option Toggles</legend>
          <div class="optlist offset">
            <label><input type="checkbox" id="ar_lock" />Aspect ratio</label>
            <label><input type="checkbox" id="size_lock" />minSize/maxSize setting</label>
          </div>
          <div class="optlist">
            <label><input type="checkbox" id="can_click" />Allow new selections</label>
            <label><input type="checkbox" id="can_move" />Selection can be moved</label>
            <label><input type="checkbox" id="can_size" />Resizable selection</label>
          </div>
        </fieldset> -->


      </div>

      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-warning pull-left" data-dismiss="modal">Close</button> -->
        <button type="button" class="btn btn-primary pull right" id="save_new_post">Post</button>
      </div>

    </div>
  </div>
</div>

{!! Form::close() !!}

<style>
.post-content-error, .post-categories-error{
  color:red;
}
</style>

