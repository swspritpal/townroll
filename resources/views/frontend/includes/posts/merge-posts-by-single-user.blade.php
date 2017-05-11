
<div class="merging-posts-wrapper">
  @foreach($recent_post as $recent_post_item)
    @include('frontend.includes.posts.single',['post'=>$recent_post_item])
  @endforeach
</div>

