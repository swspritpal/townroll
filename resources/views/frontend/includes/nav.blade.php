
<header id="header">
      <nav class="navbar navbar-default navbar-fixed-top menu">
        <div class="container">

          <!-- Brand and toggle get grouped for better mobile display -->
         

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right main-menu">
              
              <li class="dropdown">
                <a href="javascript:void(0);"> <img src="{{ $logged_in_user->picture }}" alt="user" class="menu-profile-photo"> </a>
                 
              </li>
              <li class="dropdown">
                <a href="javascript:void(0);" data-toggle="modal" data-target="#add_post"> <i class="fa fa-plus-square-o fa-2x" aria-hidden="true"></i></a>
                 
              </li>
              <li class="dropdown">
                <a href="javascript:void(0);" class='locate-me-popup' > <i class="fa fa-map-marker fa-2x" aria-hidden="true"></i> </a>
                 
              </li>

              <li class="dropdown">
                <a href="{{ route('frontend.index') }}"> <i class="fa fa-home fa-2x" aria-hidden="true"></i> </a>                
              </li>

              <li class="dropdown" id="notificationDropdown">
                <a href="javascript:void(0);"> <i class="fa fa-globe fa-2x" aria-hidden="true"></i></i> </a> 
                  @include('frontend.includes.notifications.count')
              </li>

              @include('frontend.includes.notifications.view')
            </ul>

            <div class="home-search-box">
              {{ Form::open(['route' => 'frontend.search', 'method'=>'GET','class'=>'typeahead','role'=>'search']) }}
                <div class="form-group">
                  <input type="search" name="q" class="form-control search-input" placeholder="Search" autocomplete="off" value="{{ (Request::has('q')) ? Request::get('q'):'' }}">
                </div>
                <input type="submit" class="hidden" />
              {{ Form::close() }}        
            </div>
            
            <div class="pull-right navLogout">
              {{ link_to_route('frontend.auth.logout', 'Logout',null,['class'=>'fa fa-sign-out fa-fw']) }}
            </div>

          </div><!-- /.navbar-collapse -->
        </div><!-- /.container -->
      </nav>
    </header>