<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Company;
use App\ContactPeople;
use App\Project;
use App\Settings;
use App\JobPosition;
use App\Price;
use App\Unit;
use App\Accessories;
use App\User;
use App\DeliveryAddress;
use App\DeliveryCondition;
use App\Role;
use Mail;
use App\Services\ViesService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ProjectsController extends Controller
{    
    protected $viesService;

    public function __construct(ViesService $viesService)
    {
        $this->viesService = $viesService;
    }

    public function index()
    {
        //$query = "SELECT P.id, P.company, P.contact, P.reference, C.`name` AS `customer`, CP.firstname AS `contact_name`, P.`name` AS `project_name`, P.description, P.updated_at, P.`status` FROM `project` AS `P` LEFT JOIN `company` AS `C` ON P.company = C.id LEFT JOIN `contact_people` AS `CP` ON P.contact = CP.id WHERE ISNULL(P.deleted_at) AND P.user=" . auth()->user()->id;
       

        $result = $this->getProjectList();

        return view('admin.projects.index', [
            '_page_title' => trans('global.project.project_list'),
            'project_list' => $result
        ]);
    }

    private function getProjectList() {
        $query = "SELECT P.id, P.company, P.contact, P.reference, P.pdf as p_pdf, 
                        C.`name` AS `customer`, CP.firstname AS `contact_name`, 
                        P.`name` AS `project_name`, P.description, P.updated_at, P.`status` 
                FROM `project` AS `P` 
                LEFT JOIN `company` AS `C` ON P.company = C.id 
                LEFT JOIN `contact_people` AS `CP` ON P.contact = CP.id 
                WHERE ISNULL(P.deleted_at)" ;

        $result = DB::select($query);

        return $result;
    }

    public function profile($pid=0, $cid=0, $uid=0)
    {

        // $company_list = Company::where('user', auth()->user()->id)->get();
        $company_list = Company::all();
        $job_list = JobPosition::all();
        
        Session::put('pid', $pid);

        $roleObject = new Role();
        $roles = $roleObject->getOtherRoles();
        
        $legalForms = Company::$compay_legal_form;
        $servicesActivitys = Company::$sectors_of_activity;
        $company_sizes = Company::$company_sizes;
       
        return view('admin.projects.profile',[
            '_page_title' => trans('global.project.project_profile_company_selection'), 
            'company_list' => $company_list,
            'job_list' => $job_list,
            'pid' => $pid,
            'cid' => $cid,
            'uid' => $uid,
            'roles' => $roles, 
            'legalForms' => $legalForms,
            'servicesActivitys' => $servicesActivitys, 
            'company_sizes' => $company_sizes
        ]);

        
    }

    // public function contactlist(Request $request,$pid=0, $cid=0, $uid=0)
    // {
    //     $id = $request->id;
    //     $job_list = JobPosition::all();
    //     $contact = DB::table('contact_people')
    //             // ->where('user', auth()->user()->id)
    //             ->where('company_id', $id)
    //             ->get();
    //     return view('admin.projects.contactlist',compact('contact','pid','cid','uid','job_list','id'));
    //     // $company_list = Company::where('user', auth()->user()->id)->get();
    //     // $company_list = Company::all();
    //     // $job_list = JobPosition::all();
       
        
    //     // print_r($list); exit;
    //         //  return response()->json(['result' => json_decode($list)]);
           
    //     // return view('admin.projects.contactlist',[
    //     //     '_page_title' => trans('global.project.project_profile'), 
    //     //     'company_list' => $company_list,
    //     //     'job_list' => $job_list,
    //     //     'pid' => $pid,
    //     //     'cid' => $cid,
    //     //     'uid' => $uid,
    //     // ]);
    //     // return view('admin.projects.contactlist',compact('job_list','pid','cid','uid'));
    // }


    public function detail($pid=0, $cid=0, $uid=0)
    {
        
        $option = $_GET['o'] ?? "";
        $user = User::findorFail(auth()->user()->id);
        $delivery_address = DeliveryAddress::where('id', $user->delivery_address)
            ->where('uid', auth()->user()->id)
            ->first();
        $delivery_condition = DeliveryCondition::where('id', $user->delivery_condition)
            ->where('uid', auth()->user()->id)
            ->first();
        $company = Company::findOrFail($cid);
        $contact = User::findOrFail($uid);
        // $contact = ContactPeople::findOrFail($uid);
        $project = null;
        $units = null;
        if($pid > 0) {
            $project = Project::findOrFail($pid);
            $units = Unit::join('prices', 'prices.id', '=', 'units.priceId')
                        ->where('units.pid', $pid)
                        ->whereNull('units.deleted_at')
                        ->select(['units.*', 'prices.price as price', 
                            'prices.itemcode as p_itemcode', 'prices.description as p_desc'])
                        ->get();
        }
        // $units = Unit::all();
        $settings = Settings::where('user', auth()->user()->id)->first();

        $version = "v1.00";
        if(file_exists(public_path('settings.json'))) {
            $setting_info = file_get_contents(public_path('settings.json'));
            $setting_info = json_decode($setting_info, true);            
            $version = $setting_info['version'];
        } else {
            $setting_info = ['version' => $version];            
            file_put_contents(public_path('settings.json'), json_encode($setting_info));
        }
        if($pid > 0){
           $title = trans('global.project.project_profile');
        }else{
           $title = trans('global.project.Offer tool new');
        }
        $count_project = Project::withTrashed()->get();;
        $cp = $count_project->count();
        // $contact_email = ContactPeople::select('email')->Where('id',$uid)->get();
        $contact_email = User::select('email')->Where('id',$uid)->get();

        //dump($contact_email);
        return view('admin.projects.detail',[
            '_page_title' => $title,
            'user' => $user,
            'company' => $company,
            'contact' => $contact,
            'pid' => $pid,
            'cid' => $cid,
            'uid' => $uid,
            'contact_email' => $contact_email,
            'project_count' => $cp,
            'project' => $project,
            'settings' => $settings,
            'version' => $version,
            'option' => $option,
            'units' => json_encode($units),
            'delivery_address' => $delivery_address,
            'delivery_condition' => $delivery_condition
        ]);
    }

    public function get_models(Request $request)
    {
        //$server_url = url('/');
        $server_url = "https://www.avensys-srl.com/";
        $url = $server_url.'ssw/models_json.php';
        $data = $request->all();

        // $server_url = "http://3.74.60.121";
        // $url = $server_url.'/api/models_json.php';
        // $data = $request->all();

        //print_r($url . '?' . http_build_query($data));exit;
        
        $ch = curl_init($url . '?' . http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        
        curl_close($ch);
        return response()->json(['result' => json_decode($response)]);
    }

    public function get_completedata(Request $request)
    {
        //$server_url = url('/');
        $server_url = "https://www.avensys-srl.com/";
        $url = $server_url.'ssw/completedata_json.php';
        $data = $request->all();
        
        $ch = curl_init($url . '?' . http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        
        curl_close($ch);

        $url2 = $server_url.'api/accessories.php';

        $ch = curl_init($url2 . '?' . http_build_query(['idmodel' => $request->id]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response2 = curl_exec($ch);
        
        curl_close($ch);


        $prices = $this->get_model_price_by_id($request->id);

        $result = [
            'completedata' => json_decode($response),
            'accessories' => json_decode($response2),
            'prices' => $prices
        ];
        
        return response()->json(['result' => $result]);
    }

    public function get_accessories(Request $request)
    {
        $server_url = "https://www.avensys-srl.com/";
        $url = $server_url.'api/accessories.php';
        $data = $request->all();//https://www.avensys-srl.com/api/accessories.php?idmodel=96

        $ch = curl_init($url . '?' . http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        
        curl_close($ch);      
        
        return response()->json(['result' => json_decode($response)]);
    }

    public function get_contact_list(Request $request) 
    {
        $customerController = new CustomerController();
        return $customerController->get_contact_list($request);
    }

    public function store_contact(Request $req) {



        $rules = [           
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
        ];

        $validation = Validator::make($req->all(), $rules);
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 500);
        }

        $user = User::create([
            'name'     => $req->first_name,
            'email'    => $req->email,
            'phone'    => $req->full_contact_mobile_no,
            'pc_info' => 'required|string',
            'position' => $req->job_position,
            'password' => Hash::make('acer_default'),
            'company_id' => $req->company_id,
        ]);

        $isDefaultRole = Role::with('permissions')->where('title', 'default')->first();
        if($isDefaultRole){
            $user->roles()->sync([$isDefaultRole->id]);
        }

        
        return response()->json(['result' => $user]);


        $com_id = $req->company_id;
        $con_id = $req->contact_id;
        if(!isset($req->contact_id)){
            $con_id = $req->conta;
        }

    
        $first_name = $req->first_name;
        $second_name = $req->last_name;
        $tel_no = $req->tel_no;
        $mobile_no = $req->mobile_no;
        $email = $req->email;
        $job_position = $req->job_position;

        $contact = null;

        if($con_id > 0) 
            $contact = ContactPeople::findOrFail($con_id);           
        else 
            $contact = new ContactPeople();

        if(!$contact) 
            return response()->json(['result' => 'failed']); 
            
        $contact->user = auth()->user()->id;
        $contact->company_id = $com_id;
        $contact->firstname = $first_name;
        $contact->secondname = $second_name;
        $contact->phone = $tel_no;
        $contact->mobile = $mobile_no;
        $contact->email = $email;
        $contact->job_position = $job_position;
        $res = $contact->save();

        return response()->json(['result' => $res]);
    }

    public function delete_contact(Request $rea, $id = 0) {
        $contact = ContactPeople::findOrFail($id);
        $res = $contact->delete();
        return response()->json(['result' => $res]);
    }
    

    public function store_deliverytime(Request $request) {
        
        try {
            if(!empty($request-> unit_delivery_times)) {
                $time_data = json_decode($request-> unit_delivery_times, true);
                foreach($time_data as $dt) {
                    Unit::where('id', $dt['id'])->update($dt);
                 }
            }        
            echo 'success';
        } catch (\Throwable $th) {
            echo 'fail';
        }

       
    }

    public function upload_project_pdf(Request $request) {
        $p_id = $request->id;
        if($p_id > 0) {
            $project = Project::findOrFail($p_id);
            if($request->hasFile('pdf')) {
                if($p_id > 0 && $project->pdf) {
                    $old_file_path = $this->get_project_dir_path() . '/' . $project->pdf;
                    if (file_exists($old_file_path))
                        unlink($old_file_path);
                }
                $filename = $request->pdf->getClientOriginalName();
                $request->pdf->move($this->get_project_dir_path(), $filename);            
                $project->pdf = $filename;
                $project->save();
            }
        } else {
            echo 'no project';
            return;            
        }
       
    }



    public function save_project(Request $request) {
        //  dd($request->all());
        $p_id = $request->id;
    
        $project = null;

        if($p_id > 0) {
            $project = Project::findOrFail($p_id);
        } else
            $project = new Project();

        $project->user = auth()->user()->id;
        $project->company = $request->company;
        $project->contact = $request->contact;
        $project->name = $request->name;
        $project->description = $request->description;        
        $project->reference = $request->reference;
        if($p_id == 0) {
            $project->status = 0;
        }
        
        if($request->hasFile('pdf')) {
            if($p_id > 0 && $project->pdf) {
                $old_file_path = $this->get_project_dir_path() . '/' . $project->pdf;
                if (file_exists($old_file_path))
                    unlink($old_file_path);
            }
            $filename = $request->pdf->getClientOriginalName();
            $request->pdf->move($this->get_project_dir_path(), $filename);            
            $project->pdf = $filename;
        }

        $project->layout = $request->layout;
        $project->indoor = $request->indoor;
        $project->ex1 = $request->ex1;
        $project->ex2 = $request->ex2;
        $project->airflow = $request->airflow;
        $project->pressure = $request->pressure;
        $project->Tfin = $request->Tfin;
        $project->Trin = $request->Trin;
        $project->Hfin = $request->Hfin;
        $project->Hrin = $request->Hrin;
        
        $project->modelId = $request->modelId;
        $project->priceId = $request->priceId;
        $project->save();
        $pid = $project->id;


        // //Unit::where('pid', $pid)->delete();
        // $units = $request->units;
        // // dd($units);
        // die();
        // $n = count($units);
        // for ($i=0; $i < $n; $i++) {
        //    $unit = new Unit();
        //     $unit->pid =        $pid;
        //     $unit->name =       $units[$i]->name;
        //     $unit->layout =     $units[$i]->layout;
        //     $unit->indoor =     $units[$i]->indoor;
        //     $unit->ex1 =        $units[$i]->ex1;
        //     $unit->ex2 =        $units[$i]->ex2;
        //     $unit->airflow =    $units[$i]->airflow;
        //     $unit->pressure =   $units[$i]->pressure;
        //     $unit->Tfin =       $units[$i]->Tfin;
        //     $unit->Trin =       $units[$i]->Trin;
        //     $unit->Hfin =       $units[$i]->Hfin;
        //     $unit->Hrin =       $units[$i]->Hrin;
        //     $unit->modelId =    $units[$i]->modelId;
        //     $unit->priceId =    $units[$i]->priceId;
        //     $unit->price =      $units[$i]->price;
        //     $unit->delivery_time =      $units[$i]->delivery_time;
        //     $unit->save();
        // }
        // //dd($request->all());
        
        // $unit = Unit::select('id')->Where('pid',$p_id)->first();
        
        // if ($p_id > 0 || $unit === null) {
        //     if ($unit !== null) {
        //         $unit = Unit::findOrFail($unit->id);
        //     } else {
        //         $unit = new Unit();
        //     }
        // }

        $unit_id = $request->unit_id;

        if($unit_id > 0) {
            $unit = Unit::findOrFail($unit_id);
        } else
            $unit = new Unit();

        // Update the attributes for the $unit object
        $unit->pid = $pid;
        $unit->name = $request->unit_name;

        if($request->hasFile('unit_pdf')) {           
            $filename_unit = $request->unit_pdf->getClientOriginalName();
            $request->unit_pdf->move($this->get_project_dir_path(), $filename_unit);            
            $unit->pdf = $filename_unit;
        }

        $unit->layout = $request->layout;
        $unit->indoor = $request->indoor;
        $unit->ex1 = $request->ex1;
        $unit->ex2 = $request->ex2;
        $unit->airflow = $request->airflow;
        $unit->pressure = $request->pressure;
        $unit->Tfin = $request->Tfin;
        $unit->Trin = $request->Trin;
        $unit->Hfin = $request->Hfin;
        $unit->Hrin = $request->Hrin;
        $unit->modelId = $request->modelId;
        $unit->priceId = $request->priceId;
        $unit->delivery_time = $request->unit_delivery_time;
        $unit->standard_climatic = $request->standard_climatic;
               
        $unit->s_Tfin = $request->s_Tfin;
        $unit->s_Trin = $request->s_Trin;
        $unit->s_Hfin = $request->s_Hfin;
        $unit->s_Hrin = $request->s_Hrin;
        
        $unit->p_r_airflow = $request->p_r_airflow;
        $unit->p_r_pressure = $request->p_r_pressure;
        $unit->p_sfp = $request->p_sfp;
        $unit->m_rfl = $request->m_rfl;

        $unit->thumbnail = $request->thumbnail;

        // Save the updated $unit object
        $unit->save();


         
        $unit_id = $unit->id;

        // $accessorie = Accessories::select('id')->Where('project_id',$p_id)->first();
        
        // if ($p_id > 0 || $accessorie === null) {
        //     if ($accessorie !== null) {
        //         $accessorie = Accessories::findOrFail($accessorie->id);
        //     } else {
        //         $accessorie = new Accessories();
        //     }
        // }


        $accessorie = new Accessories();

        $accessorie->accessories = $request->accessories;
        $accessorie->project_id = $pid;
        $accessorie->unit_id = $unit_id;
       
        // Save the updated $unit object
        $accessorie->save();

        return response()->json(['result' => [
            'success' =>  true,
            'project_id' => $pid,
            'unit_id' => $unit_id,
            'accessory_id' => $accessorie->id
        ]]);
        echo 'success';
    }

    public function delete_project(Request $req) {
        $id = $req->id;
        $project = Project::findOrFail($id);
        $res = $project->delete();
        return response()->json(['result' => $res]);
    }

    public function multi_delete_project(Request $request) {
        $res = Project::whereIn('id', $request->ids)->delete();        
        return response()->json(['result' => $res]);
    }

    public function duplicate_project(Request $req) {
        // dd($req->all());
        $id = $req->id;
        $project = Project::findOrFail($id);
        // dd($project);
        $new_project = new Project();
        
        $new_project->user = auth()->user()->id;
        $new_project->company = $project->company;
        $new_project->contact = $project->contact;
        $new_project->name = $project->name;
        $new_project->description = $project->description;        
        $new_project->reference = $project->reference;
        $new_project->layout = $project->layout;
        $new_project->indoor = $project->indoor;
        $new_project->ex1 = $project->ex1;
        $new_project->ex2 = $project->ex2;
        $new_project->airflow = $project->airflow;
        $new_project->pressure = $project->pressure;
        $new_project->Tfin = $project->Tfin;
        $new_project->Trin = $project->Trin;
        $new_project->Hfin = $project->Hfin;
        $new_project->Hrin = $project->Hrin;
        $new_project->status = $project->status;
        $new_project->modelId = $project->modelId;

        // dd($project->pdf);
        
        if($project->pdf != "")
        {
            $new_pdf = 'REPORT_' . time() . '.pdf';
            $s_path = $this->get_project_dir_path() . '/' .  $project->pdf;
            $n_path = $this->get_project_dir_path() . '/' .  $new_pdf;
            // Copy the file using the copy() function
            if (file_exists($s_path)) {
                if (copy($s_path, $n_path)) {
                    $new_project->pdf = $new_pdf;
                }
            }
        }        
        $res = $new_project->save();
        
        return response()->json(['result' => $new_project->id]);
    }

    public function save_company(Request $req) {

        $com_id = $req->company_id;
        $company = null;
        $editicon=url("assets/icons/pencil-line-icon-original.svg");
        $dlticon=url('assets/icons/trash-icon-original.svg');
        

        if($com_id > 0) 
            $company = Company::findOrFail($com_id);
        else 
            $company = new Company();

        if(!$company) 
            return response()->json(['result' => 'failed']); 
            
        $company->user = auth()->user()->id;
        $company->name = $req->company_name;
        $company->address = $req->company_address;
        $company->phone = $req->full_mobile_phone;
        $company->VAT = $req->company_VAT;
        $company->legal_form = $req->legal_form;
        $company->sector_activity = $req->sector_activity;
        $company->company_size = $req->company_size;

        // $company->operational_address = $data['operational_address'];
        // $company->contact_person_name = $data['contact_person_name'];
        // $company->country_code = $data['country_code'];
       
        $company->description = '';
        $company->save();
        if($com_id == 0 || $com_id == "") {
        $com_id = $company->id;
        }
        $companydetail = $company->name.'$$'.$company->address.'$$'.$company->phone.'$$'.$company->VAT.'$$'.$company->description;
       // $company->action = $editicon;
        $company->company_detail = $companydetail;
        //dd($company); 
        return response()->json(['result' => $company]);
    }

    public function delete_company(Request $req ) {
        $com_id = $req->id;
        $company = Company::findOrFail($com_id);
        $res = $company->delete();
        return response()->json(['result' => $res]);
    }

    public function status_change(Request $req)
    {
        $project = Project::findOrFail($req->id);
        $project->status = $req->status;
        $res = $project->save();
        return response()->json(['result' => $res]);
    }

    public function job(Request $req) {
        $job_list = JobPosition::all();
        return view('admin.projects.job', [
            // '_page_title' => trans('global.project.job_position'),
            'job_list' => $job_list
        ]);        
    }
    
    public function jobTest($uid) {
        $job_list = JobPosition::all();
        
        return view('admin.projects.job-test', [
            'job_list' => $job_list,
        ]);        
    }

    public function store_job(Request $req) {
        $job = null;
        if($req->id < 1) {
            $job = new JobPosition();
        } else {
            $job = JobPosition::findOrFail($req->id);
        }
        $job->name = $req->name;
        $res = $job->save();
        return response()->json(['result' => $job]);
    }

    public function delete_job(Request $req) {
        $job = JobPosition::findOrFail($req->id);
        $res = $job->delete();
        return response()->json(['result' => $res]);
    }

    public function get_model_price_by_id($id_model) {
        $res = Price::whereNull('deleted_at')
            ->where('id_model', $id_model)
            ->get();

        $pricetypes_user = DB::table('pricetypes_user')
            ->select('pricetypes')
            ->where('userId', auth()->user()->id)
            ->first();
        $pricetypes_multiplier = array();
        $pricetypes_user_array = array();
        $result = array();
        if ($pricetypes_user) {
            $temps = explode(',', $pricetypes_user->pricetypes);
            foreach ($temps as $key => $row) {
                $temp = explode('_', $row);
                array_push($pricetypes_user_array, $temp[0]);
                $pricetypes_multiplier[$temp[0]] = $temp[1];
            }
            foreach ($res as $row) {
                if (in_array($row->pricetype_id, $pricetypes_user_array)) {
                    $row->multiplier = floatval($pricetypes_multiplier[$row->pricetype_id]);
                    array_push($result, $row);
                }
            }
        }
        return $result;

    }
    
    public function get_model_price(Request $req){
        $id_model = $req->get('id');
        $result = $this->get_model_price_by_id($id_model);
        echo json_encode($result);
    }

    private function get_project_dir_path() {
        if(!file_exists(public_path('uploads/project')) || !is_dir(public_path('uploads/project'))) {
            mkdir(public_path('uploads/project'),0777,true);
        }

        return public_path('uploads/project');
    }

    public function get_pdf(Request $request){
        // $name = $request->input('name');
        $email = $request->input('contact_email');
        $pdfFile = $request->file('pdf');
        $fileName = $request->input('filename');
        $data = [
            'content' => 'this is a pdf file',
        ];
        // Storage::put('pdfs/' . $fileName, file_get_contents($pdfFile));
        Mail::send([], $data, function($message) use ($pdfFile, $fileName,$email) {
            $message->to($email)
            ->subject('Testing Mail')
            ->attach($pdfFile, ['as' => $fileName . '.pdf'])
            ->from('', 'testing sender'); 
            return back()->with('flash','mail sending successfully');
        });

    }

    public function getautofilldata(Request $req){

        // IE&vatNumber=6388047V

        $vatNumber = $req->input('vat_number');
        $company = Company::where('VAT', $vatNumber)->first();

        if(!$vatNumber) {
            return response()->json([
                'status' => 'error',
                'message' => 'No VAT NUMBER'
            ], 200); 
        }

        // Extract "IT" (country code)
        $countryCode = substr($vatNumber, 0, 2);

        // Extract "01258963" (numeric part)
        $numericPart = substr($vatNumber, 2);

    
        if ($company) {
            return response()->json([
                'status' => 'success',
                'source' => 'database',
                'data' => [
                    'name' => $company->name,
                    'address' => $company->address,
                    'phone' => $company->phone,
                    'legal_form' => $company->legal_form,
                    'sector_activity' => $company->sector_activity,
                    'company_size' => $company->company_size,
                    'operational_address' => $company->operational_address,
                    'contact_person_name' => $company->contact_person_name,
                    'id' => $company->id
                ],
            ]);
        } else {

            $result = $this->viesService->validateVAT($countryCode, $numericPart);        
            
            return response()->json([
                'status' => 'error',
                'message' => 'Company not found.',
                'source' => 'validation',
                'data' => $result

            ], 200);
        }
    }

    public function validateVAT(Request $request)
    {
        $request->validate([
            'countryCode' => 'required|string|size:2',
            'vatNumber'   => 'required|string',
        ]);

        $countryCode = $request->input('countryCode');
        $vatNumber   = $request->input('vatNumber');

        $result = $this->viesService->validateVAT($countryCode, $vatNumber);

        return response()->json($result);
    }
}

