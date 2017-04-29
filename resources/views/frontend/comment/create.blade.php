<div class="clearfix comment-add-new">
    <div class="col-lg-1 col-md-1 col-sm-2 col-xs-2 paddingUnset">
      <img src="{{ $logged_in_user->picture }}" alt="" class="profile-photo-sm">
    </div>
    <div class="col-lg-11 col-md-11 col-sm-10 col-xs-10 ">
        {!! Form::open(['route'=>'frontend.comment.store', 'class'=>'comment-form']) !!}
            {{ Form::hidden('commentable_id',$post->id, array('class' => 'commentable_id')) }}
            {{ Form::hidden('commentable_type','App\Post', array('class' => 'commentable_type')) }}
            <input placeholder="Post a comment" name="content" type="text" class="form-control markdown-content submit-input-enter comment-content">

        {!! Form::close() !!}
    </div>
</div>