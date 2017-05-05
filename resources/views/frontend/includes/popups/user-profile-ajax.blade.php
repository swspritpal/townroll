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

      @include('frontend.user.profile.counter-data')
    </div>
  </div>

  <div class="modal-footer clearfix">
    <a href="{{ route('frontend.auth.user.profile',$user->username) }}" >View full profile</a>
  </div>

</div>