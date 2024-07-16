<?php

namespace App\Http\Controllers;


use App\Helpers\Helper;
use Aws\Sns\SnsClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\User;
use App\PCInfo;
use Aws\Pinpoint\PinpointClient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OTPVerificationController extends Controller
{
    public function showVerificationForm()
    {
        $email = Session::get('email');
        $phone = Session::get('phone');
        $redirect = Session::get('redirect');
        $pc_info = Session::get('pc_info');
        $info = array(
            "email" => $email,
            'pc_info' => $pc_info,
            'redirect' => $redirect,
        );

        $user = User::where('email', $email)->first();
        $user->sms_code = mt_rand(100000, 999999);
        $user->phone_time = Carbon::parse()->addMinutes(5);
        $user->save();

        $this->sendSms($phone, 'Your Verification Code is ' . $user->sms_code);


        return view('otp.verify', $info);
    }
    public function sendSms($mobile, $message)
    {
        $pinpoint = new PinpointClient([
            'region' => config('aws.pinpoint.region'),
            'version' => 'latest',
            'credentials' => [
                'key' => config('aws.pinpoint.key'),
                'secret' => config('aws.pinpoint.secret'),
            ],
        ]);

        try {
            $result = $pinpoint->sendMessages([
                'ApplicationId' => config('aws.pinpoint.application_id'),
                'MessageRequest' => [
                    'Addresses' => [
                        $mobile => ['ChannelType' => 'SMS'],
                    ],
                    'MessageConfiguration' => [
                        'SMSMessage' => [
                            'Body' => $message,
                            'MessageType' => 'TRANSACTIONAL',
                        ],
                    ],
                ],
            ]);
    
            // Handle the result as needed
            $statusCode = $result->get('@metadata')['statusCode'];
            if ($statusCode === 200) {
                // Message sent successfully
                return true;
            } else {
                // Handle other status codes or log errors
                return false;
            }
        } catch (\Exception $e) {
            // Handle exceptions, log errors, etc.
            return false;
        }
    }

    public function sendVerification(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (Carbon::parse($user->phone_time)->addMinutes(1) > Carbon::now()) {
            $this->sendSms($user->phone, 'Your Verification Code is ' . $user->sms_code);
            return $user->sms_code;
        } else {
            $code = mt_rand(100000, 999999);
            $user->phone_time = Carbon::now();
            $user->sms_code = $code;
            $user->save();
        }
    }

    public function verifyCode(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user->sms_code == $request->otp_code) {
            $pc = PCInfo::where('uid', $user->id)->where('info', $request->pc_info)->first();
            if ($pc) {
                $pc->is_verified = 1;
                $pc->save();

                $redirect = Session::get('redirect');
                if ($redirect == 'login')
                    Auth::login($user);
            }
            $response = true;
        } else {
            $response = false;
        }

        echo json_encode($response);
    }

    public function backLogin(Request $request){
        Auth::logout();
        $response = true;
        echo json_encode($response);
    }
}