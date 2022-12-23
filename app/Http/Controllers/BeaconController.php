<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Beacon;
use App\Zone;
use Auth;


class BeaconController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:beacon-list|beacon-add|beacon-edit|beacon-delete|beacon-show', ['only' => ['index','store']]);
        $this->middleware('permission:beacon-show', ['only' => ['show']]);
        $this->middleware('permission:beacon-add', ['only' => ['create','store']]);
        $this->middleware('permission:beacon-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:beacon-delete', ['only' => ['destroy','delete']]);
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
        $beaconObj = new Beacon;
        $buidlingID = $request->session()->get('current_building');
        $beaconObj = $beaconObj->where('building_id',$buidlingID);
        if(Auth::user()->hasAnyRole(5) && $request->session()->has('current_building')){
            $zones = \App\UserBuildingShop::where('user_id',$user->id)->pluck('shop_id','shop_id')->all();
            $beaconObj = $beaconObj->whereIn('zone_id',$zones);    
        }
        if($request->get('keyword')){
            $keyword = $request->get('keyword');
            $beaconObj = $beaconObj->Where('unique_id', 'like', '%'.$keyword.'%');
        }
        $beaconObj = $beaconObj->with(array('zone'));
        $beacons = $beaconObj->paginate(20);
        return view('Beacons.index',compact('beacons','keyword'));
    }


    public function create(Request $request){
        $user  = Auth::user();
        $zones = array();
        $role = Auth::user()->hasAnyRole(5) ? 'shop' : ''; 
        if(Auth::user()->hasAnyRole(5) && $request->session()->has('current_building')){
            $zonesIds = \App\UserBuildingShop::where('user_id',$user->id)->pluck('shop_id','shop_id')->all();
            $zones = Zone::whereIn('id',$zonesIds)->get();    
        }
        return View('Beacons.create',compact('role','zones'));
    }

    
    public function store(Request $request){
        $rules = array(
            'unique_id' =>'required',
            'minor' =>'required',
            'major' =>'required',
            'ranging'=>'nullable',
            'uuid'=>'required',
            'mac_address'=>'required'
        );
        if(Auth::user()->hasAnyRole(5)){
            $rules['zone_id'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } 
        
        $beaconObj = new Beacon;
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $beaconObj->create($data);
        flash('Successfully Saved.','success');
        return redirect('/beacons');
        
    }

    public function show(Beacon $beacon){
        return View('Beacons.show',compact('beacon'));   
    }

    public function edit(Request $request,Beacon $beacon){ 
        $user  = Auth::user();
        $zones = array();
        $role = Auth::user()->hasAnyRole(5) ? 'shop' : ''; 
        if(Auth::user()->hasAnyRole(5) && $request->session()->has('current_building')){
            $zonesIds = \App\UserBuildingShop::where('user_id',$user->id)->pluck('shop_id','shop_id')->all();
            $zones = Zone::whereIn('id',$zonesIds)->get();    
        }
        return View('Beacons.edit',compact('beacon','role','zones'));
    }

    public function update(Request $request,Beacon $beacon){
        $rules = array(
            'unique_id' =>'required',
            'minor' =>'required',
            'major' =>'required',
            'ranging'=>'nullable',
            'uuid'=>'required',
            'mac_address'=>'required'
        );
        if(Auth::user()->hasAnyRole(5)){
            $rules['zone_id'] = 'required';
        }
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } 
        
    
        $data = $request->all();
        $data['user_id'] = Auth::user()->id;
        $beacon->update($data);

        flash('Successfully updated Beacon!','success');
        return redirect('/beacons');
    
    }

    public function delete(Beacon $beacon){
        if(!empty($beacon->id) && $beacon->user_id == Auth::user()->id){
            $beacon->delete();
            flash('Successfully deleted the Beacon!','success');
        }else{
            flash('Error in deleting. Please try again later','error');
        }
        return redirect('/beacons');
    }

    
    public function active(Beacon $beacon, $action) {
        $beacon->status = $action;
        $beacon->save();
        if($action){
            flash('Beacon successfully updated!','success');
        }else{
            flash('Beacon successfully deactive!','success');
        }
        return back();    
    }    
}
