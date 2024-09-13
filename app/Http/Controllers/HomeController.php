<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\PCInfo;
use App\Qrcode;
use App\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Stevebauman\Location\Facades\Location;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return "hello";
    }


    public function appLogin(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
    
        // Find the user by email
        $user = User::where('email', $email)->first();
        $origin = $request->header('Origin');
    
        // If a user with the email was found
        if($user){
            // Check the password
            if(Hash::check($password, $user->password)){
                
                // Password match
                
                return response()->json(['message' => 'success'])
                ->header('Access-Control-Allow-Origin', $origin) // Dynamic origin
                ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
                ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin, Authorization')
                ->header('Access-Control-Allow-Credentials', 'true');
            }
        }
    
        // No user was found or password did not match
        return response()->json(['message' => 'Invalid Email or Password'])
        ->header('Access-Control-Allow-Origin', $origin) // Dynamic origin
        ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
        ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin, Authorization')
        ->header('Access-Control-Allow-Credentials', 'true');
    }
    public function appAutoLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');
        $origin = $request->header('Origin');

        // Find the user by email
        $user = User::where('email', $email)->first();

        // If a user with the email was found
        if ($user && Hash::check($password, $user->password)) {
            // Password match
            Session::put('email', $user->email);
            Session::put('redirect', "login");
            Session::put('phone', $user->phone);
            Auth::login($user);

            return redirect()->route('otp.verify');
        }

        // No user was found or password did not match
        return response()->json(['message' => 'Invalid Email or Password'])
            ->header('Access-Control-Allow-Origin', $origin) // Dynamic origin
            ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin, Authorization')
            ->header('Access-Control-Allow-Credentials', 'true');
    }

    public function appRegister(Request $request)
    {
        $username = $request->input('username');
        $phonenumber = $request->input('phoneNumber');
        $email = $request->input('email');
        $password = $request->input('password');
        $origin = $request->header('Origin');

        try {
            // Create a new user
            $user = new User;
            $user->name = $username;
            $user->phone = $phonenumber;
            $user->email = $email;
            $user->password = Hash::make($password); // Hash the password

            // Save the user to the database
            $user->save();

            $isDefaultRole = Role::with('permissions')->where('title', 'default')->first();
            if($isDefaultRole){
                $newRole = $isDefaultRole->replicate();
                $newRole->title = $username;
                $newRole->save(); 
                if($newRole) {
                    $user->roles()->sync([$newRole->id]);
                    $newRole->permissions()->sync($isDefaultRole->permissions->pluck('id'));   
                }
            }
            return response()->json(['message' => 'success'])
            ->header('Access-Control-Allow-Origin', $origin)
            ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin, Authorization')
            ->header('Access-Control-Allow-Credentials', 'true');
        } catch (\Exception $e) {
            // If something went wrong, return an error message
            return response()->json(['message' => 'Registration Error'])
            ->header('Access-Control-Allow-Origin', $origin)
            ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin, Authorization')
            ->header('Access-Control-Allow-Credentials', 'true');
        }
    }

    public function appQrcode1(Request $request)
{
    // Validate request
    $request->validate([
        'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'size' => 'nullable|string',
        'thumb' => 'nullable|string',
        'old' => 'nullable|string',
    ]);

    $file = $request->file('file');
    $location = storage_path('app/public/upload');

    // Create directory if it does not exist
    if (!file_exists($location)) {
        mkdir($location, 0755, true);
    }

    // Remove old files if needed
    if ($request->old) {
        $oldFile = $location . '/' . $request->old;
        $oldThumb = $location . '/thumb_' . $request->old;
        
        if (file_exists($oldFile)) {
            unlink($oldFile);
        }
        if (file_exists($oldThumb)) {
            unlink($oldThumb);
        }
    }

    // Generate unique filename
    $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
    $image = Image::make($file);

    // Resize if size is provided
    if ($request->size) {
        $size = explode('x', strtolower($request->size));
        $image->resize($size[0], $size[1]);
    }

    // Save the main image
    $image->save($location . '/' . $filename);

    // Create thumbnail if needed
    if ($request->thumb) {
        $thumbSize = explode('x', $request->thumb);
        Image::make($file)
            ->resize($thumbSize[0], $thumbSize[1])
            ->save($location . '/thumb_' . $filename);
    }

    return response()->json(['filename' => $filename]);
}

    public function appQrcode(Request $request) 
    {
        $origin = $request->header('Origin');
        $email = $request->input('email');
        $device_id = $request->input('device_id');
        $serial_id = $request->input('serial_id');
        $firmware_version = $request->input('firmware_version');
        $software_version = $request->input('software_version');
        $hardware_version = $request->input('hardware_version');
        $kts = $request->input('kts');
        $counter = $request->input('counter');
        $probes = $request->input('probes');
        $accessoryHW0 = $request->input('accessoryHW0');
        $accessoryHW1 = $request->input('accessoryHW1');
        $accessoryHW2 = $request->input('accessoryHW2');
        $accessoryHW3 = $request->input('accessoryHW3');
        $motdep = $request->input('motdep');
        $alarm0 = $request->input('alarm0');
        $alarm1 = $request->input('alarm1');
        $alarm2 = $request->input('alarm2');
        $alarm3 = $request->input('alarm3');
        $alarm4 = $request->input('alarm4');
        $alarm5 = $request->input('alarm5');
        $alarm6 = $request->input('alarm6');
        $alarm7 = $request->input('alarm7');
        $alarm8 = $request->input('alarm8');
        $alarm9 = $request->input('alarm9');
        $alarm10 = $request->input('alarm10');
        $alarm11 = $request->input('alarm11');
        $alarm12 = $request->input('alarm12');
        $timestamp = $request->input('timestamp');
        $projectname = $request->input('projectname');
        $remarks = $request->input('remarks');
        $file = $request->hasFile('file');
    
        try {
            // Create a new QRCode entry
            $qrcode = new Qrcode;
    
            $qrcode->email = $email;
            $qrcode->device_id = $device_id;
            $qrcode->serial_id = $serial_id;
            $qrcode->firmware_version = $firmware_version;
            $qrcode->software_version = $software_version;
            $qrcode->hardware_version = $hardware_version;
            $qrcode->kts = $kts;
            $qrcode->counter = $counter;
            $qrcode->probes = $probes;
            $qrcode->accessoryHW0 = $accessoryHW0;
            $qrcode->accessoryHW1 = $accessoryHW1;
            $qrcode->accessoryHW2 = $accessoryHW2;
            $qrcode->accessoryHW3 = $accessoryHW3;
            $qrcode->motdep = $motdep;
            $qrcode->alarm0 = $alarm0;
            $qrcode->alarm1 = $alarm1;
            $qrcode->alarm2 = $alarm2;
            $qrcode->alarm3 = $alarm3;
            $qrcode->alarm4 = $alarm4;
            $qrcode->alarm5 = $alarm5;
            $qrcode->alarm6 = $alarm6;
            $qrcode->alarm7 = $alarm7;
            $qrcode->alarm8 = $alarm8;
            $qrcode->alarm9 = $alarm9;
            $qrcode->alarm10 = $alarm10;
            $qrcode->alarm11 = $alarm11;
            $qrcode->alarm12 = $alarm12;
            $qrcode->timestamp = $timestamp;
            $qrcode->projectname = $projectname;
            $qrcode->remarks = $remarks;
          //  $qrcode->picture = $file; // Store image path only
          if ($request->hasFile('file')) {
            // Retrieve the uploaded file
            $file = $request->file('file');
            
            // Generate a unique filename with extension
            $filename = uniqid() . time() . '.' . $file->getClientOriginalExtension();
            
            // Store the file in the 'public/uploads' directory and get the path
            $path = $file->storeAs('uploads', $filename, 'public');
            
            // Save the path to the qrcode picture attribute
            $qrcode->picture = $filename;
        }
            $qrcode->save();
    
            return response()->json(['message' =>'success'])
                ->header('Access-Control-Allow-Origin', $origin)
                ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
                ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin, Authorization')
                ->header('Access-Control-Allow-Credentials', 'true');
        } catch (\Exception $e) {
            // If something went wrong, return an error message
            return response()->json(['message' => 'QR data storing error', 'error' => $e->getMessage()])
                ->header('Access-Control-Allow-Origin', $origin)
                ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
                ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin, Authorization')
                ->header('Access-Control-Allow-Credentials', 'true');
        }
    }
    
   


    public function appProjectName()
    {
        $projectName = $request->input('projectname');

        try {
            // Create a new user
            $user = new User;
            $user->name = $username;
            $user->phone = $phonenumber;
            $user->email = $email;
            $user->password = Hash::make($password); // Hash the password

            // Save the user to the database
            $user->save();

            return response()->json(['message' => 'success'])
            ->header('Access-Control-Allow-Origin', 'http://localhost:19006')
            ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin, Authorization')
            ->header('Access-Control-Allow-Credentials', 'true');
        } catch (\Exception $e) {
            // If something went wrong, return an error message
            return response()->json(['message' => 'ProjectName Error'])
            ->header('Access-Control-Allow-Origin', 'http://localhost:19006')
            ->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE')
            ->header('Access-Control-Allow-Headers', 'Content-Type, X-Auth-Token, Origin, Authorization')
            ->header('Access-Control-Allow-Credentials', 'true');
        }
    }
    public function otherLogin(Request $request) {

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
            
            Session::put('email', $user->email);
            Session::put('redirect', "login");
            Session::put('phone', $user->phone);
            Session::put('pc_info', $pc_info);
            
            return redirect()->route('otp.verify');
        }

        return back()->with('error', 'Invalid Email or Password');
    }
}
