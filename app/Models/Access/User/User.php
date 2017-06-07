<?php

namespace App\Models\Access\User;

use Illuminate\Notifications\Notifiable;
use App\Models\Access\User\Traits\UserAccess;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Access\User\Traits\Scope\UserScope;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Access\User\Traits\UserSendPasswordReset;
use App\Models\Access\User\Traits\Attribute\UserAttribute;
use App\Models\Access\User\Traits\Relationship\UserRelationship;
use App\Post;
use Laravel\Passport\HasApiTokens;
use Nicolaslopezj\Searchable\SearchableTrait;

/**
 * Class User.
 */
class User extends Authenticatable
{
    use UserScope,
        UserAccess,
        Notifiable,
        SoftDeletes,
        UserAttribute,
        UserRelationship,
        UserSendPasswordReset,
        HasApiTokens,
        SearchableTrait;


    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'status', 'confirmation_code', 'confirmed'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('access.users_table');
    }

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'users.id' => 10,
            'users.name' => 3,
            'users.username' => 10,
            'users.email' => 10,
            'cities.name' => 5,
            'countries.name' => 5,            
        ],
        'joins' => [
            'cities' => ['users.city_id','cities.id'],
            'countries' => ['users.country_id','countries.id'],
        ],
    ];


    public function getMetaAttribute($meta)
    {
        $a = json_decode($meta, true);
        return $a ? $a : array();
    }

    public function setMetaAttribute($meta)
    {
        $this->attributes['meta'] = json_encode($meta);
    }

    public function city()
    {
        return $this->hasOne(App\Models\Country\City::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function boostPosts()
    {
        return $this->hasMany(\App\BoostPost::class);
    }

    /**
     * The user that belong to the category.
     */
    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    /**
     * The user that belong to the likes post.
     */
    
    public function likes()
    {
        return $this->belongsToMany(\App\Like::class, 'likes', 'user_id', 'post_id')->withTimeStamps();
    }

    public function slaps()
    {
        return $this->belongsToMany(\App\Slap::class, 'slaps', 'user_id', 'post_id')->withTimeStamps();
    }

    public function likedPosts()
    {
        return $this->morphedByMany('App\Post', 'likeable')->whereDeletedAt(null);
    }

    public function slappedPosts()
    {
        return $this->morphedByMany('App\Post', 'slapable')->whereDeletedAt(null);
    }

    /**
     * The user that belong to the view post.
     */
    public function views()
    {
        //return $this->belongsToMany(Post::class, 'views', 'user_id', 'post_id')->withTimeStamps();
        //return $this->belongsToMany(Post::class, 'views', 'user_id', 'post_id')->withTimeStamps();

        return $this->belongsToMany(\App\View::class, 'views', 'user_id', 'post_id')->withTimeStamps();
    }

    public  function scopeLike($query, $field, $value){
            return $query->where($field, 'LIKE', "$value%");
    }

    public  function recent_posts($user_model){

        $previous_posts_on_certain_time= \Carbon\Carbon::now()->subHours(env('DEFAULT_RECENT_POST_MERGE_IN_HOURS'))->toDateTimeString();

        return \App\Post::with(['user','categories'])
                ->where('created_at','>=',$previous_posts_on_certain_time)
                ->whereUserId($user_model->id)
                ->orderBy('created_at', 'desc')
                //->toSql();
                ->get();
    }
}
