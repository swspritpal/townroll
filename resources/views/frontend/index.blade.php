@extends('frontend.layouts.app')

@section('title') {{ app_name() }}
@endsection

@section('meta_keywords')Townroll, Social Networking Groups, Townroll.com
@endsection

@section('meta_description')join nearby social networking groups at Townroll, join Townroll.com
@endsection

@section('content')
    <div class="col-md-6 borderRight postContent postContentHome " id="app">
        @include('frontend.includes.places')
        @include('frontend.includes.posts.create')

        <div class="progress home-post-progressbar" style="display: none;">
	    	<div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%">
	      		
	    	</div>
	  	</div>
        <div class="infinite-scroll append-new-post-content">
            @forelse ($posts as $post)
                @php
                    $recent_post=$post->user->recent_posts($post->user);
                @endphp

                @if(!empty($recent_post) && count($recent_post) > 1 )
                    @foreach($recent_post as $recent_post_data)
                        @php
                            $recent_posts_ids[]=$recent_post_data->id;
                        @endphp
                    @endforeach

                    @if(in_array($post->id,$recent_posts_ids))
                        @include('frontend.includes.posts.merge-posts-by-single-user',compact('recent_post'))
                    @else
                        @include('frontend.includes.posts.single')
                    @endif
                @else
                    @include('frontend.includes.posts.single')
                @endif

            @empty
                @include('frontend.includes.posts.empty')
            @endforelse
            

        	{{ $posts
                ->appends($sort_by)
                ->render()
                }} 
        </div>
        
    </div>
 @endsection 	
