<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Post;
use App\Models\Access\User\User;

class Like extends Model
{
    public function users()
    {
        //return $this->belongsToMany(User::class,'likes','post_id','user_id');
        return $this->belongsToMany(User::class,'likes','id','user_id');
    }

    /*public function posts()
    {
        return $this->belongsToMany(Post::class,'likes','post_id','user_id');
    }*/
}
