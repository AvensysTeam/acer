<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\User;
use App\PCInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Stevebauman\Location\Facades\Location;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $countrycode = "AT,BE,BG,HR,CY,CZ,DK,EE,FI,FR,DE,GR,HU,IE,IT,LV,LT,LU,MT,NL,NO,PL,PT,RO,SK,SI,ES,SE,CH,GB";        
        
        if ($position = Location::get(request()->getClientIp())) {
            // Successfully retrieved position.
            if(isset($position) && !empty($position)) {
                if(!strpos($countrycode, $position->countryCode)) {
                    return back()->with('error', 'Invalid Location: You location is '.$position->countryName);
                }
            }
        }

        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
            'pc_info' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        $pc_info = $request->input('pc_info');
        $user = User::where('email', $credentials['email'])->first();
        if ($user && Hash::check($request->password, $user->password)) {
            if (!PCInfo::where('uid', $user->id)->where('info', $pc_info)->first()) {
                $newPC = new PCInfo();
                $newPC->uid = $user->id;
                $newPC->info = $pc_info;
                $newPC->is_verified = 0;
                $newPC->save();
            }

            if (!$user->hasVerifiedEmail()) {
                Session::put('redirect', "thanks");
            } else {            
                Session::put('redirect', "login");
            }

            Session::put('email', $user->email);
            Session::put('phone', $user->phone);
            Session::put('pc_info', $pc_info);
            
            Auth::login($user);
            
            return redirect()->route('otp.verify');
        }
        return back()->with('error', 'Invalid Email or Password');
    }
}
