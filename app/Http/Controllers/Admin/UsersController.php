<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Role;
use App\User;
use App\Pricetype;
use App\PricetypesUser;
use App\ContactPeople;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Session;
use App\DeliveryAddress;
use App\DeliveryCondition;
use App\Mail\VerificationEmail;
use App\Permission;
use Exception;


class UsersController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $query = "SELECT * FROM (
            SELECT id, name, email, approved, email_verified_at, phone, company_name, 
            company_address, company_vat, position, created_at 
            FROM users  WHERE deleted_at IS NULL
            UNION
            SELECT CONCAT('contact_', contact_people.id) as id, CONCAT(IFNULL(firstname, ''), ' ', IFNULL(secondname, '')) AS name, 
            email, 'contact_people' AS approved, 'verified' AS email_verified_at, mobile AS phone,  
            company.name AS company_name, company.address AS compnay_address, company.VAT AS company_vat , job_position AS position,  contact_people.`created_at`
            FROM contact_people
            LEFT JOIN company ON company.id = contact_people.`company_id`
            WHERE company.deleted_at IS NULL
            ) AS p
            ORDER BY p.created_at DESC;";

        // $users = $results = DB::select("
        //     SELECT users.*, delivery_conditions.cond as delivery_condition_data
        //     FROM (
        //         SELECT users.*, delivery_addresses.address as delivery_address_data
        //         FROM users
        //         LEFT JOIN delivery_addresses ON users.delivery_address = delivery_addresses.id
        //         WHERE users.deleted_at IS NULL
        //     ) as users
        //     LEFT JOIN delivery_conditions ON users.delivery_condition = delivery_conditions.id
        // ");

        $users = DB::select($query);

        $n = count($users);
        for ($i = 0; $i < $n ; $i++) {
            $users[$i]->roles = DB::table('role_user')
                ->select('roles.title', 'roles.id')
                ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                ->where('role_user.user_id', $users[$i]->id)
                ->get();
        }
        // $users = User::all();

        return view('admin.users.index', compact('users'));
    }

    public function indexTest($uid)
    {
        $users = $results = DB::select("
            SELECT users.*, delivery_conditions.cond as delivery_condition_data
            FROM (
                SELECT users.*, delivery_addresses.address as delivery_address_data
                FROM users
                LEFT JOIN delivery_addresses ON users.delivery_address = delivery_addresses.id
                WHERE users.deleted_at IS NULL
            ) as users
            LEFT JOIN delivery_conditions ON users.delivery_condition = delivery_conditions.id
        ");
        $n = count($users);
        for ($i = 0; $i < $n ; $i++) {
            $users[$i]->roles = DB::table('role_user')
                ->select('roles.title')
                ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                ->where('role_user.user_id', $users[$i]->id)
                ->get();
        }
        
        $query = "SELECT permissions.title FROM (SELECT permission_role.permission_id FROM role_user LEFT JOIN permission_role ON role_user.role_id = permission_role.role_id WHERE role_user.user_id = {$uid}) AS A LEFT JOIN permissions ON A.permission_id = permissions.id WHERE permissions.deleted_at IS NULL";
        $results = DB::select(DB::raw($query));
        $role = array();
        foreach ($results as $row) {
            array_push($role, $row->title);
        }

        return view('admin.users.index-test', compact('users', 'role'));
    }

    public function create()
    {
        abort_if(Gate::denies('user_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');
        $pricetypes = DB::table('pricetypes')
            ->select('id', 'name')
            ->whereNull('deleted_at')
            ->get();
        $pricing_class = DB::table('pricings')
            ->select('id', 'description', 'multiplier')
            ->whereNull('deleted_at')
            ->get();

        return view('admin.users.create', compact('roles', 'pricetypes', 'pricing_class'));
    }

    public function store(StoreUserRequest $request, User $user)
    {
        $user = User::create($request->all());
        $user->roles()->sync($request->input('roles', []));

        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles');
        if ($request->pricetypes) {
            $pricetypes = implode(",", $request->pricetypes);
            $price_user = new PricetypesUser();
            $price_user->userId = $user->id;
            $price_user->pricetypes = $pricetypes;
            $price_user->save();
        }

        $delivery_address = DeliveryAddress::where('id', $user->delivery_address)
            ->where('uid', $user->id)
            ->first();
        $delivery_condition = DeliveryCondition::where('id', $user->delivery_condition)
            ->where('uid', $user->id)
            ->first();

        $pricetypes = DB::table('pricetypes')
            ->select('id', 'name')
            ->get();
        $pricetypes_array = array();
        foreach ($pricetypes as $row) {
            $pricetypes_array[$row->id] = $row->name;
        }
        $pricetypes_user = DB::table('pricetypes_user')
            ->select('pricetypes')
            ->where('userId', $user->id)
            ->first();
        $pricetypes_user_array = array();
        if ($pricetypes_user) {
            $temps = explode(',', $pricetypes_user->pricetypes);
            foreach ($temps as $key => $row) {
                $temp = explode('_', $row);
                $pricetypes_user_array[$key] = $pricetypes_array[$temp[0]] . ' - ' . $temp[1];
            }
            $pricetypes = implode(', ', $pricetypes_user_array);
        }
        else {
            $pricetypes = '';
        }

        $pricing_class = DB::table('pricings')
            ->select('id', 'description', 'multiplier')
            ->whereNull('deleted_at')
            ->get();

        $multiplier = '';
        if ($user->multiplier) {
            $pricing_class_id = explode('_', $user->multiplier)[0];
            foreach ($pricing_class as $key => $row) {
                if ($row->id == $pricing_class_id) {
                    $multiplier = $row->description . ' - ' . $row->multiplier;
                }
            }
        }

        return view('admin.users.show', compact('user', 'pricetypes', 'delivery_address', 'delivery_condition', 'multiplier'));
    }

    public function edit(User $user)
    {
        abort_if(Gate::denies('user_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all()->pluck('title', 'id');
        
        $user_pricetypes = DB::table('pricetypes_user')
                            ->select('pricetypes')
                            ->where('userId', $user->id)                            
                            ->get();
        $delivery_address = DeliveryAddress::where('uid', $user->id)->get();
        $delivery_condition = DeliveryCondition::where('uid', $user->id)->get();

        $pricetypes = DB::table('pricetypes')
            ->select('id', 'name')
            ->whereNull('deleted_at')
            ->get();
        $pricetypes_array = array();
        foreach ($pricetypes as $row) {
            $pricetypes_array[$row->id] = $row->name;
        }
        $pricetypes_user = DB::table('pricetypes_user')
            ->select('pricetypes')
            ->where('userId', $user->id)
            ->first();
        $pricetypes_user_array = array();
        if ($pricetypes_user) {
            $temps = explode(',', $pricetypes_user->pricetypes);
            foreach ($temps as $key => $row) {
                $temp = explode('_', $row);
                $pricetypes_user_array[$key]['id'] = $temp[0];
                $pricetypes_user_array[$key]['name'] = $pricetypes_array[$temp[0]] . ' - ' . $temp[1];
                $pricetypes_user_array[$key]['multiplier'] = $temp[1];
            }
        }

        $pricing_class = DB::table('pricings')
            ->select('id', 'description', 'multiplier')
            ->whereNull('deleted_at')
            ->get();

        $user->load('roles');

        return view('admin.users.edit', compact('roles', 'user', 'pricetypes', 'pricetypes_user_array', 'pricing_class', 'delivery_address', 'delivery_condition'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->all());
        $user->delivery_time = $request->delivery_time . '_' . $request->delivery_time_type;
        $user->save();
        $user->roles()->sync($request->input('roles', []));
        
        $user_pricetype = implode(",", $request->pricetypes);
        
        $price_user = PricetypesUser::where('userId', $user->id)->first();
        
        if(!isset($price_user))
            $price_user = new PricetypesUser();

        $price_user->userId = $user->id;
        $price_user->pricetypes = $user_pricetype;
        $price_user->save();

        $users = $results = DB::select("
            SELECT users.*, delivery_conditions.cond as delivery_condition_data
            FROM (
                SELECT users.*, delivery_addresses.address as delivery_address_data
                FROM users
                LEFT JOIN delivery_addresses ON users.delivery_address = delivery_addresses.id
                WHERE users.deleted_at IS NULL
            ) as users
            LEFT JOIN delivery_conditions ON users.delivery_condition = delivery_conditions.id
        ");
        $n = count($users);
        for ($i = 0; $i < $n ; $i++) {
            $users[$i]->roles = DB::table('role_user')
                ->select('roles.title')
                ->leftJoin('roles', 'role_user.role_id', '=', 'roles.id')
                ->where('role_user.user_id', $users[$i]->id)
                ->get();
        }
        // $users = User::all();

        return view('admin.users.index', compact('users'));
        return redirect()->route('admin.users.index');
    }

    public function show(User $user)
    {
        $delivery_address = DeliveryAddress::where('id', $user->delivery_address)
            ->where('uid', $user->id)
            ->first();
        $delivery_condition = DeliveryCondition::where('id', $user->delivery_condition)
            ->where('uid', $user->id)
            ->first();
        abort_if(Gate::denies('user_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->load('roles');
        if ($user->delivery_time) {
            $temp = explode('_', $user->delivery_time);
            $user->delivery_time = $temp[0] . ' ' . ($temp[0] == '1' ? 'Days' : 'Weeks');
        }

        $pricetypes = DB::table('pricetypes')
            ->select('id', 'name')
            ->get();
        $pricetypes_array = array();
        foreach ($pricetypes as $row) {
            $pricetypes_array[$row->id] = $row->name;
        }
        $pricetypes_user = DB::table('pricetypes_user')
            ->select('pricetypes')
            ->where('userId', $user->id)
            ->first();
        $pricetypes_user_array = array();
        if ($pricetypes_user) {
            $temps = explode(',', $pricetypes_user->pricetypes);
            foreach ($temps as $key => $row) {
                $temp = explode('_', $row);
                $pricetypes_user_array[$key] = $pricetypes_array[$temp[0]] . ' - ' . $temp[1];
            }
        }
        $pricetypes = implode(', ', $pricetypes_user_array);

        
        $pricing_class = DB::table('pricings')
            ->select('id', 'description', 'multiplier')
            ->whereNull('deleted_at')
            ->get();

        $multiplier = '';
        if ($user->multiplier) {
            $pricing_class_id = explode('_', $user->multiplier)[0];
            foreach ($pricing_class as $key => $row) {
                if ($row->id == $pricing_class_id) {
                    $multiplier = $row->description . ' - ' . $row->multiplier;
                }
            }
        }

        return view('admin.users.show', compact('user', 'pricetypes', 'delivery_address', 'delivery_condition', 'multiplier'));
    }

    public function destroy(User $user)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Delete roles associated with the user
        //Role::where('id', $user->id)->delete();

        $role = DB::table('role_user')
            ->whereIn('user_id', $user)
            ->pluck('role_id');
        
        
         Role::whereIn('id', $role)->delete();


        $user->delete();

        return back();
    }

    public function selectDestroy(Request $request)
    {
        $ids = $request->input('ids');
        if (!$ids) {
            return redirect()->back()->withErrors('Please select at least one user to delete.');
        }

        $role = DB::table('role_user')
            ->whereIn('user_id', $ids)
            ->pluck('role_id');
        
        
         Role::whereIn('id', $role)->delete();

         User::whereIn('id', $ids)->delete();

        return redirect()->back()->with('success', 'Selected users have been deleted successfully.');
    }

    public function massDestroy(MassDestroyUserRequest $request)
    {
        $ids = request('ids');

        $role = DB::table('role_user')
            ->whereIn('user_id', $ids)
            ->pluck('role_id');
        
        
         Role::whereIn('id', $role)->delete();

        // Delete roles associated with the users
       // Role::whereIn('id', $ids)->delete();

        User::whereIn('id', $ids)->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function addDeliveryAddress(Request $request)
    {
        $new = new DeliveryAddress();
        $new->uid = $request->uid;
        $new->address = $request->address;
        $new->save();
        return $new->id;
    }

    public function addDeliveryCondition(Request $request)
    {
        $new = new DeliveryCondition();
        $new->uid = $request->uid;
        $new->cond = $request->condition;
        $new->save();
        return $new->id;
    }

    public function userTest(Request $request) {
        if ($request->method() === 'GET') {
            $users = User::where('id', '<>', auth()->user()->id)
                ->get();
            return view('admin.users.test', compact('users'));
        } elseif ($request->method() === 'POST') {

            $uid = $request->uid;
            $pricetypes = DB::table('pricetypes')
                ->select('id', 'name')
                ->whereNull('deleted_at')
                ->get();
            $pricetypes_array = array();
            foreach ($pricetypes as $row) {
                $pricetypes_array[$row->id] = $row->name;
            }
            $pricetypes_user = DB::table('pricetypes_user')
                ->select('pricetypes')
                ->where('userId', $uid)
                ->first();
            $pricetypes_user_array = array();
            $role = DB::table('role_user')
                ->select('role_id')
                ->where('user_id', $uid)
                ->first();
            $multiplier = auth()->user()->multiplier;
            if ($multiplier) {
                $multiplier = floatval(explode('_', $multiplier)[1]);
            } else {
                $multiplier = 1;
            }
            if ($pricetypes_user) {
                $temps = explode(',', $pricetypes_user->pricetypes);
                foreach ($temps as $key => $row) {
                    $temp = explode('_', $row);
                    $pricetypes_user_array[$key]['id'] = $temp[0];
                    if ($role->role_id === 1) {
                        $pricetypes_user_array[$key]['name'] = $pricetypes_array[$temp[0]] . ' - ' . (floatval($temp[1]) * $multiplier) ;
                    } else {
                        $pricetypes_user_array[$key]['name'] = $pricetypes_array[$temp[0]];
                    }
                }
            }

            $query = "SELECT permissions.title FROM (SELECT permission_role.permission_id FROM role_user LEFT JOIN permission_role ON role_user.role_id = permission_role.role_id WHERE role_user.user_id = {$uid}) AS A LEFT JOIN permissions ON A.permission_id = permissions.id WHERE permissions.deleted_at IS NULL";
            $results = DB::select(DB::raw($query));
            $role = array();
            foreach ($results as $row) {
                array_push($role, $row->title);
            }

            return view('test', [
                'menu_pricetypelist' => $pricetypes_user_array,
                'role' => $role,
            ]);
        }
    }

    public function verifyTest($uid) {
        $users = User::where('id', '<>', $uid)
            ->get();
        return view('admin.users.verify-test', compact('users'));
    }

    public function fetchPermission($id) {
        try{
            $user = User::where('id', $id)->first();
            $permissions = ($user->roles && $user->roles->first()) ? $user->roles->first()->permissions : null;
            if(!$permissions){
                throw new Exception('The user permission not found.');
            }else{
                $permissions = $permissions ? $permissions->pluck('id') : null;
            }
            return response()->json(['success' => true, 'permissions' => $permissions]);
        }catch(\Exception $e){
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function approveCustomer($id) {
        try{
            $user = User::find($id);

            $email = '';
            // Make sure you've got the Page model
            if($user) {
                $user->approved = 1;
                $user->save();
                $email = $user->email;
            }

            if ($email != '') {
                \Mail::to($email)->send(new VerificationEmail($user->id));

            }

            return redirect('/admin/users')->with('success', 'Selected users have been approved successfully.');
          
        }catch(\Exception $e){
            dd($e);
            return redirect()->back()->withErrors('Failed approvement.');
        }
    }
}
