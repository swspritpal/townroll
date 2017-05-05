<div class="col-lg-12 paddingUnset">
  @if(!empty($post))

    @unless(empty($post->image_path))
      <div class="col-lg-7 paddingUnset ViewFullPostLeft" >
        <div class="ViewFullPostIMAGE" >
           <img src="{{ asset(env('POST_IMAGES_FOLDER').$post->image_path) }}" alt="post-image" class="img-responsive post-image">
        </div>
      </div>
    @endunless

    <div class="col-lg-{{ empty($post->image_path) ? '12': '5'}} ViewFullPostRight comment-main-wrappper">
        <div class="commentScroller ">
          <div class="modal-body clearfix">
            <a href="{{ route('frontend.auth.user.profile',$post->user->username) }}" >
              <img src="{{ $post->user->picture }}" alt="user" class="profile-photo-sm">
            </a>
            
            <a href="{{ route('frontend.auth.user.profile',$post->user->username) }}" >{{  $post->user->username }}</a>
            <span class="GroupInfo">published a post in 

              @unless(empty($post->categories))
                @foreach($post->categories as $post_category)
                  <a href="{{ route('frontend.index','cat='."$post_category->id") }}">{{ $post_category->name }}</a>,
                @endforeach
              @endunless
               
          </span>

          <p class="text-muted"> {{ $post->created_at }}</p>
            <div class="line-divider"></div>
              @unless(empty($post->content))
                <div class="post-text">
                    <p>
                      {{ $post->content }} 
                    </p>
                </div>
              @endunless
          </div>
          @include('frontend.widget.comment',['load_all_comment'=>true])
        @else
          <div class="col-lg-12">
            <section class="row">
              <h2>Post not found.</h2>
            </section>
          </div>
        @endif
      </div>

      <div class="clearfix " style="padding-top: 10px;">
        @include('frontend.comment.create')
      </div>
    </div>
</div>