
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 groupsWrapper add-new-location-in-header" style="overflow-x: auto;">
    <div class="scrollerDivSlick" style="visibility: hidden;">
        <a href="javascript:void(0);"  class="locate-me-popup">
            <div class="chatGroups">
                <img src="{{ asset('img/icons/locateMeNew.png') }}" class="imgCircle groupProfile" />                
                <div class="groupTitleMain">Locate me</div>
            </div>
        </a>

        @unless(empty($categories))
            @foreach($categories as $user_category)
                @include('frontend.includes.categories.vertical',compact('user_category'))             
            @endforeach
        @endunless     

            <div style="clear:both"> </div>
        <!-- <div class="viewAllGroups hidden-xs hidden-sm"><span class="pull-right"><a href="javascript:void(0);" class="viewAllGroupLink">View all</a> </span> </div> -->
    </div>
    
</div>
<div style="clear:both"> </div>