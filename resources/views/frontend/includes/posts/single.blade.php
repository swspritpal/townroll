{{ $post->onPostShowing($post)}}
<!-- Post Content
================================================= -->
  <div class="post-content content-item-wrapper" data-post-id="{{ $post->id }}" data-auto-refresh="false">
    <div class="post-container">        
      <div class="post-detail">
         <a href="javascript:void(0);" class="profile-link" data-action="user-profile" data-user-id="{{ $post->user_id }}">
          <img src="{{ $post->user->picture }}" alt="user" class="profile-photo-md pull-left">
        </a>
        <div class="user-info">
          <a href="javascript:void(0);" class="profile-link" data-action="user-profile" data-user-id="{{ $post->user_id }}">{{ $post->user->username }}</a>
          <span class="GroupInfo">published a post in 

              @unless(empty($post->categories))
                @foreach($post->categories as $post_category)
                  <a href="{{ route('frontend.index','cat='."$post_category->id") }}">{{ $post_category->name }}</a>,
                @endforeach
              @endunless
               
          </span> 
          <p class="text-muted"> {{ $post->created_at }}</p>

          <aside class="delete-single-post">
            @if(access()->user()->id == $post->user_id)
                <a class="post-delete-operation swal-dialog-target"
                   href="javascript:void (0)"
                   data-url="{{ route('frontend.post.destroy',$post->id) }}" data-enable-ajax="1" data-operation-on="post">
                    Delete
                    <form action="{{ route('frontend.post.destroy',$post->id) }}" method='post' style='display:none'>
                      <input type='hidden' name='_method' value="delete">
                      {!! csrf_field() !!}
                    </form>
                </a>
            @endif
          </aside>

          <div class="reportPost">
            <a href="javascript:void(0);"   data-toggle="modal" data-target="#reportPost">
              <span class="reportPostIcon"><i class="fa fa-exclamation-triangle" aria-hidden="true" ></i> Report post</span>
              
            </a> 
          </div>
        </div>
          @unless(empty($post->image_path))
            <img src="{{ asset(env('POST_IMAGES_FOLDER').$post->image_path) }}" alt="post-image" class="img-responsive post-image" href="javascript:void(0);"   data-toggle="modal" data-target="#ViewFullPost">
          @endunless

        
        <div class="line-divider"></div>
        <div class="post-text clearfix">
          @unless(empty($post->content))
            <p class="postTextLimit">
              @if(strlen($post->content) > 350)
                {{ str_limit($post->content,env('DEFAULT_HOME_PAGE_POST_CONTENT_LENGTH',350)) }}
                 ...<a href="javascript:void(0);"  data-toggle="modal" data-target="#viewPost"  >Read more </a>
                
              @else
                {{ $post->content }}

              @endif
              <i class="em em-anguished"></i> <i class="em em-anguished"></i> <i class="em em-anguished"></i>
            </p>
          @endunless
 
        </div>
        @include('frontend.widget.comment',['load_all_comment'=>false])
    </div>
  </div>
</div>


<!-- Post Content
================================================= -->
