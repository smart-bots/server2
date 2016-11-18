<?php

namespace SmartBots;

// use Illuminate\Database\Eloquent\Model;
use Moloquent;

class Hub extends Moloquent
{
    /**
     * Should timestamp (create_at, update_at,...) appear in the collection?.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = '_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token','name','image','description','active','timezone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'token'
    ];

    // Relationships -----------------------------------------------------------------------------------------

    public function bots() {
        return $this->hasMany('SmartBots\Bot','hub_id');
    }

    public function users() {
        return $this->belongsToMany('SmartBots\User','members');
    }

}
