<?php

namespace App\Http\Controllers\Admin;

use App\Scooter;
use App\ScooterStatus;
use App\UtilitiesAcerStatistics;
use App\UtilitiesCustomerStatistics;
use App\UtilitiesDashboardQuality;
use App\UtilitiesDashboardMaintenance;
use App\UtilitiesWarranty;
use App\UtilitiesService;
use App\UtilitiesInformatics;
use App\UtilitiesSale;
use App\UtilitiesProduction;
use App\UtilitiesMaintenance;
use App\UtilitiesQuality;
use App\UtilitiesMechanicals;
use App\UtilitiesElectronics;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

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
        // $date = DB::table('project')->latest()->first();
        // $formattedDate = null;
        // if ($date) {
        //     $formattedDate = date('Y-m-d', strtotime($date->created_at));
            
        // }
        $logHistory = DB::table('loghistories')
        ->select('loghistories.updated_at')
        ->leftJoin('users', 'loghistories.user_id', '=', 'users.id')
        ->where('users.email', auth()->user()->email)
        ->orderBy('loghistories.updated_at', 'desc') // Order by updated_at in descending order
        ->first();
        $formattedDate = null;
        if ($logHistory) {
            $formattedDate = Carbon::parse($logHistory->updated_at)->format('Y-m-d');
        }
        $utilties_Acer_Statistics_tree = UtilitiesAcerStatistics::where(function (Builder $query) {
            $query->where('is_folder', 1)->orWhere(function (Builder $query) {
                $query->where('is_folder', 0)->whereHas('saleUserPermission', function (Builder $query1) {
                    if (!in_array(1, auth()->user()->roles->pluck('id')->toArray())) {
                        $query1->where('user_id', auth()->user()->id);
                    }
                });
            });
        })->get();
        $utilties_Customer_Statistics_tree = UtilitiesCustomerStatistics::where(function (Builder $query) {
            $query->where('is_folder', 1)->orWhere(function (Builder $query) {
                $query->where('is_folder', 0)->whereHas('saleUserPermission', function (Builder $query1) {
                    if (!in_array(1, auth()->user()->roles->pluck('id')->toArray())) {
                        $query1->where('user_id', auth()->user()->id);
                    }
                });
            });
        })->get();
        $utilties_Dashboard_Quality_tree = UtilitiesDashboardQuality::where(function (Builder $query) {
            $query->where('is_folder', 1)->orWhere(function (Builder $query) {
                $query->where('is_folder', 0)->whereHas('saleUserPermission', function (Builder $query1) {
                    if (!in_array(1, auth()->user()->roles->pluck('id')->toArray())) {
                        $query1->where('user_id', auth()->user()->id);
                    }
                });
            });
        })->get();
        $utilties_Dashboard_Maintenance_tree = UtilitiesDashboardMaintenance::where(function (Builder $query) {
            $query->where('is_folder', 1)->orWhere(function (Builder $query) {
                $query->where('is_folder', 0)->whereHas('saleUserPermission', function (Builder $query1) {
                    if (!in_array(1, auth()->user()->roles->pluck('id')->toArray())) {
                        $query1->where('user_id', auth()->user()->id);
                    }
                });
            });
        })->get();
        $utilties_Warranty_tree = UtilitiesWarranty::where(function (Builder $query) {
            $query->where('is_folder', 1)->orWhere(function (Builder $query) {
                $query->where('is_folder', 0)->whereHas('saleUserPermission', function (Builder $query1) {
                    if (!in_array(1, auth()->user()->roles->pluck('id')->toArray())) {
                        $query1->where('user_id', auth()->user()->id);
                    }
                });
            });
        })->get();

        $utilties_Service_tree = UtilitiesService::where(function (Builder $query) {
            $query->where('is_folder', 1)->orWhere(function (Builder $query) {
                $query->where('is_folder', 0)->whereHas('saleUserPermission', function (Builder $query1) {
                    if (!in_array(1, auth()->user()->roles->pluck('id')->toArray())) {
                        $query1->where('user_id', auth()->user()->id);
                    }
                });
            });
        })->get();

        $utilties_Informatics_tree = UtilitiesInformatics::where(function (Builder $query) {
            $query->where('is_folder', 1)->orWhere(function (Builder $query) {
                $query->where('is_folder', 0)->whereHas('saleUserPermission', function (Builder $query1) {
                    if (!in_array(1, auth()->user()->roles->pluck('id')->toArray())) {
                        $query1->where('user_id', auth()->user()->id);
                    }
                });
            });
        })->get();
        
        $utilitiesSale = UtilitiesSale::where(function (Builder $query) {
            $query->where('is_folder', 1)->orWhere(function (Builder $query) {
                $query->where('is_folder', 0)->whereHas('saleUserPermission', function (Builder $query1) {
                    if (!in_array(1, auth()->user()->roles->pluck('id')->toArray())) {
                        $query1->where('user_id', auth()->user()->id);
                    }
                });
            });
        })->get();

        $utilitiesProduction = UtilitiesProduction::where(function (Builder $query) {
            $query->where('is_folder', 1)->orWhere(function (Builder $query) {
                $query->where('is_folder', 0)->whereHas('saleUserPermission', function (Builder $query1) {
                    if (!in_array(1, auth()->user()->roles->pluck('id')->toArray())) {
                        $query1->where('user_id', auth()->user()->id);
                    }
                });
            });
        })->get();
        
        $utilitiesMaintenance = UtilitiesMaintenance::where(function (Builder $query) {
            $query->where('is_folder', 1)->orWhere(function (Builder $query) {
                $query->where('is_folder', 0)->whereHas('saleUserPermission', function (Builder $query1) {
                    if (!in_array(1, auth()->user()->roles->pluck('id')->toArray())) {
                        $query1->where('user_id', auth()->user()->id);
                    }
                });
            });
        })->get();

        $utilitiesQuality = UtilitiesQuality::where(function (Builder $query) {
            $query->where('is_folder', 1)->orWhere(function (Builder $query) {
                $query->where('is_folder', 0)->whereHas('saleUserPermission', function (Builder $query1) {
                    if (!in_array(1, auth()->user()->roles->pluck('id')->toArray())) {
                        $query1->where('user_id', auth()->user()->id);
                    }
                });
            });
        })->get();

        $utilitiesMechanicals = UtilitiesMechanicals::where(function (Builder $query) {
            $query->where('is_folder', 1)->orWhere(function (Builder $query) {
                $query->where('is_folder', 0)->whereHas('saleUserPermission', function (Builder $query1) {
                    if (!in_array(1, auth()->user()->roles->pluck('id')->toArray())) {
                        $query1->where('user_id', auth()->user()->id);
                    }
                });
            });
        })->get();
        $utilitiesElectronics = UtilitiesElectronics::where(function (Builder $query) {
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
            'Latest_date' => $formattedDate,
            'utilties_Acer_Statistics_tree' => $utilties_Acer_Statistics_tree,
            'utilties_Customer_Statistics_tree' => $utilties_Customer_Statistics_tree,
            'utilties_Dashboard_Quality_tree' => $utilties_Dashboard_Quality_tree,
            'utilties_Dashboard_Maintenance_tree' => $utilties_Dashboard_Maintenance_tree,
            'utilties_Warranty_tree' => $utilties_Warranty_tree,
            'utilties_Service_tree' => $utilties_Service_tree,
            'utilties_Informatics_tree' => $utilties_Informatics_tree,
            'utilitiesSale' => $utilitiesSale,
            'utilitiesProduction' => $utilitiesProduction,
            'utilitiesMaintenance' => $utilitiesMaintenance,
            'utilitiesQuality' => $utilitiesQuality,
            'utilitiesMechanicals' => $utilitiesMechanicals,
            'utilitiesElectronics' => $utilitiesElectronics,
        ]);
    }

    // public function indexTest($uid)
    // {
    //     $role = DB::table('role_user')
    //         ->select('role_id')
    //         ->where('user_id', $uid)
    //         ->first();
    //     $permission = DB::table('permission_role')
    //         ->select('title')
    //         ->where('role_id', $role->role_id)
    //         ->leftJoin('permissions', function($join) {
    //             $join->on('permission_role.permission_id', "=", 'permissions.id');
    //         })->get();
    //     $data = array();
    //     return view('home-test', [
    //         'role' => $data,
    //     ]);
    // }
    public function indexTest($uid)
    {
        $role = DB::table('role_user')
            ->select('role_id')
            ->where('user_id', $uid)
            ->first();

        if($role) {
            $permissions = DB::table('permission_role')
                ->select('display')
                ->where('role_id', $role->role_id)
                ->leftJoin('permissions', 'permission_role.permission_id', '=', 'permissions.id')
                ->pluck('display')
                ->toArray();

            return view('home-test', [
                'role' => $permissions,
            ]);
        } else {
            return view('home-test', [
                'role' => [],
            ]);
        }
    }

    public function home()
    {
        if (session('status')) {
            return redirect()->route('admin.home')->with('status', session('status'));
        }

        return redirect()->route('admin.home');
    }
}
