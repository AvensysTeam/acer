<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\PCInfo;
use App\Permission;
use App\Providers\RouteServiceProvider;
use App\Role;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Stevebauman\Location\Facades\Location;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    public function showRegistrationForm() {
        $roleObject = new Role();
        $roles = $roleObject->getOtherRoles();
        return view('auth.register', compact('roles'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $countrycode = "AT,BE,BG,HR,CY,CZ,DK,EE,FI,FR,DE,GR,HU,IE,IT,LV,LT,LU,MT,NL,NO,PL,PT,RO,SK,SI,ES,SE,CH,GB";
        
        if ($position = Location::get(request()->getClientIp())) {
            // Successfully retrieved position.
            if(isset($position) && !empty($position)) {
                if(!strpos($countrycode, $position->countryCode)) {              
                    return Validator::make($data, [
                        'ip_address'     => ['required']
                    ], [
                        'required' => 'IP address is not valid. Your location is ' . $position->countryName
                    ]);
                }
            }
        }

        $messages = [
            'mobile_phone'    => 'The :attribute should be an European Country Number',
        ];

        return Validator::make($data, [
            'company_name'          => ['required', 'string', 'max:255'],
            'VAT'                   => ['required', 'string', 'max:255'],
            'contact_person_name'   => ['required', 'string', 'max:255'],
            'legal_address'         => ['required', 'string', 'max:255'],
            // 'position'              => ['required', 'string', 'max:255'],
            'username'              => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile_phone'          => ['required', 'string', 'min:10', 'max:16', "phone:{$countrycode}"],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            // 'captcha'  => ['required', 'string', 'min:1'],
        ], $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name'     => $data['username'],
            'email'    => $data['email'],
            'phone'    => $data['mobile_phone'],
            'company_name'    => $data['company_name'],
            'company_vat'    => $data['VAT'],
            'company_address' => $data['legal_address'],
            'pc_info' => 'required|string',
            'legal_form' => $data['legal_form'],
            'sector_activity' => $data['sector_activity'],
            'company_size' => $data['company_size'],
            'operational_address' => $data['operational_address'],
            'contact_person_name' => $data['contact_person_name'],
            'position' => $data['position'],
            'password' => Hash::make($data['password']),
        ]);

        $isDefaultRole = Role::with('permissions')->where('title', 'default')->first();
        if($isDefaultRole){
            // $newRole = $isDefaultRole->replicate();
            // $newRole->title = $data['position'];
            // $newRole->save(); 
            // if($newRole) {
            //     $user->roles()->sync([$newRole->id]);
            //     $newRole->permissions()->sync($isDefaultRole->permissions->pluck('id'));   
            // }
            $user->roles()->sync([$isDefaultRole->id]);
        }
        return $user;
    }

    // protected function registered(Request $request, $user)
    // {
    //     Auth::logout();
    //     return redirect('/login');
    // }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        if ($response = $this->registered($request, $user)) {
            return $response;
        }
    }

    protected function registered(Request $request, $user)
    {
        $pc_info = $request->input('pc_info');
        $newPC = new PCInfo();
        $newPC->uid = $user->id;
        $newPC->info = $pc_info;
        $newPC->is_verified = 0;
        $newPC->save();
        Session::put('phone', $user->phone);
        Session::put('redirect', "thanks");
        Session::put('email', $user->email);
        Session::put('pc_info', $pc_info);
        Auth::login($user);
        return redirect()->route('otp.verify');
    }

    public function showProfilePg() {
        return view('auth.profile');
    }

    public function addProfile(Request $request) {

    }
}
