<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Comment;
use App\Page;
use App\Policies\CommentPolicy;
use App\Policies\PagePolicy;
use App\Policies\PostPolicy;
use App\Policies\UserPolicy;
use App\Post;
use App\User;
use Laravel\Passport\Passport;

/**
 * Class AuthServiceProvider.
 */
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Post::class => PostPolicy::class,
        //Page::class => PagePolicy::class,
        Comment::class => CommentPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Comment::observe(CommentObserver::class);
        Post::observe(PostObserver::class);
        //Page::observe(PageObserver::class);

        $this->registerPolicies();
        
        Passport::routes();

        //
    }
}
