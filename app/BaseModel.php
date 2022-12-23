<?php 
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class BaseModel extends Model
{
    public static function boot()
    {
        parent::boot();
        static::creating(function($model)
        {
            $table = $model->getTable();
            if (Schema::hasColumn($table, 'created_by')) {
                $model->created_by = Auth::user()->id;
                if(!empty(Session::get('whois',null))){
                    $model->created_by = Session::get('whois');
                }                
            }
            if (Schema::hasColumn($table, 'updated_by')) {
                $model->updated_by = Auth::user()->id;
                if(!empty(Session::get('whois',null))){
                    $model->updated_by = Session::get('whois');
                }                
            }
            if (Schema::hasColumn($table, 'building_id')) {
                if(!empty(Session::get('current_building',null))){
                    $model->building_id = Session::get('current_building',null);
                }                
            }
        });

        static::updating(function($model)
        {
            $table = $model->getTable();
            if (Schema::hasColumn($table, 'updated_by')) {
                $model->updated_by = Auth::user()->id;
                if(!empty(Session::get('whois',null))){
                    $model->updated_by = Session::get('whois');
                }                
            }
            if (Schema::hasColumn($table, 'updated_at')) {
                $model->updated_at = date('Y-m-d h:i:s');             
            }

        });
    }
}

?>