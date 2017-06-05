<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class BoostPostCategories extends Model
{
	use SoftDeletes;

    protected $guarded = ['id'];

    protected $table = 'boosts_post_categories';

    public function boostPost() {
        return $this->belongsTo('\App\BoostPost');
    }

   /* public function categories() {
        return $this->belongsToMany('\App\Category','boosts_post_categories','id','category_id');
    }*/

    public function category() {
        return $this->belongsTo('\App\Category');
    }
}
