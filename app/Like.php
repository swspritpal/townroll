<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Post;
use App\Models\Access\User\User;

use Illuminate\Database\Eloquent\SoftDeletes;

class Like extends Model
{
	use SoftDeletes;

    protected $fillable = [
        'user_id',
        'likeable_id',
        'likeable_type',
    ];
	//use GetStream\StreamLaravel\Eloquent\ActivityTrait;

	//protected $dates = ['deleted_at'];

    public function users()
    {
        return $this->belongsToMany(User::class,'likes','id','user_id');
    }
    /**
     * Get all of the posts that are assigned this like.
     */
    public function posts()
    {
        return $this->morphedByMany('App\Post', 'likes');
    }
}
