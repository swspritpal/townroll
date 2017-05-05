<div class="clearfix comment-show-blade {{ (!empty($comment_pagination)? 'comment-infinite-scroll': '') }}">
  @if($comments->count() > 0)
    @foreach($comments as $comment)

      <article class="comment clearfix comment-wrapper">
          <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 paddingUnset">
            @if(isset($load_all_comment) && ($load_all_comment))
              <a href="{{ route('frontend.auth.user.profile',$comment->user->username) }}" >
                <img src="{{ $comment->user->picture }}" alt="" class="profile-photo-sm">
              </a>
            @else
              <a href="javascript:void(0);" data-action="user-profile" data-user-id="{{ $comment->user_id }}">
                <img src="{{ $comment->user->picture }}" alt="" class="profile-photo-sm">
              </a>
            @endif
          </div>
            <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11 comment-info">
              @if(isset($load_all_comment) && ($load_all_comment))
                <a href="{{ route('frontend.auth.user.profile',$comment->user->username) }}" >{{ $comment->user->username }} </a>
              @else
                <a href="javascript:void(0);" data-action="user-profile" data-user-id="{{ $comment->user_id }}">{{ $comment->user->username }} </a>
              @endif
                  <div class="commentLimit">
                    {!! $comment->html_content !!}                
                  </div>

               <div class="comment-operation">
                  @if(access()->user()->id == $post_user_id)
                      <a class="comment-operation-item swal-dialog-target"
                         href="javascript:void (0)"
                         data-url="{{ route('frontend.comment.destroy',$comment->id) }}" data-enable-ajax="1" data-operation-on="comment">
                          Delete
                          <form action="{{ route('frontend.comment.destroy',$comment->id) }}" method='post' style='display:none'>
                            <input type='hidden' name='_method' value="delete">
                            {!! csrf_field() !!}
                          </form>
                      </a>
                  @endif
                  <span class="replyComment">
                    <a class="comment-operation-reply"
                     title="Reply"
                     href="javascript:void(0);"
                     data-username="{{ $comment->user->username }}"><i class="fa fa-reply replyIcon"></i> Reply </a>       
                  </span>
              </div>
             
            </div>
        <span class="clearfix"></span>
      </article>
    @endforeach
  @endif
</div>

