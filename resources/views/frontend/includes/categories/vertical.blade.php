<a href="{{ route('frontend.index', ['cat' =>$user_category->id ]) }}" class="filter-posts slick-slide slick-active filter-posts {{ ( (app('request')->has('cat') && app('request')->input('cat') == $user_category->id) ? 'place-active':'') }}" data-filter-by="cat" data-filter-id="{{ $user_category->id }}" data-slick-index="{{ (isset($slick_last_index)?$slick_last_index:'') }}"  aria-hidden="false" tabindex="-1" role="option" aria-describedby="slick-slide{{ (isset($slick_last_index)?$slick_last_index:'') }}">
    <div class="chatGroups">
    
        <img src="{{ asset(env('PLACE_IMAGES_FOLDER').$user_category->place_image_path) }}" class="imgCircle groupProfile"  />
        <div class="groupTitleMain">{!! $user_category->name !!}</div>
    </div>
</a>