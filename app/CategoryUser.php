<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\Access\User\User;
use App\Category;

class CategoryUser extends Model
{
    protected $guarded = ['id'];

    protected $table = 'category_user';

    public function users() {
        return $this->belongsTo('App\Models\Access\User\User');
    }

    public function categories() {
        return $this->belongsTo('App\Category');
    }
}
