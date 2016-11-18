<?php

namespace SmartBots;

use Moloquent;

class Bot extends Moloquent
{
    private $virtual_bot = false; // No hardware connection but wanna test software?

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
        'hub_id','name','token','image','description','type','online','active','state','status'
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

    public function hub() {
        return $this->belongsTo('SmartBots\Hub','hub_id');
    }
}
