<div id="Notifi" class="notificationsLayout hidden">
  <div class="node">
    <i class="fa fa-caret-up fa-2x" aria-hidden="true" style="color:#fff"></i> 
  </div>
  <div class="Notifi-header">
    <h4>Notifications</h4> 
  </div>
    <div class="NotifiWrapper">
       
        @forelse($activities as $activityData)
          @php
            $activityArrays = $enricher->enrichActivities($activityData['activities']);
          @endphp

          @foreach($activityArrays as $activity)
            @include('frontend.includes.notifications.render_activity', ['activity'=>$activity])
          @endforeach
          
          
        @empty
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h5>Check out this tab when you make a post to see Likes, Comments, and new boosts.</h5>  
          <div>
        @endforelse
    </div><!--notify Wrapper Ends -->
  
  @unless(empty($activities))    
    <div class="Notifi-footer"> 
      <h4>See all </h4>
    </div>
  @endunless
</div>