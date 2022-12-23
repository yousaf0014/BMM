<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Beacon extends BaseModel
{
    
    protected $fillable = [
        'building_id','user_id','unique_id','minor','major','ranging','uuid','mac_address','status','created_by','updated_by','created_at',' updated_at','zone_id'
    ];

    public function zone(){
        return $this->belongsTo(Zone::class);
    	
    }
    
}
