<article>
      <a href="javascript:void(0);" class="profile-link" data-action="user-profile" data-user-id="{{ $post->user_id }}">
          <img src="{{ $post->user->picture }}" alt="user" class="profile-photo-md pull-left">
        </a>
        <div class="user-info">
        @php
          $post->boosts=$post->boosts->reverse();
          $last_boost_time=$post->boosts->first();
        @endphp

          @foreach($post->boosts->unique('user_id') as $boosts)
              @unless(empty($boosts->user))
                  <a href="javascript:void(0);" class="profile-link" data-action="user-profile" data-user-id="{{ $boosts->user->id }}">{{ $boosts->user->username }}</a>

                    @if($post->boosts->last() != $boosts)
                      ,
                    @endif
              @endunless
          @endforeach

          <span class="GroupInfo">boost this post in             
               @unless(empty(boost_categories_of_post($post->id)))
                 @foreach(boost_categories_of_post($post->id) as $boost_categories)
                     <a href="{{ route('frontend.index','cat='.$boost_categories->category->id) }}">{{ $boost_categories->category->name }}</a>,
                 @endforeach
               @endunless
          </span>
          <p class="text-muted"> {{ show_time($last_boost_time['created_at']) }}</p>

   </article>