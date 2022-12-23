<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'building_id','user_id','start_time','end_time','beacon_id','beacon_unique_id','zone_id','zone_name','shop_name','floor'
    ];

    public function zone(){
    	return $this->belongsTo(Zone::class);
    }

    public function beacon(){
        return $this->belongsTo(Beacon::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
