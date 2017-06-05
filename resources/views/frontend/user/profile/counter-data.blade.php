<ul class= "nav-news-feed profile-menu">
    <li><i class="icon ion-ios-paper"></i>
    <div class="LeftMenuLi">
      <a href="#" class="LeftSidebarCounter">
      <span class="CounterFontStyle pull-left"> Posts </span> 
      <span class="pull-right">{{ $user_post_count }} </span></a>
      <div style="clear:both"> </div>
    </div>
    </li>
    <li><i class="icon ion-ios-people"></i>

    <div class="LeftMenuLi">
      <a href="#" class="LeftSidebarCounter ">
      <span class="CounterFontStyle pull-left">  Boost Posts </span>
      <span class="pull-right">{{ $total_boost_posts }} </span></a>
      <div style="clear:both"> </div>
    </div>
    </li>
    <li><i class="icon ion-ios-people-outline"></i>

    <div class="LeftMenuLi">
        <a href="#" class="LeftSidebarCounter">
        <span class="CounterFontStyle pull-left">  Groups </span>
        <span class="pull-right">{{ $user_place_count }} </span></a>
        <div style="clear:both"> </div>
    </div>
    </li>

    <li><i class="fa fa-eye"></i>

    <div class="LeftMenuLi">
      <a href="#" class="LeftSidebarCounter">
      <span class="CounterFontStyle pull-left">  Viewer's </span>
      <span class="pull-right">{{ $total_places_users }} </span></a>
      <div style="clear:both"> </div>
    </div>
    </li>
</ul>