<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Permission;
use App\Role;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('role_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::all();

        return view('admin.roles.index', compact('roles'));
    }

    public function indexTest($uid)
    {
        $roles = Role::all();

        $query = "SELECT permissions.title FROM (SELECT permission_role.permission_id FROM role_user LEFT JOIN permission_role ON role_user.role_id = permission_role.role_id WHERE role_user.user_id = {$uid}) AS A LEFT JOIN permissions ON A.permission_id = permissions.id WHERE permissions.deleted_at IS NULL";
        $results = DB::select(DB::raw($query));
        $user_role = array();
        foreach ($results as $row) {
            array_push($user_role, $row->title);
        }

        return view('admin.roles.index-test', compact('roles', 'user_role'));
    }

    public function create()
    {
        abort_if(Gate::denies('role_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::all()->pluck('display', 'id');

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(StoreRoleRequest $request, Role $role)
    {
        $role = Role::create($request->all());
        $role->permissions()->sync($request->input('permissions', []));
        
        abort_if(Gate::denies('role_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->load('permissions');

        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        abort_if(Gate::denies('role_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::all()->pluck('display', 'id');

        $role->load('permissions');

        $customers = User::whereHas('roles' , function($qry){
            $qry->where('title', 'not like', '%Admin%');
        })->get()->pluck('name', 'id');

        return view('admin.roles.edit', compact('permissions', 'role', 'customers'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update($request->all());
        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('admin.roles.index');
    }
    
    public function show(Role $role)
    {
        abort_if(Gate::denies('role_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $role->load('permissions');

        return view('admin.roles.show', compact('role'));
    }
    public function destroy( Role $role)
    {
        abort_if(Gate::denies('user_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    
        // Delete roles associated with the user
       
        $userd = DB::table('role_user')
         ->whereIn('role_id', $role->id)
         ->pluck('user_id');

        User::whereIn('id', $userd)->delete();
        Role::whereIn('id', $role->id)->delete();
        return back();
    }
    
    public function selectDestroy(Request $request)
    {
        $ids = $request->input('ids');
        if (!$ids) {
            return redirect()->back()->withErrors('Please select at least one user to delete.');
        }
    
        // Delete roles associated with the selected users
        Role::whereIn('id', $ids)->delete();
    
        $userd = DB::table('role_user')
            ->whereIn('role_id', $ids)
            ->pluck('user_id');

        User::whereIn('id', $userd)->delete();
    
        return redirect()->back()->with('success', 'Selected users have been deleted successfully.');
    }
    
    public function massDestroy(MassDestroyUserRequest $request)
    {
        $ids = request('ids');
    
        // Delete roles associated with the users
        Role::whereIn('id', $ids)->delete();
    
        $userd = DB::table('role_user')
        ->whereIn('role_id', $ids)
        ->pluck('user_id');

        User::whereIn('id', $userd)->delete();
    
        return response(null, Response::HTTP_NO_CONTENT);
    }
    
}
