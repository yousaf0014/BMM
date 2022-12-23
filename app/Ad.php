<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Ad extends BaseModel
{
    //
    //
    protected $fillable = [
        'title','zone_id','url','type','message','beacon_id'
    ];

    public function zone(){
    	return $this->belongsTo(Zone::class);
    }

    public function beacon(){
        return $this->belongsTo(Beacon::class);
    }
}
