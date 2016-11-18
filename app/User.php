<?php

namespace SmartBots;

// use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Moloquent;

class User extends Moloquent implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use Notifiable;

    /**
     * Should timestamp (create_at, update_at,...) appear in the collection?.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * When use a hybird database connection, this must be defined to overide the default database connection
     */
    // protected $connection = 'mongodb';

    /**
     * Name of the collection (instead of lower-case, plural name of model)
     * @var string
     */
    // protected $collection = 'users';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = '_id';
    /**
     * The attributes that aren't mass assignable - Blacklist
     *
     * @var array
     */
    // protected $guarded = ['price'];

    /**
     * The attributes that are mass assignable - Whitelist
     *
     * @var array
     */
    protected $fillable = [
        'username', 'name', 'email', 'password','avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    // Relationships -----------------------------------------------------------------------------------------

    public function members() {
        return $this->hasMany('SmartBots\Member','user_id');
    }

    public function hubpermissions() {
        return $this->hasMany('SmartBots\HubPermission','user_id');
    }

    public function hubs() {
        return $this->belongsToMany('SmartBots\Hub','members');
    }

    public function botpermissions() {
        return $this->hasMany('SmartBots\BotPermission','user_id');
    }

    // Query Scopes -----------------------------------------------------------------------------------------

    // Functions -----------------------------------------------------------------------------------------

    // Mutators -----------------------------------------------------------------------------------------

}
