<div class="col-md-2 static hidden-xs hidden-sm">
<div id="sticky-sidebar-left">
            <div class="LeftSidebarProfile">
                <a href="{{ route('frontend.auth.user.profile',$logged_in_user->username) }}" >
                  <img src="{{ $logged_in_user->picture }}" alt="user" class="profile-photo"> 
                </a>
                <h5><a href="{{ route('frontend.auth.user.profile',$logged_in_user->username) }}">{{ $logged_in_user->name }}</a></h5>
                <a href="{{ route('frontend.index','cat='."$logged_in_user->city_id") }}"><i class="fa fa-map-marker" aria-hidden="true"></i> {{ get_city_name($logged_in_user->city_id) }}</a>
            </div><!--profile card ends-->
            <ul class="nav-news-feed HideLeftMeuIcons">
              <li>
                <i class="icon ion-ios-paper"></i>
                  <div class="LeftMenuLi">
                  <a href="#" class="LeftSidebarCounter">
                  <span class="CounterFontStyle pull-left"> Posts </span> 
                  <span class="pull-right">{{ $user_post_count }} </span></a>
                    <div style="clear:both"> </div>
                  </div>
              </li>
              <li>
                <i class="icon ion-ios-people"></i>
                  <div class="LeftMenuLi">
                  <a href="#" class="LeftSidebarCounter ">
                  <span class="CounterFontStyle pull-left">  Boost Posts </span>
                  <span class="pull-right">0 </span></a>
                    <div style="clear:both"> </div>
                  </div>
              </li>
              <li>
                <i class="icon ion-ios-people-outline"></i>
                  <div class="LeftMenuLi">
                  <a href="#" class="LeftSidebarCounter">
                  <span class="CounterFontStyle pull-left">  Groups </span>
                  <span class="pull-right">{{ $user_place_count }} </span></a>
                    <div style="clear:both"> </div>
                  </div>
              </li>

               <li>
                <i class="fa fa-eye"></i>
                  <div class="LeftMenuLi">
                  <a href="#" class="LeftSidebarCounter">
                  <span class="CounterFontStyle pull-left">  Viewer's </span>
                  <span class="pull-right">0 </span></a>
                    <div style="clear:both"> </div>
                  </div>
              </li>
              
            </ul><!--news-feed links ends-->
            <!-- <div id="chat-block" style="">
              <div class="title">Chat online</div>
              <ul class="online-users list-inline">
                <li><a href="#" title="Linda Lohan"><img src="images/user-2.jpg" alt="user" class="img-responsive profile-photo"><span class="online-dot"></span></a></li>
                <li><a href="#" title="Sophia Lee"><img src="images/user-3.jpg" alt="user" class="img-responsive profile-photo"><span class="online-dot"></span></a></li>
                <li><a href="#" title="John Doe"><img src="images/user-4.jpg" alt="user" class="img-responsive profile-photo"><span class="online-dot"></span></a></li>
                <li><a href="#" title="Alexis Clark"><img src="images/user-5.jpg" alt="user" class="img-responsive profile-photo"><span class="online-dot"></span></a></li>
                <li><a href="#" title="James Carter"><img src="images/user-6.jpg" alt="user" class="img-responsive profile-photo"><span class="online-dot"></span></a></li>
                <li><a href="#" title="Robert Cook"><img src="images/user-7.jpg" alt="user" class="img-responsive profile-photo"><span class="online-dot"></span></a></li>
                <li><a href="#" title="Richard Bell"><img src="images/user-8.jpg" alt="user" class="img-responsive profile-photo"><span class="online-dot"></span></a></li>
                <li><a href="#" title="Anna Young"><img src="images/user-9.jpg" alt="user" class="img-responsive profile-photo"><span class="online-dot"></span></a></li>
                <li><a href="#" title="Julia Cox"><img src="images/user-10.jpg" alt="user" class="img-responsive profile-photo"><span class="online-dot"></span></a></li>
              </ul>
            </div> --><!--chat block ends-->
          </div>
</div>