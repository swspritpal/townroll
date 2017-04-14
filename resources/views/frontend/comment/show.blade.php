<div class="clearfix comment-show-blade">
  @if($comments->count() > 0)
    @foreach($comments as $comment)

      <p class="comment">
          <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 paddingUnset">
            <img src="{{ $comment->user->picture }}" alt="" class="profile-photo-sm">
          </div>
            <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11 comment-info">

              <a href="#" class="profile-link">{{ $comment->user->username }} </a>
                <!-- By default 1 comment showing, thats why using ZERO index -->   
                  @if(strlen($comment->content) > env('DEFAULT_HOME_PAGE_COMMENT_CONTENT_LENGTH'))                 
                    {{ str_limit($comment->content,env('DEFAULT_HOME_PAGE_COMMENT_CONTENT_LENGTH',150)) }}
                    <a href="javascript:void(0);"  data-toggle="modal" data-target="#viewSingleComment"  >Read more </a>';
                  @else
                    {{ $comment->content }}
                  @endif

               <div class="comment-operation">
                  @if(access()->user()->id == $post_user_id)
                      <a class="comment-operation-item swal-dialog-target"
                         href="javascript:void (0)"
                         data-url="{{ route('frontend.comment.destroy',$comment->id) }}">
                          Delete
                      </a>
                  @endif
                  <p class="replyComment">
                    <a class="comment-operation-reply"
                     title="Reply"
                     href="javascript:void(0);"
                     data-username="{{ $comment->user->username }}"><i class="fa fa-reply replyIcon"></i> Reply </a>       
                  </p>
              </div>
             
            </div>
      </p>
    @endforeach
  @endif
</div>

