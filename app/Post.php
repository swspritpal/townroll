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

//use Nicolaslopezj\Searchable\SearchableTrait;

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

    protected $guarded = ['id'];

    protected $fillable = ['user_id', 'published_at', 'status', 'html_content', 'content', 'image_path'];


    /**
     * Searchable rules.
     *
     * @var array
     */
    /*protected $searchable = [
        'columns' => [
            'posts.content' => 20,
        ],
        'joins' => [
        ],
    ];*/

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

    public function getCommentsWithPaginationAttribute()
    {
        return $this->morphMany(Comment::class, 'commentable')->orderBy('created_at', 'desc')->simplePaginate(env('DEFAULT_SINGLE_POST_COMMENTS_LIMIT'));
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
    }


    public function likes()
    {
        //return $this->morphToMany(User::class, 'likes')->whereDeletedAt(null);
        return $this->morphToMany(User::class, 'likeable','likes')->where('likes.deleted_at','=',null);
    }

    public function slaps()
    {
        return $this->morphToMany(User::class, 'slapable','slaps')->where('slaps.deleted_at','=',null);
    }

    
    /**
     * Determine whether a post has been marked as like by a user.
     *
     * @return boolean
     */
    public function getIsLikedAttribute()
    {
        return is_post_liked_by_user($this->id,\Auth::id());
    }
    /**
     * Determine whether a post has been marked as like by a user.
     *
     * @return boolean
     */
    public function getIsSlappedAttribute()
    {
        return (bool) \App\Slap::where('user_id', \Auth::id())
                            ->where('slapable_id', $this->id)
                            ->first();
    }


   /**
     * Get the boost for the post.
     */
    public function boosts()
    {
        return $this->hasMany('\App\BoostPost');
    }

    /**
     * Determine counter for boost
     *
     * @return int
     */
    public function boost_count()
    {
        $boost_count_model=\App\BoostPostCategories::whereIn('boost_post_id',function($boost_post_category_query){
                            $boost_post_category_query
                                ->select(['id'])
                                ->from(with(new \App\BoostPost)->getTable())
                                ->wherePostId($this->id);
                            $boost_post_category_query->get()
                            ->toArray();
                        })
                        ->count(['category_id']);

        return (int) !empty($boost_count_model) ? $boost_count_model : 0;
     }
}
