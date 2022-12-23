<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use App\Questionnaire;
use App\Question;
use App\User;
use App\UserForm;
use App\Building;
use App\Attendance;
use App\UserBuildingShop;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        return view('home',compact('user'));
    }

    public function adminDashboard()
    {
        $user = Auth::user();
        $permissions = $user->getAllPermissions();

        //dd($permissions);


        $clients = User::whereHas(
            'roles', function($q){
                $q->where('id', 2);
            }
        )->get();
        return view('Home.admindashboard',compact('user','clients'));   
    }
    public function actAs(Request $request)
    {
        $user = Auth::user();
        $actAsUser = User::where('id',$request->user_id)->first();
        if($user->hasRole(1)){
            Auth::login($actAsUser);
        }
        $request->session()->put('whois',$user->id);
        if (Auth::user()->hasAnyRole(1)) {
            return redirect('/adminDashboard');
        }if (Auth::user()->hasAnyRole(2,3,4)){
            return redirect('/clientDashboard');
        }if (Auth::user()->hasAnyRole(5)){
            return redirect('/shopDashboard');
        }
        return back();
    }
    public function backToOrignal(Request $request){
        if($request->session()->has('whois')){
            $userID = $request->session()->pull('whois');
            $user = User::where('id',$userID)->first();
            Auth::login($user);
            if (Auth::user()->hasAnyRole(1)) {
                return redirect('/adminDashboard');
            }if (Auth::user()->hasAnyRole(2,3,4)){
                return redirect('/clientDashboard');
            }if (Auth::user()->hasAnyRole(5)){
                return redirect('/shopDashboard');
            }   
        }
        return back();   
    }

    function __checkredirect(){
        if (Auth::user()->hasAnyRole(1)) {
            return redirect('/adminDashboard');
        }if (Auth::user()->hasAnyRole(2,3,4)){
            return redirect('/clientDashboard');
        }if (Auth::user()->hasAnyRole(5)){
            return redirect('/shopDashboard');
        }
    }
    public function clientDashboard()
    {
        if (Auth::user()->hasAnyRole(1)) {
            return redirect('/adminDashboard');
        }
        $user = Auth::user();
        $otherBuildings = UserBuildingShop::where('user_id',$user->id)->get();
        $buildingIds = array();
        foreach($otherBuildings as $bil){
            $buildingIds[$bil->building_id] = $bil->building_id;
        }
        $buildings = Building::where('user_id',$user->id)->orWhereIn('id',$buildingIds)->get();
        return view('Home.clientdashboard',compact('user','buildings'));   
    }
    public function viewBuilding(Request $request){
        $user = Auth::user();
        $otherBuildings = UserBuildingShop::where('user_id',$user->id)->get();
        $buildingIds = array();
        foreach($otherBuildings as $bil){
            $buildingIds[$bil->building_id] = $bil->building_id;
        }
        if(empty($buildingIds[$request->building_id])){
            return back()->withErrors('Invalid Building');
        }
        $buidling = Building::where('user_id',$user->id)->where('id',$request->building_id)->first();
        $request->session()->put('current_building', $request->building_id);
        return redirect('/clientdashboardBuilding');
    }
    public function clientDashboardBuilding(Request $request)
    {
        $user = Auth::user();
        if(!$request->session()->has('current_building')){
            return redirect('/clientDashboard');
        }
        $buidlingID = $request->session()->get('current_building');
        $building = Building::where('user_id',$user->id)->where('id',$buidlingID)->first();
        
        return view('Home.clientdashboardBuilding',compact('user','building'));

    }

    public function attendanceAjax(Request $request){
        $buidlingID = $request->session()->get('current_building');
        $startTime = date('Y-m-d 00:00:00',strtotime("-1 month"));
        $endTime = date('Y-m-d H:i:s');
        $attendanceObj = new Attendance;
        if(!empty($request->startDate)){
            $startTime = date('Y-m-d H:i:s',strtotime($request->startDate));
        }
        if(!empty($request->endDate)){
            $endTime = date('Y-m-d H:i:s',strtotime($request->endDate));
        }

        $attendanceObj = $attendanceObj->Where(function($query) use($startTime,$endTime){
            $query = $query->orWhere(function($q) use ($startTime,$endTime){
                return $q->where('start_time','>=',$startTime)->Where('end_time','<=',$endTime);      
            })->orWhere(function($q) use ($startTime,$endTime){
                return $q->where('end_time','>=',$startTime)->Where('end_time','<=',$endTime);
            })->orWhere(function($q) use ($startTime,$endTime){
                return $q->where('start_time','<=',$startTime)->Where('end_time','>=',$endTime);
            });
        });

        //$attendance = $attendanceObj->where('building_id1',$buidlingID)->get();
        $attendance = $attendanceObj->where('building_id',1)->get();

        $userColor = array();
        foreach($attendance as $thisData){         
            $timeIn = $thisData->start_time;
            $timeOut = $thisData->timeout;
            //$name = trim($thisData->user);
            $name = $thisData->user->first_name.' '.$thisData->user->last_name;
            $userID = trim($thisData->user->id);
            $zoneName = $thisData->zone->name;                  
            $timestamp  = strtotime($timeIn);
            $outTime = strtotime($timeOut);
            if($outTime <= $timestamp){
                $timeOut = $timeIn;
            }
            $timeIn = getPakistanTime($timeIn);//date("Y/m/d h:i:s a", strtotime("+10 hours $timeIn"));
            $timeOut = getPakistanTime($timeOut);//date("Y/m/d h:i:s a", strtotime("+10 hours $timeOut"));
            $timeOut = $timeOut == false ? $timeIn:$timeOut;
            if(empty($userColor[$name])){
                $userColor[$name] = $this->__getColor(rand(1,200));
            }
            if(!empty($zoneName) && !empty($name) && !empty($timeIn) && !empty($timeOut)){
                $arr = array($zoneName,$name,$timeIn,$timeOut,$userColor[$name]);   
                $arr_result[]= $arr;                 
            }           
            
        }
        echo json_encode($arr_result);
        exit;

    }
    
    function __getColor($num){
        /*$hash = md5('color' . $num); // modify 'color' to get a different palette     
        $color = hexdec(substr($hash, 0, 2)).hexdec(substr($hash, 2, 2)).hexdec(substr($hash, 4, 2));
        return substr($color, 0, 6); */
        $num =0;
        $colorArr = array('FFA07A','FA8072','E9967A','F08080','CD5C5C','DC143C','B22222','FF0000','8B0000','FF7F50','FF6347','FF4500','FFD700','FFA500','FF8C00','FFFF00','7CFC00','7FFF00','32CD32','228B22','006400','ADFF2F','00FF7F','90EE90','8FBC8F','2E8B57','808000','556B2F','6B8E23','00FFFF','7FFFD4','66CDAA','40E0D0','20B2AA','5F9EA0','008080','B0E0E6','87CEEB','00BFFF','4682B4','0000FF','000080','6A5ACD','DA70D6','FF00FF','9932CC','8B008B','4B0082','FFB6C1','C71585','808080','778899','2F4F4F','000000','DEB887','DAA520','8B4513','800000','A52A2A');
        global $usedArr;
        do{
            $num = rand(0,58);
        }while(!empty($usedArr[$num]));
        $usedArr[$num] = 1;
        return $colorArr[$num];
    }

    public function shopDashboard(Request $request)
    {
        $user = Auth::user();
        if(Auth::user()->hasAnyRole(5) && !$request->session()->has('current_building')){
            $request->session()->put('current_building', $user->building_id);
        }
        
        return view('Home.shopdashboard',compact('user'));
    }

    public function getZones(Request $request){
        $auser = Auth::user();
        $building = UserBuildingShop::where('user_id',$auser->id)->where('building_id',$request->building)->pluck('building_id','building_id')->all();
        if(empty($building)){
            echo 'error. In valid Access';
            exit;
        }
        $userShops = UserBuildingShop::where('user_id',$request->user_id)->pluck('shop_id','shop_id')->all();
        $zones = \App\Zone::where('building_id',$request->building)->get();
        $html = '';
        foreach($zones as $zone){
            $selected = in_array($zone->id,$userShops) ? ' selected="selected" ':'';
            $html .= '<option '.$selected.' value="'.$zone->id.'" >'.$zone->name.' | '.$zone->shop_name.' | '.$zone->shop.'</option>';
        }
        echo $html;
        exit;
    }
}