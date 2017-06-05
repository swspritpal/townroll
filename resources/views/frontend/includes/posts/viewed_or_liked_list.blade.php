@foreach ($viewed_or_liked_users as $model)
  <div class="row">
      <div class="col-lg-1 col-md-1 col-sm-2 col-xs-2 paddingUnset">
        <a href="{{ route('frontend.auth.user.profile',$model->user->username) }}" class="profile-link">
          <img src="{{$model->user->picture}}" alt="" class="profile-photo-sm">
        </a>
      </div>
     <div class="col-lg-11 col-md-11 col-sm-10 col-xs-10 ">
        <a href="{{ route('frontend.auth.user.profile',$model->user->username) }}" class="profile-link">{!! $model->user->username !!}</a>
      </div>
  </div>
@endforeach


@unless(empty($viewed_or_liked_users->nextPageUrl()))
  <div class="viewAllGroups popup-user-paginator">
    <span class="pull-right"><a href="javascript:void( 0);" class="load-more-users-popup" data-next-page-url="{{ $viewed_or_liked_users->nextPageUrl() }}">Load More</a> </span> 
  </div>
@endunless
