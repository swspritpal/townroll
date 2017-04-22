<?php
namespace App\Http\Composers\Frontend;

use Illuminate\View\View;
use App\Models\Access\User\User;
use App\Post;
use App\Category;
use GetStream\StreamLaravel\Enrich;

class HeaderComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        $feed = \FeedManager::getUserFeed(\Auth::id());
        $enricher = new Enrich;
        $activities = $feed->getActivities(0,25)['results'];
        $activities = $enricher->enrichActivities($activities);

        $view->with(compact('activities'));
    }
}