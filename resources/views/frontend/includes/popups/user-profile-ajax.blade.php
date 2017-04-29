<div class="col-md-12  profilePage paddingUnset">
  <div class="timeline-cover" style="background: url('../img/covers/1.jpg') no-repeat;">
    <!--Timeline Menu for Large Screens-->
    <div class="timeline-nav-bar hidden-sm hidden-xs">
      <div class="row">
        <div class="col-md-12">
          <div class="profile-info"> 
            <a href="{{ route('frontend.auth.user.profile',$user->username) }}">
              <img src="{{ $user->picture }}" alt="" class="img-responsive profile-photo"> 
            </a>
            <a href="{{ route('frontend.auth.user.profile',$user->username) }}">
              <h4>{{ $user->username }}</h4>
            </a>

          <p class="text-muted"><i class="fa fa-map-marker" aria-hidden="true"></i> {{ get_city_name($user->city_id) }}</p>
          </div>
        </div>
        </div>
      </div><!--Timeline Menu for Large Screens End-->
  </div>
  <div class="createPostWrapper">
    <div class="col-md-2">
      <ul class="list-inline profile-menu">
      <li><i class="fa fa-th-large fa-2x" aria-hidden="true"></i></li>
      <li><i class="fa fa-list-ul fa-2x" aria-hidden="true"></i></li>   
      </ul>
      </div>
      <div class="col-md-6 hidden-xs hidden-sm">&nbsp; </div>
      <div class="col-md-4 profileCounter">
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
      <span class="pull-right">0 </span></a>
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
      <span class="pull-right">0 </span></a>
      <div style="clear:both"> </div>
      </div>
      </li>
      </ul>
    </div>
  </div>

  <div class="modal-footer clearfix">
    <a href="{{ route('frontend.auth.user.profile',$user->username) }}" >View full profile</a>
  </div>

</div>