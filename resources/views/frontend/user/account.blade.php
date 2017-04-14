@extends('frontend.layouts.app')

@section('content')
    <div class="col-md-6 borderRight  profilePage paddingUnset">
                          <div class="timeline-cover" style="background: url('../img/covers/1.jpg') no-repeat;">
                                <!--Timeline Menu for Large Screens-->
                                <div class="timeline-nav-bar   ">
                                  <div class="row paddingUnsetMobile">
                                    <div class="col-md-12">
                                      <div class="profile-info">
                                       
                                        <img src="{{ $logged_in_user->picture }}" alt="" class="img-responsive profile-photo user-profile-image"> 
                                        <h4>{{ $logged_in_user->name }}</h4>
                                        <p class="text-muted"><a href="#"><i class="fa fa-map-marker" aria-hidden="true"></i> SAS Nagar, Punjab</a></p>
                                      </div>
                                    </div> 
                                  </div>
                                </div><!--Timeline Menu for Large Screens End-->
                                <!--Timeline Menu for Small Screens-->         
                              <!--Timeline Menu for Small Screens End-->
                          </div>
            <!-- Post Create Box
            ================================================= -->
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
                                              <span class="pull-right">110 </span></a>
                                              <div style="clear:both"> </div>
                                            </div>
                                            </li>
                                            <li><i class="icon ion-ios-people"></i>

                                            <div class="LeftMenuLi">
                                              <a href="#" class="LeftSidebarCounter ">
                                              <span class="CounterFontStyle pull-left">  Boost Posts </span>
                                              <span class="pull-right">5 </span></a>
                                              <div style="clear:both"> </div>
                                            </div>
                                            </li>
                                            <li><i class="icon ion-ios-people-outline"></i>

                                            <div class="LeftMenuLi">
                                                <a href="#" class="LeftSidebarCounter">
                                                <span class="CounterFontStyle pull-left">  Groups </span>
                                                <span class="pull-right">6 </span></a>
                                                <div style="clear:both"> </div>
                                            </div>
                                            </li>

                                            <li><i class="fa fa-eye"></i>

                                            <div class="LeftMenuLi">
                                              <a href="#" class="LeftSidebarCounter">
                                              <span class="CounterFontStyle pull-left">  Viewer's </span>
                                              <span class="pull-right">1.3k </span></a>
                                              <div style="clear:both"> </div>
                                            </div>
                                            </li>
                                      </ul>
                                  </div>

                                <!-- Post Create Box End-->

                          </div>

        <div class="col-xs-12" style="display: none;">
            <div class="panel panel-default">
                <div class="panel-heading">{{ trans('navs.frontend.user.account') }}</div>

                <div class="panel-body">

                    <div role="tabpanel">

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">{{ trans('navs.frontend.user.profile') }}</a>
                            </li>

                            <li role="presentation">
                                <a href="#edit" aria-controls="edit" role="tab" data-toggle="tab">{{ trans('labels.frontend.user.profile.update_information') }}</a>
                            </li>

                            @if ($logged_in_user->canChangePassword())
                                <li role="presentation">
                                    <a href="#password" aria-controls="password" role="tab" data-toggle="tab">{{ trans('navs.frontend.user.change_password') }}</a>
                                </li>
                            @endif
                        </ul>

                        <div class="tab-content">

                            <div role="tabpanel" class="tab-pane mt-30 active" id="profile">
                                @include('frontend.user.account.tabs.profile')
                            </div><!--tab panel profile-->

                            <div role="tabpanel" class="tab-pane mt-30" id="edit">
                                @include('frontend.user.account.tabs.edit')
                            </div><!--tab panel profile-->

                            @if ($logged_in_user->canChangePassword())
                                <div role="tabpanel" class="tab-pane mt-30" id="password">
                                    @include('frontend.user.account.tabs.change-password')
                                </div><!--tab panel change password-->
                            @endif

                        </div><!--tab content-->

                    </div><!--tab panel-->

                </div><!--panel body-->

            </div><!-- panel -->

        </div><!-- col-xs-12 -->

