<?php

namespace App\Models\Access\User;

use Illuminate\Notifications\Notifiable;
use App\Models\Access\User\Traits\UserAccess;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Access\User\Traits\Scope\UserScope;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Access\User\Traits\UserSendPasswordReset;
use App\Models\Access\User\Traits\Attribute\UserAttribute;
use App\Models\Access\User\Traits\Relationship\UserRelationship;
use App\Post;
use Laravel\Passport\HasApiTokens;

/**
 * Class User.
 */
class UserOldProfiles extends Authenticatable
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['name', 'email', 'password', 'status', 'confirmation_code', 'confirmed'];

    /**
     * @var array
     */
    //protected $dates = ['deleted_at'];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
       
    }
}
