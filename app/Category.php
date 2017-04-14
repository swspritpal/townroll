<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = ['id'];

    //
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    /**
     * The category that belong to the user.
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\Access\User\User');
    }
}
