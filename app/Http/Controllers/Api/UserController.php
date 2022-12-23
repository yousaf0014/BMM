<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Events\Verified;
use Validator;
use App\User;
use Illuminate\Validation\Rule;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController 
{
    use VerifiesEmails;
    

    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request)
    {
        $user2 = User::where('email',$request->email)->withTrashed()->first();
        if(!empty($user2) && !empty($user2->deleted_at)){
            return $this->sendError('Validation Error.', array('email'=>'Your account is locked. Please contact support to get it unlocked.'));
        }

        $validator = Validator::make($request->all(), [
            'security'=> ["required" , "max:44","min:44"],
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users|string',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $str = $request->security;
        $const = "yYg)OyfK127X9GpSlepkuJmy7c7f7rBRET7T$053GzoL";
        if(strcmp($str, $const) !== 0 ){
            return $this->sendError('Validation Error.', array('security'=>'The selected security key is invalid.'));
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['type'] = 'user';
        $input['verify_code'] = Str::random(8);
        $user = User::create($input);
        $user->sendApiEmailVerificationNotification();
        $success = $this->__userfields($user);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['message'] = 'Please confirm yourself by clicking on verify user button sent to you on your email';
        return $this->sendResponse($success, 200);
    }

    function __userfields($user){
        $userArr['id'] = $user->id;
        $userArr['first_name'] = $user->first_name; 
        $userArr['last_name'] = $user->last_name; 
        $userArr['email'] = $user->email;
        $userArr['verified'] = !empty($user->email_verified_at) ? true:false;
        $userArr['login_type'] = $user->type;
        return $userArr;
    }


    public function sendPasswordRestEmail(Request $request)
    {
        $rules = array(
            'security'=> ["required" , "max:44","min:44"],
            'email' => 'required|email'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {            
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $str = $request->security;
        $const = "yYg)OyfK127X9GpSlepkuJmy7c7f7rBRET7T$053GzoL";
        if(strcmp($str, $const) !== 0 ){
            return $this->sendError('Validation Error.', array('security'=>'The selected mobile key is invalid.'));
        }

        $user = User::where('email', '=', $request->email)->first();//Check if the user exists
        if (empty($user->id)) {
            return $this->sendError('Validation Error.', array('Email'=>'Email does not exist'));
        }


        DB::table('password_resets')->where('email',$request->email)->delete();
        //Create Password Reset Token
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => Str::random(60)
        ]);//Get the token just created above
        $tokenData = DB::table('password_resets')
            ->where('email', $request->email)->first();
        $user->sendResetEmail($tokenData->token);
        $success['message'] =  'please check your email';
        return $this->sendResponse($success, 200);
    }

    public function updatePassword(Request $request){    
        $rules = array(
            'security'=> ["required" , "max:44","min:44"],
            'token' => ['required'],
            'email' => ['required'],
            'password' => ['nullable','string', 'min:8']
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {            
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $str = $request->security;
        $const = "yYg)OyfK127X9GpSlepkuJmy7c7f7rBRET7T$053GzoL";
        if(strcmp($str, $const) !== 0 ){
            return $this->sendError('Validation Error.', array('security'=>'The selected mobile key is invalid.'));
        }
        $user = User::where('email', '=', $request->email)->first();
        if (empty($user->id)) {
            return $this->sendError('Validation Error.', array('Email'=>'Email does not exist'));
        }

        $userPasswordRest = DB::table('password_resets')->where('email', '=', $request->email)->where('token',$request->token)->first();
        if (empty($userPasswordRest->email)) {
            return $this->sendError('Validation Error.', array('Token'=>'Invalid token provided'));
        }     
        $data = $request->all();                    
        $user->password = bcrypt($request->password);
        $user->save();
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user(); 
            $success = $this->__userfields($user);
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            return $this->sendResponse($success, 'User login successfully.');
        }
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }


    public function login(Request $request)
    {
        $user2 = User::where('email',$request->email)->withTrashed()->first();
        if(!empty($user2) && !empty($user2->deleted_at)){
            return $this->sendError('Validation Error.', array('email'=>'Your account is locked. Please contact support to get it unlocked.'));
        }

        $validator = Validator::make($request->all(), [
            'security'=> ["required" , "max:44","min:44"],
            'email' => 'required',
            'password' => 'required',            
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $str = $request->security;
        $const = "yYg)OyfK127X9GpSlepkuJmy7c7f7rBRET7T$053GzoL";
        if(strcmp($str, $const) !== 0 ){
            return $this->sendError('Validation Error.', array('security'=>'The selected mobile key is invalid.'));
        }
        
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user(); 
            $success = $this->__userfields($user);
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            return $this->sendResponse($success, 'User login successfully.');
        }
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }

    //user data
    function userData(){
        $user = Auth::user();
        $success = $this->__userfields($user);
        return $this->sendResponse($success, 200);
    }
    
        /** 
     * Update Email api 
     * 
     * @return \Illuminate\Http\Response 
    **/ 
    public function registerEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users|string'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user = Auth::user();
        $user->email = $request->email;
        $user->verify_code = Str::random(8);
        $user->sendApiEmailVerificationNotification();
        $success = $this->__userfields($user);
        $success['message'] = 'Please confirm yourself by clicking on verify user button sent to you on your email';
        return $this->sendResponse($success, 200);
    }
    

    /** 
     * Register api for social
     * 
     * @return \Illuminate\Http\Response 
     */
    public function socailRegister(Request $request)
    {
        $input = $request->all();
        $user2 = User::where('email',$request->email)->withTrashed()->first();
        if(!empty($user2) && !empty($user2->deleted_at)){
            return $this->sendError('Validation Error.', array('email'=>'Your account is locked. Please contact support to get it unlocked.'));
        }

        $user1 = User::where('email',$input['email'])->whereNull('deleted_at')->first();
        if(empty($user1)){
            $validator = Validator::make($request->all(), [
                'first_name' => "required",
                'last_name' => "required",
                'login_type'=> ["required",Rule::in(['google', 'facebook','apple'])],
                'token_for_business' => ['required','unique:users'],
                'security'=> ["required" , "max:44","min:44"],
                'email' => ['email','nullable'],
                'password' =>'nullable'
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());
            }    
        }
        
        $str = $request->security;
        $const = "yYg)OyfK127X9GpSlepkuJmy7c7f7rBRET7T$053GzoL";
        if(strcmp($str, $const) !== 0 ){
            return $this->sendError('Validation Error.', array('security'=>'The selected mobile key is invalid.'));
        }
        if(empty($user1)){
            $input['last_name'] =  $input['last_name'] == 'undefined' ? '':$input['last_name'];
            $input['email_verified_at'] = date('Y-m-d H:i:s');
            $user = User::create($input);
            //$user->sendApiEmailVerificationNotification();
            $success = $this->__userfields($user);
            $success['token'] =  $user->createToken('MyApp')->accessToken;
        
            $success['message'] = 'Signup Successfully';
            return $this->sendResponse($success, 200);
            
        }
        $success = $this->__userfields($user1);
        $success['token'] =  $user1->createToken('MyApp')->accessToken;
        $success['message'] = 'User login successfully.';
        return $this->sendResponse($success, 200);
        
    }

    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], 200); 
    }

    //email verification
    public function verify(Request $request) {
        $user = Auth::user();
        if($user->verify_code == $request->code){
            $date = date("Y-m-d g:i:s");
            $user->email_verified_at = $date;
            $user->save();
            return response()->json(['success' => array('message'=>'Email verified!')], 200);    
        }
        return $this->sendError('Invalid.', ['error'=>'Invalid Code']);       
    }
	

    //change Password
    public function changePassword(Request $request){    
        $rules = array(
            'password' => ['required','string', 'min:8'],
            'c_password' => 'required|same:password',
            'old_password' => ['required','string', 'min:8']
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {            
            return $this->sendError('Validation Error.', $validator->errors());
        }
        
        $user = Auth::user();
        if (!Hash::check($request->old_password, $user->password)){
            return $this->sendError('Validation Error.', array('old_password'=>'Invalid old password'));
        }

        $user->password = bcrypt($request->password);
        $user->save();


        $success = $this->__userfields($user);
        return $this->sendResponse($success, 'Password changed successfully.');
    }
}