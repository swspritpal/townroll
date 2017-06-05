<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class BoostPost extends Model
{
	use SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'boosts_post';



    public function post() {
        return $this->belongsTo('\App\Post');
    }

    public function user() {
        return $this->belongsTo('\App\Models\Access\User\User');
    }

    public function boostCategories() {
        return $this->hasMany('\App\BoostPostCategories','boost_post_id','id');
    }

}
