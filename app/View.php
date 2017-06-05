<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Post;
use App\Models\Access\User\User;

class View extends Model
{
    
    public function user()
    {
        //return $this->belongsToMany(User::class,'views','post_id','user_id');
        //return $this->belongsToMany(User::class,'views','id','user_id');
        return $this->belongsTo(User::class);
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class,'views','user_id','post_id');
    }
    
}
