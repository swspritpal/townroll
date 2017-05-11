
<a class="clearfix" href="javascript:void(0);"  data-action="post-single-popup" data-post="{{ $activity['object']->id }}">
	<article class="col-lg-12 col-md-12 col-sm-12 col-xs-12 Notifi-box">
		<div class="userProfile ">
		  <img class="img-responsive" src="{{ $activity['actor']->picture }}"/>
		</div>
		<div class="UserAction">
		  <p class="marginUnset"> {{ $activity['actor']->username }} slap your post </p>
		  <p class="marginUnset"><i class="fa fa-hand-paper-o" style="color:#FD3E3E;" aria-hidden="true"></i> {{ show_time($activity['time']) }}  </p>
		</div>

		@unless(empty($activity['object']->image_path))
			<div class="NotificationImage ">
			  	<img class="img-responsive" src="{{ asset( env('POST_IMAGES_FOLDER').$activity['object']->image_path) }}"/>
			</div>
		@endunless
	</article>
</a>