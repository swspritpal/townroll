<div class="clearfix comment-show-blade {{ (!empty($comment_pagination)? 'comment-infinite-scroll': '') }}">
  @if($comments->count() > 0)
    @foreach($comments as $comment)

      <p class="comment clearfix">
          <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 paddingUnset">
            <a href="javascript:void(0);" data-action="user-profile" data-user-id="{{ $comment->user_id }}">
              <img src="{{ $comment->user->picture }}" alt="" class="profile-photo-sm">
            </a>
          </div>
            <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11 comment-info">

              <a href="javascript:void(0);" data-action="user-profile" data-user-id="{{ $comment->user_id }}">{{ $comment->user->username }} </a>
                  <div class="commentLimit">
                    @if(strlen($comment->html_content) > env('DEFAULT_HOME_PAGE_COMMENT_CONTENT_LENGTH'))                 
                      {!! str_limit($comment->html_content,env('DEFAULT_HOME_PAGE_COMMENT_CONTENT_LENGTH',150)) !!}
                      <a href="javascript:void(0);"  data-toggle="modal" data-target="#viewSingleComment"  >Read more </a>';
                    @else
                      {!! $comment->html_content !!}
                    @endif
                  </div>


               <div class="comment-operation">
                  @if(access()->user()->id == $post_user_id)
                      <a class="comment-operation-item swal-dialog-target"
                         href="javascript:void (0)"
                         data-url="{{ route('frontend.comment.destroy',$comment->id) }}" data-enable-ajax="1" data-request-data="">
                          Delete
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
      </p>
    @endforeach

    @unless(empty($comment_pagination))
        <div class="comment-pagination">
          {{ $comment_pagination->render()  }}
        </div>
    @endunless

  @endif
</div>

