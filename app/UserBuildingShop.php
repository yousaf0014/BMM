<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserBuildingShop extends BaseModel
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user_buildings_shopes';

    protected $fillable = [
        'user_id', 'building_id','shop_id'
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function building(){
        return $this->belongsTo(User::class);
    }

    public function shop(){
        return $this->belongsTo(Zone::class);
    }

}