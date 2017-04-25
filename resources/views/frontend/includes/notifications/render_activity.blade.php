@if (is_array($activity) || $activity->enriched())
    @if ($activity['verb'] == "like")
       @include('frontend.includes.notifications.like')

    @elseif ($activity['verb'] == "mention_in_comment")
    	@include('frontend.includes.notifications.mention_in_comment')
    @else
       @include('frontend.includes.notifications.comment')
    @endif
@else
    {{ '' }}
    Log::warning('The activity could not be rendered, the following field/refs could not be enriched:', $activity->getNotEnrichedData());
@endif
