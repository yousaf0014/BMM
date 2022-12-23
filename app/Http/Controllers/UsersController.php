<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http;
use App\UserBuildingShop;
use App\Building;
use App\User;
use App\Zone;
use Auth;
use DB;
use Hash;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)

    {
        $userObj = new User;
        $cuser = Auth::user();
        $keyword = '';
        if ($cuser->hasAnyRole(2,3,4)){
            if(!$request->session()->has('current_building')){
                return redirect('/clientDashboard');
            }
            $currentBuilding = $request->session()->get('current_building');
            $userObj = $userObj->where('building_id',$currentBuilding);
        }
        if($request->get('keyword')){
            $keyword = $request->get('keyword');
            $userObj = $userObj->where(function($query) use($keyword) {
                return $query->where('first_name', 'like', "%$keyword%")->orWHere('first_name', 'like', "%$keyword%");
            });
        }
        $users = $userObj->paginate(20);
        return view('Users.index',compact('users','keyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */ //whereNotIn
    public function create()
    {
        $roleObj = new Role;
        $user = Auth::user();
        $roleIds = getAllowedRegistrationType();
        if(!empty($roleIds)){
            $roleObj =  $roleObj->whereIn('id',$roleIds);
        }
        $roles = $roleObj->get();
        $buildings = getBuildings();
        return view('Users.create',compact('roles','buildings'));
    }
    public function assignshop(Request $request,User $user){
        if(!$request->session()->has('current_building')){
            return redirect('/clientDashboard');
        }
        $buildings = getBuildings();
        //$buildings = UserBuildingShop::where('user_id',$user->id)->pluck('building_id','building_id')->all();
        $userRoles = $user->getRoleNames();
        $userBuilding = $request->session()->get('current_building');
        $zones = Zone::where('building_id',$userBuilding)->get();
        $currentShops = array();
        $currentShops = UserBuildingShop::where('user_id',$user->id)->pluck('shop_id','shop_id')->all();
        return view('Users.shop',compact('buildings','userBuilding','user','zones','currentShops'));
    }

    public function storeShop(Request $request,User $user){
        if(!$request->session()->has('current_building')){
            return redirect('/clientDashboard');
        }
        $zones = $request->zones;
        $auser = Auth::user();
        $building = UserBuildingShop::where('user_id',$auser->id)->where('building_id',$request->building)->pluck('building_id','building_id')->all();
        if(empty($building)){
            return back()->with('error','Invalid building');
        }
        $zones = Zone::where('building_id',$request->building)->whereIn('id',$request->zones)->get();
        UserBuildingShop::where('user_id',$user->id)->delete();
        foreach($zones as $zone){
            $userBuild = new UserBuildingShop;
            $userBuild->building_id = $request->building;
            $userBuild->shop_id = $zone->id;
            $userBuild->user_id = $user->id;
            $userBuild->save();
        }
        return back()->with('success','successfully saved');
    }

    public function assignBuildings(Request $request,User $user){
        $buildings = getBuildings();
        $currentBuildings = UserBuildingShop::where('user_id',$user->id)->pluck('building_id','building_id')->all();
        $userRoles = $user->getRoleNames();
        $userBuilding = $request->session()->get('building_id','');
        return view('Users.building',compact('buildings','currentBuildings','userBuilding','user'));
    }

    public function storeBuilding(Request $request,User $user)
    {
        UserBuildingShop::where('user_id',$user->id)->delete();
        $buildings = $request->buildings;
        foreach($buildings as $build){
            $building = Building::where('id',$build)->first();
            if(empty($building->user_id)){
                $building->user_id = $user->id;
                $building->save();
            }
            $userBuild = new UserBuildingShop;
            $userBuild->building_id = $build;
            $userBuild->user_id = $user->id;
            $userBuild->save();
        }
        return redirect('users')
                        ->with('success','User building Assigned Successfully!');

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'contact' =>'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        unset($input['assignbuilding']);
        unset($input['assignshop']);
        $user = User::create($input);
        $user->assignRole($request->input('roles'));
        if(!empty($request->assignbuilding)){
            return redirect('users/'.$user->id.'assignBuildings/')
                        ->with('success','User created successfully');
        }else if(!empty($request->assignshop)){
            return redirect('users/'.$user->id.'assignshop/')
                        ->with('success','User created successfully');
        }
        return redirect('users')
                        ->with('success','User created successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show(User $user)
    {
        return view('Users.show',compact('user'));
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit(User $user)
    {
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        return view('Users.edit',compact('user','roles','userRole'));
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'contact' =>'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            unset($input['password']);    
        }

        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$user->id)->delete();
        $user->assignRole($request->input('roles'));
        return redirect('users')
                        ->with('success','User updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(User $user)
    {
        $user->delete();
        return redirect('users')
                        ->with('success','User deleted successfully');

    }
}