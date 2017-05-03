
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 paddingUnset  seperatorGroup">
	<a href="{{ route('frontend.index', ['cat' =>$user_category->id ]) }}" class="filter-posts {{ ( (app('request')->has('cat') && app('request')->input('cat') == $user_category->id) ? 'place-active':'') }}" data-filter-by="cat" data-filter-id="{{ $user_category->id }}">
	  <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3  paddingUnset">
	    <img src="{{ asset(env('PLACE_IMAGES_FOLDER').$user_category->place_image_path) }}" class="imgCircle" />
	  </div>
	  <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 ">
	    <div class="groupTitle">{!! $user_category->name !!}</div>
	    <div class="groupPopulation text-muted" ><i class="fa fa-users" aria-hidden="true"></i> {{ $user_category->users_count }} population </div>
	  </div>
	</a>
</div>