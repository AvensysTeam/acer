<?php

namespace App\Http\Controllers\Admin;

use App\Scooter;
use App\ScooterStatus;
use App\UtilitiesSale;
use DB;
use Illuminate\Database\Eloquent\Builder;

class HomeController
{
    public function index()
    {
        $ready_status = ScooterStatus::where('name', 'FINALIZAT')->first();
        $working_status = ScooterStatus::where('name', 'IN LUCRU')->first();
        $ready_scooters =  Scooter::where('status_id', isset($ready_status->id) ? $ready_status->id : 0)->orderBy('created_at', 'asc')->get();
        $working_scooters =  Scooter::where('status_id', isset($working_status->id) ? $working_status->id : 0)->orderBy('created_at', 'asc')->get();

        $version = "v1.00";
        if(file_exists(public_path('settings.json'))) {
            $setting_info = file_get_contents(public_path('settings.json'));
            $setting_info = json_decode($setting_info, true);
            $version = $setting_info['version'];
        } else {
            $setting_info = [
                'seller_info' => [
                    'name' => '',
                    'address' => '',
                    'tel_num' => '',
                    'fax' => '',
                    'contact' => [
                        'name' => '',
                        'tel_num' => '',
                        'mobile' => '',
                        'email' => ''
                    ]
                ],
                'version' => $version
            ];
            
            file_put_contents(public_path('settings.json'), json_encode($setting_info));
        }
        $date = DB::table('project')->latest()->first();
        $formattedDate = null;
        if ($date) {
            $formattedDate = date('Y-m-d', strtotime($date->created_at));
            
        }

        $utilitiesSale = UtilitiesSale::where(function (Builder $query) {
            $query->where('is_folder', 1)->orWhere(function (Builder $query) {
                $query->where('is_folder', 0)->whereHas('saleUserPermission', function (Builder $query1) {
                    if (!in_array(1, auth()->user()->roles->pluck('id')->toArray())) {
                        $query1->where('user_id', auth()->user()->id);
                    }
                });
            });
        })->get();

        // return view('home', compact('ready_scooters', 'working_scooters'));
        return view('home', [
            '_page_title' => "Software version $version",
            'Latest_date' => date('Y-m-d', strtotime($formattedDate)),
            'utilitiesSale' => $utilitiesSale
        ]);
    }

    public function indexTest($uid)
    {
        return view('home-test');
    }

    public function home()
    {
        if (session('status')) {
            return redirect()->route('admin.home')->with('status', session('status'));
        }

        return redirect()->route('admin.home');
    }
}
