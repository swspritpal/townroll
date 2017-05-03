@extends('frontend.layouts.app')

@section('content')
    <div class="col-md-6 borderRight  profilePage paddingUnset">
        <div class="timeline-cover" style="background: url('../img/covers/1.jpg') no-repeat;">
              <!--Timeline Menu for Large Screens-->
              <div class="timeline-nav-bar   ">
                <div class="row paddingUnsetMobile">
                  <div class="col-md-12">
                    <div class="profile-info">
                     
                      <img src="{{ $user->picture }}" alt="" class="img-responsive profile-photo user-profile-image"> 
                      <h4>{{ $user->username }}</h4>
                      <p class="text-muted"><i class="fa fa-map-marker" aria-hidden="true"></i> {{ get_city_name($user->city_id) }}</p>
                    </div>
                  </div> 
                </div>
              </div><!--Timeline Menu for Large Screens End-->
              <!--Timeline Menu for Small Screens-->         
            <!--Timeline Menu for Small Screens End-->
        </div>

        <div class="createPostWrapper" >
                <div class="col-lg-2 col-md-2  col-sm-2 col-xs-3 paddingUnset ">
                  <ul class="list-inline profile-menu">
                      <li>
                            <a href="javascript:void(0);" id="list">
                                  <i class="fa fa-th-large fa-2x"  aria-hidden="true"></i> 
                            </a>
                      </li>
                    <li>
                        <a href="javascript:void(0);" id="grid">
                            <i class="fa fa-list-ul fa-2x"  aria-hidden="true"></i> 
                        </a>
                    </li>

                  </ul>
                </div>

                <div class="col-lg-6 col-md-6  col-sm-6 col-xs-2 ">&nbsp; </div>

                <div class="col-lg-4 col-md-4  col-sm-4 col-xs-7  profileCounter">
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
                            <span class="pull-right">{{ $total_places_users }} </span></a>
                            <div style="clear:both"> </div>
                          </div>
                          </li>
                    </ul>
                </div>
        </div>

    <section >
        <div class="infinite-scroll append-new-post-content">
          @each('frontend.includes.posts.single',$posts,'post','frontend.includes.posts.empty')
            
          {{ $posts
                ->appends($sort_by)
                ->render()
                }} 
        </div>
    </section>
    </div><!-- row -->
@endsection