@extends('frontend.layouts.app')

@section('title') {{ app_name() }}
@endsection

@section('meta_keywords')Townroll, Social Networking Groups, Townroll.com
@endsection

@section('meta_description')join nearby social networking groups at Townroll, join Townroll.com
@endsection

@section('content')
    <div class="col-md-6 borderRight postContent postContentHome " id="app">

        <div class="progress home-post-progressbar" style="display: none;">
	    	<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">
	      		
	    	</div>
	  	</div>

        @unless($users->isEmpty())
            <div class="panel panel-primary search-user-panel">
                <div class="panel-heading">Users</div>

                <div class="panel-body">
                    @foreach($users as $user)
                        
                        <div class="row">
                          <div class="col-lg-1 col-md-1 col-sm-2 col-xs-2 paddingUnset">
                            <a href="{{ route('frontend.auth.user.profile',$user->username) }}" class="profile-link">
                              <img src="{{$user->picture}}" alt="" class="profile-photo-sm">
                            </a>
                          </div>
                         <div class="col-lg-11 col-md-11 col-sm-10 col-xs-10 ">
                            <a href="{{ route('frontend.auth.user.profile',$user->username) }}" class="profile-link">{!! $user->username !!}</a>
                          </div>
                      </div>
                    @endforeach
                </div>

                <div class="panel-footer">
                    @unless(empty($users->nextPageUrl()))
                        <a href='{{ route('frontend.search.users',['q'=>$q]) }}'>See All</a>
                    @endunless
                </div>
            </div>
        @endunless

        @unless($categories->isEmpty())
            <div class="panel panel-success search-user-panel">
                <div class="panel-heading">Groups</div>

                <div class="panel-body">
                    @foreach($categories as $user_category)
                        @include('frontend.includes.categories.horizontal',compact('user_category'))
                    @endforeach
                </div>

                <div class="panel-footer">
                    @unless(empty($categories->nextPageUrl()))
                        <a href='{{ route('frontend.search.categories',['q'=>$q]) }}'>See All</a>
                    @endunless
                </div>
            </div>
        @endunless
        
        @unless($posts->isEmpty())
            <div class="append-new-post-content">
                @foreach ($posts as $post)
                    @include('frontend.includes.posts.single')
                @endforeach

                @unless(empty($posts->nextPageUrl()))
                    <a href='{{ route('frontend.search.posts',['q'=>$q]) }}'>See All</a>
            	@endunless
            </div>
        @endunless        
    </div>
 @endsection 	
