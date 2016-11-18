<?php

namespace SmartBots;

use Moloquent;

class HubPermission extends Moloquent
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
        'user_id','bot_id','high','notice'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    // Relationships -----------------------------------------------------------------------------------------

    public function user() {
        return $this->belongsTo('SmartBots\User','user_id');
    }
    public function hub() {
        return $this->belongsTo('SmartBots\Hub','hub_id');
    }
}
