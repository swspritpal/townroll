

@forelse ($categories as $category)

	<label for="boost_cat_{!! $category->name !!}" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 paddingUnset  seperatorGroup" style="clear:both">
		<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1  paddingUnset">
		  <img src="{{ asset(env('PLACE_IMAGES_FOLDER').$category->place_image_path) }}" class="imgCircle" />
		</div>
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 ">
		  <div class="groupTitle">{!! $category->name !!}</div>
		  <div class="groupPopulation text-muted" ><i class="fa fa-users" aria-hidden="true"></i>{{ $category->users_count }} population </div>
		</div> 
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3  paddingUnset">
		  	<div class=" checkbox-info">
		      <input type="checkbox" class="boost_category_checkbox" name="post_boost_groups[]" value="{!! $category->id !!}" id="boost_cat_{!! $category->name !!}" {{ (is_post_already_boost_for_category($post_id,$category->id,access()->user()->id)) ? 'checked':'' }}>		      
		    </div>
		</div>
	</label>
@empty
	<article class="col-lg-12 col-md-12 col-sm-12 col-xs-12 paddingUnset  seperatorGroup" style="clear:both">
		<h4>No group found</h4>
	</article>
@endforelse
<div style="clear:both"> </div>

@unless(empty($categories->nextPageUrl()))
	<div class="viewAllGroups boost-paginator">
		<span class="pull-right"><a href="javascript:void( 0);" class="viewAllGroupLink load-more-boost-posts" data-next-page-url="{{ $categories->nextPageUrl() }}">Load More</a> </span> 
	</div>
@endunless
