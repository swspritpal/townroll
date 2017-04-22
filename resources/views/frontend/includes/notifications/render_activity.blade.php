@if (is_array($activity) || $activity->enriched())
    @if ($activity['verb'] == "like")
       @include('frontend.includes.notifications.like')
    @else
       @include('frontend.includes.notifications.comment')
    @endif
@else
    {{ '' }}
    Log::warning('The activity could not be rendered, the following field/refs could not be enriched:', $activity->getNotEnrichedData());
@endif
