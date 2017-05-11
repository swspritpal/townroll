<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Post;
use App\Models\Access\User\User;

use Illuminate\Database\Eloquent\SoftDeletes;

class Slap extends Model
{
	use SoftDeletes;

    protected $fillable = [
        'user_id',
        'slapable_id',
        'slapable_type',
    ];

	//protected $dates = ['deleted_at'];

    public function users()
    {
        return $this->belongsToMany(User::class,'slaps','id','user_id');
    }
    /**
     * Get all of the posts that are assigned this like.
     */
    public function posts()
    {
        return $this->morphedByMany('App\Post', 'slaps');
    }
}
