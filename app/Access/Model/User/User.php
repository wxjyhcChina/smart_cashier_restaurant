<?php

namespace App\Access\Model\User;

use Illuminate\Notifications\Notifiable;
use App\Access\Model\User\Traits\UserAccess;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Access\Model\User\Traits\Scope\UserScope;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Access\Model\User\Traits\UserSendPasswordReset;
use App\Access\Model\User\Traits\Attribute\UserAttribute;
use App\Access\Model\User\Traits\Relationship\UserRelationship;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User.
 */
class User extends Authenticatable implements JWTSubject
{
    use UserScope,
        UserAccess,
        Notifiable,
        SoftDeletes,
        UserAttribute,
        UserRelationship,
        UserSendPasswordReset;

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
    protected $fillable = ['first_name', 'last_name', 'password', 'status', 'confirmation_code', 'confirmed'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The dynamic attributes from mutators that should be returned with the user object.
     * @var array
     */
    protected $appends = ['full_name', 'name'];

    /**
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('access.users_table');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->id;
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'type' => 'restaurant'
        ];
    }
}
