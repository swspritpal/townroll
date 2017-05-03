@extends('frontend.layouts.app')

@section('title') {{ app_name() }}
@endsection

@section('meta_keywords')Townroll, Social Networking Groups, Townroll.com
@endsection

@section('meta_description')join nearby social networking groups at Townroll, join Townroll.com
@endsection

@section('content')
    <div class="col-md-6 borderRight postContent postContentHome ">
        @include('frontend.includes.places')
        @include('frontend.includes.posts.create')

        <div class="progress home-post-progressbar" style="display: none;">
	    	<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">
	      		
	    	</div>
	  	</div>
        <div class="infinite-scroll append-new-post-content">
        	@each('frontend.includes.posts.single',$posts,'post','frontend.includes.posts.empty')
            
        	{{ $posts
                ->appends($sort_by)
                ->render()
                }} 
        </div>
        
    </div>
 @endsection 	
