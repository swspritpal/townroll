@php
  if($load_all_comment){
    $comment_pagination=$post->comments_with_pagination;
    $post->comments=$comment_pagination->reverse();
  }else{
    $post->comments=$post->lastest_comments->reverse();
  }
  
@endphp

@section('after-scripts')
  {!! Html::script(asset('js/post-view.js')) !!}
  {!! Html::script(asset('js/comments/autosize.min.js')) !!}
  {!! Html::script(asset('js/comments/hightlight.js')) !!}
  {!! Html::script(asset('js/comments/imgLiquid-min.js')) !!}
  {!! Html::script(asset('js/comments/marked.js')) !!}
  {!! Html::script(asset('js/comments/comments.js')) !!}
@endsection

<!-- 'redirect'=>(isset($redirect) && $redirect ? $redirect:'') -->

<div class="comment-main-wrappper">
    <div class="reaction clearfix post-counter-bar">

        <a  href="javascript:void(0);"  class="btn text-grey hoverRed paddingUnset " data-post-id="{{ $post->id }}">
          <i class=" fa  {{ $post->is_liked ? 'fa-heart' : 'fa-heart-o' }} post-like-click"></i>
        </a>
        <a href="javascript:void(0);" data-post-id="{{ $post->id }}" class="btn text-grey hoverRed paddingUnset post-liked-users"> 
            <span class="post-like-counter">{{ !empty($post->like_count()) ? $post->like_count() : "0" }}</span> Likes
        </a>

        @if($load_all_comment)
          <a class="btn text-grey hoverOlive paddingUnset" href="javascript:void(0);" ><i class="fa fa-comment-o"></i> <span class="home-comment-counter">{!! $post->comments_count !!}</span> Comments</a>
        @else
          <a class="btn text-grey hoverOlive " href="javascript:void(0);"  data-action="post-single-popup" data-post="{{ $post->id }}"><i class="fa fa-comment-o"></i> <span class="home-comment-counter">{!! $post->comments_count !!}</span> Comments</a>
        @endif

        <a class="btn text-grey hoverCyan post-view-by-users {{ ($load_all_comment) ? 'paddingUnset':'' }}" href="javascript:void(0);"  data-post-id="{{ $post->id }}">
          <i class="fa fa-eye"></i> {{ !empty($post->view_count()) ? $post->view_count() : "0" }}  Views
        </a>

        <span class="pull-right"> 
        <a class="btn text-grey hoverOrange paddingUnset" title="Boost post" data-toggle="modal" data-target="#postBoot"><i class="fa fa-rocket"></i> Boost</a> 
        <a class="btn text-grey hoverOrange paddingUnset" href="javascript:void(0);"  data-toggle="modal" data-target="#allBoost">(0)</a> </span>
        <div style="clear:both"> </div>
    </div>
    <div class="line-divider"></div>


    <div class="comments-container load-more-comments-post-single"
         data-api-url="{{ route('frontend.comment.show',[$post->id,
         'commentable_type'=>'App\Post',
         'last_comment_id'=>!empty($post->comments->last())? $post->comments->last()->id:0]) }}">
         <p class="comment-pagination-load-more">
             @if(!empty($post->comments_count) && $post->comments_count > env('DEFAULT_HOME_PAGE_POST_COMMENTS') )
                @if($load_all_comment)
                  @if(!empty($comment_pagination->nextPageUrl()))
                    <a href="javascript:void(0);" data-action="load_more_comments" data-uri="{{ $comment_pagination->nextPageUrl() }}"> View more comments </a>
                  @else
                    <!-- when no more comment avaliable to show but in popup -->
                  @endif

                @else
                  <a href="javascript:void(0);" data-action="post-single-popup" data-post="{{ $post->id }}"> View more comments </a>
                @endif
            @endif
          </p>

        @include('frontend.comment.show',[ 'comments' => $post->comments,'post_user_id' =>$post->user->id, 'load_all_comment'=>$load_all_comment ])

    </div>
    @if(empty($load_all_comment))
      @include('frontend.comment.create')
    @endif

</div> <!-- .comment-main-wrappper -->    