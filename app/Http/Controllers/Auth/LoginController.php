<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectAfterLogout = '/login';
    protected $redirectTo = '/login'; //RouteServiceProvider::HOME;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /*protected function credentials(Request $request)
    {
        return array_merge($request->only($this->username(), 'password'), ['type' => 'admin']);
    }*/

    protected function redirectTo()
    {
        if (auth()->user()->hasAnyRole(1)) {
            return '/adminDashboard';
        }if (auth()->user()->hasAnyRole(2,3,4)){
            return '/clientDashboard';
        }if (auth()->user()->hasAnyRole(5)){
            return '/shopDashboard';
        }
    }
}