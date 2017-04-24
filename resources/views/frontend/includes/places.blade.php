
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 groupsWrapper" style="overflow-x: auto;">
    <div class="scrollerDivSlick">
        <a href="javascript:void(0);"  class="locate-me-popup">
            <div class="  chatGroups">
                <img src="{{ asset('img/icons/locateMeNew.png') }}" class="imgCircle groupProfile" />                
                <div class="groupTitleMain">Locate me</div>
            </div>
        </a>

        @unless(empty($categories))
            @foreach($categories as $user_category)

                <a href="{{ route('frontend.index', ['cat' =>$user_category->id ]) }}" class="filter-posts {{ ( (app('request')->has('cat') && app('request')->input('cat') == $user_category->id) ? 'place-active':'') }}" data-filter-by="cat" data-filter-id="{{ $user_category->id }}">
                    <div class="chatGroups ">
                        <img src='{!! asset("img/goole_places_image/$user_category->place_image_path" ) !!}' class="imgCircle groupProfile"  />
                        <div class="groupTitleMain">{!! $user_category->name !!}</div>
                    </div>
                </a>
            @endforeach
        @endunless     

            <div style="clear:both"> </div>
        <!-- <div class="viewAllGroups hidden-xs hidden-sm"><span class="pull-right"><a href="javascript:void(0);" class="viewAllGroupLink">View all</a> </span> </div> -->
    </div>
    
</div>
<div style="clear:both"> </div>