<div id="products">
        <div class="post-content item ">
             
              <div class="post-container" style="margin-top:30px;">
                <img src="img/user-5.jpg" alt="user" class="profile-photo-md pull-left">
                <div class="post-detail">
                  <div class="user-info">
                    <a href="javascript:void(0);" data-toggle="modal" data-target="#myProfile" class="profile-link" class="profile-link">Alexis Clark</a> 
                    <span class="GroupInfo">Published a photo in <a href="#">Ludhiana Locals</a> </span> 
                    <p class="text-muted"> about 3 mins ago</p>
                    <div class="reportPost"> 
                      <a href="#">
                        <span class="reportPostIcon"><i class="fa fa-exclamation-triangle" aria-hidden="true" ></i> Report post</span>
                        
                      </a> 
                    </div>
                  </div>

                   <img src="img/1.jpg" alt="post-image" class="img-responsive post-image">

                  
                  <div class="line-divider"></div>
                  <div class="post-text">

                
                    <p class="postTextLimit">

                    <?php
                        $PostText = "Lorem ipsum dolor sit amet, consectetur 
                        adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore 
                        magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                        laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor 
                        in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla 
                        pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa 
                        qui officia deserunt mollit anim id est laborum.";

                        if(strlen($PostText) > 350) $PostText = substr($PostText, 0, 350).'... <a href="#">Read more </a>';
                        echo $PostText;

                    ?>  

                    <i class="em em-anguished"></i> <i class="em em-anguished"></i> <i class="em em-anguished"></i></p>
                  </div>
                    <div class="reaction">
                      <a class="btn text-grey hoverRed paddingUnset"><i class="fa fa-heart-o"></i></a> 
                      <a class="btn text-grey hoverRed paddingUnset" href="javascript:void(0);">13 Likes</a>

                      <a class="btn text-grey hoverOlive"><i class="fa fa-comment-o"></i> 20 Comments</a>
                      <a class="btn text-grey hoverCyan"><i class="fa fa-eye"></i> 36 Views</a>

                      <span class="pull-right"> 
                      <a class="btn text-grey hoverOrange paddingUnset" title="Boost post"><i class="fa fa-rocket"></i> Boost</a> 
                      <a class="btn text-grey hoverOrange paddingUnset" href="javascript:void(0);">(8)</a> </span>

                    </div>
                  <div class="line-divider"></div>
                  
                  <div class="clearfix">
                    <p>
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 paddingUnset">
                        <img src="img/user-11.jpg" alt="" class="profile-photo-sm">
                        </div>
                          <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                            <a href="#" class="profile-link">Diana </a>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud 
                            <p class="replyComment">
                            <a href="javascript:void(0);"><i class="fa fa-reply replyIcon"></i> Reply </a>       
                            </p>
                          </div>
                    </p> 
                      
                  </div>

                  <div class="clearfix">
                        <p>
                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 paddingUnset">
                            <img src="img/user-4.jpg" alt="" class="profile-photo-sm">
                            </div>
                              <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                              <a href="javascript:void(0);" data-toggle="modal" data-target="#myProfile" class="profile-link" class="profile-link">John </a>
                              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud 
                              <p class="replyComment"><a href="javascript:void(0);"><i class="fa fa-reply replyIcon"></i> 
                              Reply </a>       
                              </p>
                              </div>
                        </p>     
                  </div>

                   <div class="clearfix">
                   <p><a href="javascript:void(0);"> View more comments </a></p>
                    
                      <div class="col-lg-1 col-md-1 col-sm-2 col-xs-2 paddingUnset">
                        <img src="img/user-1.jpg" alt="" class="profile-photo-sm">
                      </div>
                      <div class="col-lg-11 col-md-11 col-sm-10 col-xs-10 paddingUnset">
                        <input class="form-control" placeholder="Post a comment" type="text">
                      </div>

                  </div>
              </div>
            </div>

            </div>
</div>
    </div><!-- row -->
@endsection