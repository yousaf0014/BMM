<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Beacon;
use App\Zone;
use App\Ad;
use Auth;

class AdsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:ads-list|ads-add|ads-edit|ads-delete|ads-show', ['only' => ['index','store']]);
        $this->middleware('permission:ads-show', ['only' => ['show']]);
        $this->middleware('permission:ads-add', ['only' => ['create','store']]);
        $this->middleware('permission:ads-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:ads-delete', ['only' => ['destroy','delete']]);

        $this->middleware('permission:message-list|message-add|message-edit|message-delete|message-show', ['only' => ['index1','store1']]);
        $this->middleware('permission:message-show', ['only' => ['show1']]);
        $this->middleware('permission:message-add', ['only' => ['create1','store1']]);
        $this->middleware('permission:message-edit', ['only' => ['edit1','update1']]);
        $this->middleware('permission:message-delete', ['only' => ['destroy1','delete1']]);
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
        $zone = $keyword = '';
        $adObj = new Ad;
        $adObj = $adObj->where('type','ad');
        $user = Auth::user();
        if($user->hasAnyRole(5)){
            $zones = \App\UserBuildingShop::where('user_id',$user->id)->pluck('shop_id','shop_id');
            $adObj = $adObj->whereIn('zone_id',$zones);
        }
        $buidlingID = $request->session()->get('current_building');
        $adObj = $adObj->where('building_id',$buidlingID);
        if($request->get('keyword')){
            $keyword = $request->get('keyword');
            $adObj = $adObj->where('title', 'like', "%$keyword%");
        }
        if($request->get('zone')){
            $zone = $request->get('zone');
            $adObj = $adObj->where('zone_id',$zone);
        }
        $zonesObj = new Zone;
        if(Auth::user()->hasAnyRole(5) && $request->session()->has('current_building')){
            $zonesIds = \App\UserBuildingShop::where('user_id',$user->id)->pluck('shop_id','shop_id')->all();
            $zonesObj = $zonesObj->whereIn('id',$zonesIds);    
        }
        $zones = $zonesObj->get();
        $adObj = $adObj->with(array('zone','beacon'));
        $ads = $adObj->paginate(20);
        return view('Ads.index',compact('ads','zones','keyword','zone'));
    }


    public function create(Request $request){
        $zoneObj = new Zone;
        $user = Auth::user();
        if($user->hasAnyRole(5)){
            $zones = \App\UserBuildingShop::where('user_id',$user->id)->pluck('shop_id','shop_id');
            $zoneObj = $zoneObj->whereIn('id',$zones);
        }
        $buidlingID = $request->session()->get('current_building');
        $zoneObj = $zoneObj->where('building_id',$buidlingID);
        
        $zones =  $zoneObj->get();
        return View('Ads.create',compact('zones'));
    }

    
    public function store(Request $request){
        $rules = array(
            'zone_id' =>'required',
            'title' =>'required',
            'pic' =>'required',
            'beacon_id' =>'required'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } 
        
        $adObj = new Ad;
        $data = $request->all();
        $file = $request->file('pic');
        $filename = uniqid() . $file->getClientOriginalName();
        $file->move('uploads/', $filename);
        $adObj->url = 'uploads/'.$filename;
        $adObj->title = $data['title'];
        $adObj->zone_id = $data['zone_id'];
        $adObj->beacon_id = $data['beacon_id'];
        $adObj->save();
        flash('Successfully Saved.','success');
        return redirect('/ads');
    }

    public function show(Ad $ad){
        $zone = Zone::where('id',$ad->zone_id)->first();
        $beacon = Beacon::where('id',$ad->beacon_id)->first();
        return View('Ads.show',compact('ad','zone','beacon'));   
    }

    public function edit(Request $request,Ad $ad){ 
        $zoneObj = new Zone;
        $user = Auth::user();
        if($user->hasAnyRole(5)){
            $zones = \App\UserBuildingShop::where('user_id',$user->id)->pluck('shop_id','shop_id');
            $zoneObj = $zoneObj->whereIn('id',$zones);
        }
        $buidlingID = $request->session()->get('current_building');
        $zoneObj = $zoneObj->where('building_id',$buidlingID);
        
        $zones =  $zoneObj->get();
        $beacons = Beacon::where('zone_id',$ad->zone_id)->get();
        return View('Ads.edit',compact('zones','ad','beacons'));
    }

    public function update(Request $request,Ad $ad){
        $rules = array(
            'zone_id' =>'required',
            'title' =>'required',
            'pic' =>'nullable',
            'beacon_id' =>'required'
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } 
        
        $data = $request->all();
        if(!empty($data['pic'])){
            $file = $request->file('pic');
            $filename = uniqid() . $file->getClientOriginalName();
            $file->move('uploads/', $filename);
            $ad->url = 'uploads/'.$filename;
        }
        $ad->title = $data['title'];
        $ad->zone_id = $data['zone_id'];
        $ad->beacon_id = $data['beacon_id'];
        $ad->save();
        
        flash('Successfully updated Zone!','success');
        return redirect('/ads');
    
    }

    public function delete(Ad $ad){
        if(!empty($ad->id)){
            $ad->delete();
            flash('Successfully deleted the Ad!','success');
        }else{
            flash('Error in deleting. Please try again later','error');
        }
        return redirect('/ads');
    }



    public function index1(Request $request)
    {    
        $zone = $keyword = '';
        $adObj = new Ad;
        $user = Auth::user();
        $adObj = $adObj->where('type','message');
        if($user->hasAnyRole(5)){
            $zones = \App\UserBuildingShop::where('user_id',$user->id)->pluck('shop_id','shop_id');
            $adObj = $adObj->whereIn('zone_id',$zones);
        }
        $buidlingID = $request->session()->get('current_building');
        $adObj = $adObj->where('building_id',$buidlingID);
        if($request->get('keyword')){
            $keyword = $request->get('keyword');
            $adObj = $adObj->where('title', 'like', "%$keyword%");
        }
        if($request->get('zone')){
            $zone = $request->get('zone');
            $adObj = $adObj->where('zone_id',$zone);
        }
        $zonesObj = new Zone;
        if(Auth::user()->hasAnyRole(5) && $request->session()->has('current_building')){
            $zonesIds = \App\UserBuildingShop::where('user_id',$user->id)->pluck('shop_id','shop_id')->all();
            $zonesObj = $zonesObj->whereIn('id',$zonesIds);    
        }
        $zones = $zonesObj->get();
        $adObj = $adObj->with(array('zone','beacon'));
        $ads = $adObj->paginate(20);
        return view('Messages.index',compact('ads','zones','keyword','zone'));
    }


    public function create1(Request $request){
        $zoneObj = new Zone;
        $user = Auth::user();
        if($user->hasAnyRole(5)){
            $zones = \App\UserBuildingShop::where('user_id',$user->id)->pluck('shop_id','shop_id');
            $zoneObj = $zoneObj->whereIn('id',$zones);
        }
        $buidlingID = $request->session()->get('current_building');
        $zoneObj = $zoneObj->where('building_id',$buidlingID);
        
        $zones =  $zoneObj->get();
        return View('Messages.create',compact('zones'));
    }

    
    public function store1(Request $request){
        $rules = array(
            'zone_id' =>'required',
            'title' =>'required',
            'message' =>'required',
            'beacon_id' => 'required'        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } 
        
        $adObj = new Ad;
        $data = $request->all();
        $adObj->title = $data['title'];
        $adObj->zone_id = $data['zone_id'];
        $adObj->message = $data['message'];
        $adObj->type = "message";
        $adObj->beacon_id = $data['beacon_id'];
        $adObj->save();
        flash('Successfully Saved.','success');
        return redirect('/messages');
    }

    public function show1(Ad $ad){
        $zone = Zone::where('id',$ad->zone_id)->first();
        $beacon = Beacon::where('id',$ad->beacon_id)->first();
        return View('Messages.show',compact('ad','zone','beacon'));   
    }

    public function edit1(Request $request,Ad $ad){ 
        $zoneObj = new Zone;
        $user = Auth::user();
        if($user->hasAnyRole(5)){
            $zones = \App\UserBuildingShop::where('user_id',$user->id)->pluck('shop_id','shop_id');
            $zoneObj = $zoneObj->whereIn('id',$zones);
        }
        $buidlingID = $request->session()->get('current_building');
        $zoneObj = $zoneObj->where('building_id',$buidlingID);
        
        $zones =  $zoneObj->get();
        $beacons = Beacon::where('zone_id',$ad->zone_id)->get();
        return View('Messages.edit',compact('zones','ad','beacons'));
    }

    public function update1(Request $request,Ad $ad){
        $rules = array(
            'zone_id' =>'required',
            'title' =>'required',
            'message' =>'required',
            'beacon_id' =>'required'
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } 
        
        $data = $request->all();
        $ad->title = $data['title'];
        $ad->zone_id = $data['zone_id'];
        $ad->message = $data['message'];
        $ad->beacon_id = $data['beacon_id'];
        $ad->save();
        flash('Successfully updated Zone!','success');
        return redirect('/messages');
    
    }

    public function delete1(Ad $ad){
        if(!empty($ad->id)){
            $ad->delete();
            flash('Successfully deleted the Message!','success');
        }else{
            flash('Error in deleting. Please try again later','error');
        }
        return redirect('/messages');
    }

    public function getBeacons(Request $request){
        $data = $request->all();
        $beacons = Beacon::where('zone_id',$data['zone_id'])->get();
        $baconArr = array();
        foreach($beacons as $bc){
            $st = !empty($bc->selected) ? ' | Default':'';
            $baconArr[$bc->id]= $bc->unique_id .$st;
        }
        echo  json_encode($baconArr);
        exit;
    } 
}