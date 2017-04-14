<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\Access\User\User;

class CategoryUser extends Model
{
    protected $guarded = ['id'];

    protected $table = 'category_user';

    public function users() {
        return $this->belongsTo('users');
    }

    public function categories() {
        return $this->belongsTo('categories');
    }
}
