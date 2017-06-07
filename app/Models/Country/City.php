<?php

namespace App\Models\Country;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public function user()
    {
        return $this->belongsTo(App\Models\User\User::class);
    }
}
