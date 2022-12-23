<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\UserBuildingShop;
use App\Beacon;
use App\Building;
use App\Zone;
use App\Ad;
use App\User;
use Auth;
use DB;

class BuildingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:building-list|building-create|building-edit|building-delete|building-show', ['only' => ['index','store']]);
        $this->middleware('permission:building-show', ['only' => ['show']]);
        $this->middleware('permission:building-add', ['only' => ['create','store']]);
        $this->middleware('permission:building-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:building-delete', ['only' => ['destroy','delete']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {    
        $keyword = '';
        $buildingObj = new Building;
        $user = Auth::user();
        if($user->type!='admin'){
            $buidlings = array();
            if($user->hasAnyRole(array(2,3,4,5))){
                $UserBuildingShop = \App\UserBuildingShop::where('user_id',$user->id)->get();
                foreach($UserBuildingShop as $bul){
                    $buidlings[$bul->building_id] = $bul->building_id;
                }
            }
          $buildingObj = $buildingObj->whereIn('id',$buidlings); 
        }
        if($request->get('keyword')){
            $keyword = $request->get('keyword');
            $buildingObj = $buildingObj->where('name', 'like', "%$keyword%");
        }
        $buildings = $buildingObj->paginate(20);
        return view('Buildings.index',compact('buildings','keyword'));
    }


    public function create(){
        $userObj = new User;
        $userObj = $userObj->where(function($query) use($userObj){
            return $userObj->scopeRole($query,2);
        });
        $users = $userObj->get();

        return View('Buildings.create',compact('users'));
    }

    public function store(Request $request){
        $rules = array(
            'name' =>'required',
            'detail' =>'nullable',
            'address' =>'nullable',
            'user_id' =>'nullable'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } 
        
        $buildingObj = new Building;
        $data = $request->all();
        $buildingObj->name = $data['name'];
        $buildingObj->description = $data['detail'];
        $buildingObj->address = $data['address'];
        $buildingObj->user_id = $data['user_id'];
        $buildingObj->save();
        if(!empty($data['user_id'])){
            $newuserBuilding = new UserBuildingShop;
            $newuserBuilding->building_id = $buildingObj->id;
            $newuserBuilding->user_id = $data['user_id'];
            $newuserBuilding->save();
        }
        flash('Successfully Saved.','success');
        return redirect('/buildings');
    }

    public function show(Building $building){
        return View('Buildings.show',compact('building'));   
    }

    public function edit(Building $building){ 
        $userObj = new User;
        $userObj = $userObj->where(function($query) use($userObj){
            return $userObj->scopeRole($query,2);
        });
        $users = $userObj->get();
        return View('Buildings.edit',compact('building','users'));
    }

    public function update(Request $request,Building $building){
        $rules = array(
            'name' =>'required',
            'detail' =>'nullable',
            'address' =>'nullable',
            'user_id' =>'nullable'
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } 
        
        $data = $request->all();
        if($data['user_id'] != $building->user_id){
            UserBuildingShop::where('user_id',$building->user_id)->where('building_id',$building->id)->delete();
            $newuserBuilding = new UserBuildingShop;
            $newuserBuilding->building_id = $buildingObj->id;
            $newuserBuilding->user_id = $data['user_id'];
            $newuserBuilding->save();
        }
        $building->name = $data['name'];
        $building->description = $data['detail'];
        $building->address = $data['address'];
        $building->user_id = $data['user_id'];
        $building->save();
        
        flash('Successfully updated Building!','success');
        return redirect('/buildings');
    
    }

    public function delete(Building $building){
        if(!empty($building->id)){
            $building->delete();
            flash('Successfully deleted the Building!','success');
        }else{
            flash('Error in deleting. Please try again later','error');
        }
        return redirect('/buildings');
    }
}