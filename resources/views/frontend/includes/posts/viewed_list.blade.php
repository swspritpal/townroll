@foreach ($viewed_users as $user)
  
  @foreach ($user->users as $user)
    <div class="row">
        <div class="col-lg-1 col-md-1 col-sm-2 col-xs-2 paddingUnset">
          <img src="{{$user->picture}}" alt="" class="profile-photo-sm">
        </div>
       <div class="col-lg-11 col-md-11 col-sm-10 col-xs-10 ">
          <a href="javascript:void(0);" class="profile-link">{{$user->username}}</a>
        </div>
    </div>
  @endforeach
@endforeach


@if(!empty($viewed_users['0']->users_count))

  @php
    $more_users=(int) ($viewed_users['0']->users_count - env('DEFAULT_HOME_PAGE_VIEWED_USERS_LIMIT'));
  @endphp

  @if($more_users > 0)
    See more users {{$more_users}}
  @endif
@endif
