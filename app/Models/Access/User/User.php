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
        HasApiTokens;


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


    public function getMetaAttribute($meta)
    {
        $a = json_decode($meta, true);
        return $a ? $a : array();
    }

    public function setMetaAttribute($meta)
    {
        $this->attributes['meta'] = json_encode($meta);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
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
    
   /* public function likes()
    {
        //return $this->belongsToMany(Post::class, 'likes', 'post_id', 'user_id')->withTimeStamps();
        return $this->belongsToMany(\App\Like::class, 'likes', 'user_id', 'post_id')->withTimeStamps();
    }*/

    public function likedPosts()
    {
        return $this->morphedByMany('App\Post', 'likeable')->whereDeletedAt(null);
    }

    /**
     * The user that belong to the view post.
     */
    public function views()
    {
        return $this->belongsToMany(Post::class, 'views', 'user_id', 'post_id')->withTimeStamps();
        //return $this->belongsToMany(\App\View::class, 'views', 'user_id', 'post_id')->withTimeStamps();
    }
}
