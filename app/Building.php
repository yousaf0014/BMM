<?php
namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Building extends BaseModel
{
    //
    //
    use SoftDeletes;
    
    protected $fillable = [
        'name','details','address','user_id'
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
