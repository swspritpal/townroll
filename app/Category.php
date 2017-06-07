<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Category extends Model
{
    use SearchableTrait;

    protected $guarded = ['id'];

     /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        'columns' => [
            'categories.name' => 10,
            //'categories.vicinity' => 10,
        ],
        'joins' => [
        ],
    ];



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
