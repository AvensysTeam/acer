<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\User;
use App\UtilitiesSale;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UtilitiesSaleUserPermission;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UtilitiesSaleController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('utilities_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $result = UtilitiesSale::with('saleUserPermission.user')->orderBy('id', 'DESC')->paginate(10);

        $users = User::whereHas('roles' , function($qry){
            $qry->where('title', 'not like', '%Admin%');
        })->get()->pluck('name', 'id');

        return view('admin.utilities.index', [
            '_page_title' => trans('global.utilities.utilities'),
            'utilities_sale' => $result,
            'users' => $users,
            'parentUtilities' => UtilitiesSale::select('id', 'title')->where("is_folder", true)->get()->toArray()
        ]);
    }

    public function create(Request $req)
    {
        abort_if(Gate::denies('utilities_sale_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $rules = [
            'title' => 'required|unique:utilities_sale,title',
            'link' => 'nullable|url',
            'users' => 'nullable|array'
        ];
        $validation = Validator::make($req->all(), $rules);

        if($validation->fails()){
            return response()->json(['errors' => $validation->errors()], 500);
        } else {
            $tool = new UtilitiesSale();
            $tool->title = $req->title;
            $tool->is_folder = $req->is_folder ?? 0;
            $tool->link = (isset($req->parent) && $req->parent) ? $req->link : null;
            $tool->parent_folder_id = $req->parent ?? null;
            $tool->save();
            if(isset($req->users) && $req->users){
                $users = array_values(array_filter(array_unique($req->users)));
                foreach ($users as $userId) {
                    UtilitiesSaleUserPermission::create([
                        'utilities_sale_id'    => $tool->id,
                        'user_id'       => $userId
                    ]);
                }
            }
            return response()->json(['result' => $tool]);
        }        
    }

    public function update(Request $req, $id)
    {
        abort_if(Gate::denies('utilities_sale_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $rules = [
            'title' => 'required|unique:utilities_sale,title,'.$id,
            'link' => 'nullable|url',
            'users' => 'nullable|array'
        ];
        $validation = Validator::make($req->all(), $rules);

        if($validation->fails()){
            return response()->json(['errors' => $validation->errors()], 500);
        } else {
            $tool = UtilitiesSale::find($id);
            if(!$tool) return response()->json(['result' => 'The selected utility record not found.'], 500);
            $tool->title = $req->title;
            $tool->link = (isset($req->parent) && $req->parent) ? $req->link : null;
            $tool->parent_folder_id = $req->parent ?? null;
            $tool->save();
            if(isset($req->users) && $req->users){
                UtilitiesSaleUserPermission::where('utilities_sale_id', $id)->forceDelete();
                $users = array_values(array_filter(array_unique($req->users)));
                foreach ($users as $userId) {
                    UtilitiesSaleUserPermission::create([
                        'utilities_sale_id'    => $tool->id,
                        'user_id'       => $userId
                    ]);
                }
            }
            return response()->json(['result' => $tool]);
        } 
    }

    public function show(int $id)
    {
        abort_if(Gate::denies('utilities_sale_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $tool = UtilitiesSale::with('saleUserPermission')->where('id', $id)->first();
        if(!$tool) return response()->json(['result' => 'The selected utility record not found.'], 500);
        
        return response()->json(['result' => $tool], 200);
    }

    public function delete(int $id)
    {
        abort_if(Gate::denies('utilities_sale_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $existTool = UtilitiesSale::find($id);
        if(!$existTool) return response()->json(['result' => 'The selected utility record not found.'], 500);
        
        $existTool->delete();
        return response()->json(['result' => 'The record deleted successfully.'], 200);
    }
}
