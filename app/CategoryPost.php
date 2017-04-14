<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryPost extends Model
{
    protected $guarded = ['id'];

    protected $table = 'category_post';

    public function posts() {
        return $this->belongsTo('posts');
    }

    public function categories() {
        return $this->belongsTo('categories');
    }
}
