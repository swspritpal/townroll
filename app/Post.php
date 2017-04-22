<?php

namespace App;

use App\Scopes\PublishedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Lufficc\Comment\CommentHelper;
use Lufficc\Config\ConfigureHelper;
use Carbon\Carbon;
use App\Like;
use App\View;
use App\Models\Access\User\User;
use Lufficc\Post\PostHelper;

class Post extends Model
{
    use SoftDeletes, CommentHelper, ConfigureHelper,PostHelper;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new PublishedScope());
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at', 'published_at'];

    const selectArrayWithOutContent = [
        'id',
        'user_id',
        'category_id',
        'title',
        'slug',
        'view_count',
        'description',
        'updated_at',
        'created_at',
        'status'
    ];

    protected $fillable = ['user_id', 'published_at', 'status', 'html_content', 'content', 'image_path'];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function getlastestCommentsAttribute()
    {
        return $this->morphMany(Comment::class, 'commentable')->orderBy('created_at', 'desc')->limit(env('DEFAULT_HOME_PAGE_POST_COMMENTS'))->get();
    }

    public function configuration()
    {
        return $this->morphOne(Configuration::class, 'configurable');
    }

    public function isPublished()
    {
        return $this->status == 1;
    }

    /**
     * @return array
     */
    public function getConfigKeys()
    {
        return ['allow_resource_comment', 'comment_type', 'comment_info'];
    }

     # Use this to count the comments
    /*public function getCommentCountAttribute(){
        return $this->comments->count();
    }*/



    public function getCreatedAtAttribute($value)
    {
        return show_time($value);
    }

    public function views()
    {
        return $this->belongsToMany(User::class, 'views', 'post_id', 'user_id')->withTimeStamps();
        //return $this->belongsToMany(View::class, 'views', 'post_id', 'user_id')->withTimeStamps();
    }

    /*public function like()
    {
        //testing
        return $this->hasMany(\App\Like::class)->withTimeStamps();

        //return $this->belongsToMany(User::class, 'views', 'post_id', 'user_id')->withTimeStamps();
        //return $this->belongsToMany(\App\like::class, 'likes', 'post_id', 'user_id')->withTimeStamps();
    }*/

    public function likes()
    {
        return $this->morphToMany(User::class, 'likes')->whereDeletedAt(null);
    }

    
    /**
     * Determine whether a post has been marked as like by a user.
     *
     * @return boolean
     */
    public function getIsLikedAttribute()
    {
        //$like = $this->likes()->whereUserId(\Auth::id())->first();        
        //return (!is_null($like)) ? true : false;

        return (bool) Like::where('user_id', \Auth::id())
                            ->where('likeable_id', $this->id)
                            ->first();
    }

    /**
     * Determine whether a post like counter
     *
     * @return int
     */
    public function like_count()
    {
        return (int) Like::where('likeable_id', $this->id)
                            ->count();
    }
    /**
     * Post total views
     *
     * @return int
     */
    public function view_count()
    {
        return (int) View::where('post_id', $this->id)
                            ->count();
    }
}
