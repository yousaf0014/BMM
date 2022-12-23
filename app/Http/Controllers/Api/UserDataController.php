<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use DB;
use App\User;
use App\Beacon;
use App\Zone;
use App\Ad;
use App\Attendance;

class UserDataController extends BaseController 
{
    public function  attandance(Request $request){
        $user = Auth::user();
        $beaconObj = Beacon::where('id',$request->beacon)->orWhere('unique_id',$request->beacon)->with(array('zone'))->first();
        if(empty($beaconObj)){
            return response()->json(['error' => array('message'=>'Error. invalid beacon')], 200);   
        }
        $attendanceObj = new Attendance;
        $attendanceObj->user_id = $user->id;
        $attendanceObj->beacon_id = $beaconObj->id;
        $attendanceObj->beacon_unique_id = $beaconObj->unique_id;
        $attendanceObj->zone_id = $beaconObj->zone->id;
        $attendanceObj->zone_name = $beaconObj->zone->name;
        $attendanceObj->shop_name = $beaconObj->zone->shop_name;
        $attendanceObj->floor = $beaconObj->zone->floor;
        $attendanceObj->start_time = getPakistanTime($request->start_time);
        $attendanceObj->end_time = getPakistanTime($request->end_time);
        $attendanceObj->save();
        return response()->json(['success' => array('message'=>'successfully saved')], 200);
        //Attendance
    }

    public function getAds(Request $request)
    {
        $beaconObj = Beacon::where('id',$request->beacon)->orWhere('unique_id',$request->beacon)->with(array('zone'))->first();
        if(empty($beaconObj)){
            return response()->json(['error' => array('message'=>'Error. invalid beacon')], 200);   
        }
        $ads = Ad::where('beacon_id',$beaconObj->id)->get();
        return response()->json(['success' => array('data'=>$ads)], 200);
    }
}