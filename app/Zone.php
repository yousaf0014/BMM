<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zone extends BaseModel
{
    //
    //
    protected $fillable = [
        'building_id','user_id','name','floor','floor_name','shop','shop_name','created_by','updated_by','created_at','updated_at'
    ];

    public function beacon(){
    	return $this->hasMany(Beacon::class);
    }

    public function ads(){
    	return $this->hasMany(Ad::class);
    }
}
