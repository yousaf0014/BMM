<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Beacon;
use App\Zone;
use DB;
use Auth;

class ZonesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:zone-list|zone-create|zone-edit|zone-delete|zone-show', ['only' => ['index','store']]);
        $this->middleware('permission:zone-show', ['only' => ['show']]);
        $this->middleware('permission:zone-add', ['only' => ['create','store']]);
        $this->middleware('permission:zone-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:zone-delete', ['only' => ['destroy','delete']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {    
        if(!$request->session()->has('current_building')){
            return redirect('/clientDashboard');
        }
        $user = Auth::user();
        $keyword = '';
        $zoneObj = new Zone;
        $buidlingID = $request->session()->get('current_building');
        $zoneObj = $zoneObj->where('building_id',$buidlingID);
        if(Auth::user()->hasAnyRole(5) && $request->session()->has('current_building')){
            $zones = \App\UserBuildingShop::where('user_id',$user->id)->pluck('shop_id','shop_id')->all();
            $zoneObj = $zoneObj->whereIn('id',$zones);    
        }
        if($request->get('keyword')){
            $keyword = $request->get('keyword');
            $zoneObj = $zoneObj->where(function ($query) use ($keyword) {
                                $query->where('name', 'like', "%$keyword%")
                                    ->orWhere('floor_name', 'like',  "%$keyword%")
                                    ->orWhere('floor', '=',  $keyword)
                                    ->orWhere('shop_name', 'like',  "%$keyword%")
                                    ->orWhere('shop', '=',  $keyword);
            });
        }
        $zoneObj = $zoneObj->with(array('beacon'));
        $zones = $zoneObj->paginate(20);
        return view('Zones.index',compact('zones','keyword'));
    }


    public function create(){
        $beacons = Beacon::where('user_id',Auth::user()->id)->where(function ($query){
            return $query->whereNull('zone_id')->orWhere('zone_id',0);
        })->get();
        return View('Zones.create',compact('beacons'));
    }

    
    public function store(Request $request){
        $rules = array(
            'name' =>'required',
            'beacon_ids' =>'required',
            'floor' =>'required',
            'floor_name'=>'nullable',
            'shop'=>'required',
            'shop_name'=>'required',
            'checkin' => 'required',
            'deafult_beacon' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }
        
        $data = $request->all();
        
        $zoneObj = new Zone;
        $beacons = $data['beacon_ids'];

        $data['user_id'] = Auth::user()->id;
        $id = $zoneObj->create($data)->id;
        
        $beaconObj = Beacon::where('id',$beacons)->update(array('zone_id'=>$id));

        $beacon = Beacon::where('id',$data['checkin'])->first();
        $beacon->checked_in = 1;
        $beacon->zone_id = $id;
        $beacon->save();

        $defaultBeacon = Beacon::where('id',$data['deafult_beacon'])->first();
        $defaultBeacon->selected = 1;
        $defaultBeacon->zone_id = $id;
        $defaultBeacon->save();


        flash('Successfully Saved.','success');
        return redirect('/zones');
    }

    public function show(Zone $zone){
        return View('Zones.show',compact('zone'));   
    }

    public function edit(Zone $zone){ 
        $beacons = Beacon::where('user_id',Auth::user()->id)->where('zone_id',array(Null,0))->get();
        $selectedBeacons = $zone->beacon()->get();
        return View('Zones.edit',compact('zone','beacons','selectedBeacons'));
    }

    public function update(Request $request,Zone $zone){
        $rules = array(
            'name' =>'required',
            'beacon_ids' =>'required',
            'floor' =>'required',
            'floor_name'=>'nullable',
            'shop'=>'required',
            'shop_name'=>'required',
            'checkin' => 'required',
            'deafult_beacon' => 'required'
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } 
        
        $beaconObj = Beacon::where('zone_id',$zone->id)->update(array('zone_id'=>0,'selected'=>0,'checked_in'=>0));

        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $zone->update($data);
        $beacons = $data['beacon_ids'];
        $beaconObj = Beacon::where('id',$beacons)->update(array('zone_id'=>$zone->id));

        $beacon = Beacon::where('id',$data['checkin'])->first();
        $beacon->checked_in = 1;
        $beacon->zone_id = $zone->id;
        $beacon->save();

        $defaultBeacon = Beacon::where('id',$data['deafult_beacon'])->first();
        $defaultBeacon->selected = 1;
        $defaultBeacon->zone_id = $zone->id;
        $defaultBeacon->save();


        flash('Successfully updated Zone!','success');
        return redirect('/zones');
    
    }

    public function delete(Zone $zone){
        if(!empty($zone->id) && $zone->user_id == Auth::user()->id){
            $zone->delete();
            flash('Successfully deleted the Zone!','success');
        }else{
            flash('Error in deleting. Please try again later','error');
        }
        return redirect('/zones');
    }  
}
