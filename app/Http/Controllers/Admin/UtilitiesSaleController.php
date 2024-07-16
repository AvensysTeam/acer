<?php

namespace App\Http\Controllers\Admin;

use Gate;
use App\User;
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
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\UtilitiesAcerStatisticsUserPermission;
use App\UtilitiesCustomerStatisticsUserPermission;
use App\UtilitiesDashboardQualityUserPermission;
use App\UtilitiesDashboardMaintenanceUserPermission;
use App\UtilitiesWarrantyUserPermission;
use App\UtilitiesServiceUserPermission;
use App\UtilitiesInformaticsUserPermission;
use App\UtilitiesSaleUserPermission;
use App\UtilitiesProductionUserPermission;
use App\UtilitiesMaintenanceUserPermission;
use App\UtilitiesQualityUserPermission;
use App\UtilitiesMechanicalsUserPermission;
use App\UtilitiesElectronicsUserPermission;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Storage;

class UtilitiesSaleController extends Controller
{
    public function index(Request $req)
    {
        abort_if(Gate::denies('utilities_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    
        $modelname = $req->modelname;
        $models = [
            'acer_statistics' => UtilitiesAcerStatistics::class,
            'customer_statistics' => UtilitiesCustomerStatistics::class,
            'dashboard_quality' => UtilitiesDashboardQuality::class,
            'dashboard_maintenance' => UtilitiesDashboardMaintenance::class,
            'warranty' => UtilitiesWarranty::class,
            'service' => UtilitiesService::class,
            'informatics' => UtilitiesInformatics::class,
            'sale' => UtilitiesSale::class,
            'production' => UtilitiesProduction::class,
            'maintenance' => UtilitiesMaintenance::class,
            'quality' => UtilitiesQuality::class,
            'mechanicals' => UtilitiesMechanicals::class,
            'electronics' => UtilitiesElectronics::class,
        ];
    
        if (!array_key_exists($modelname, $models)) {
            return redirect()->back()->withErrors(['error' => 'Invalid model name']);
        }
    
        $modelClass = $models[$modelname];
        $result = $modelClass::with('saleUserPermission.user')->orderBy('id', 'DESC')->paginate(10);
        $data = $modelClass::select('id', 'title')->where("is_folder", true)->get()->toArray();
    
        $users = User::get()->pluck('name', 'id');
    
        return view('admin.utilities.index', [
            '_page_title' => trans('global.utilities.utilities'),
            'utilities_sale' => $result,
            'users' => $users,
            'parentUtilities' => $data,
            'model' => $modelname
        ]);
    }
    public function create(Request $req)
    {
        abort_if(Gate::denies('utilities_sale_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $modelMapping = [
            'acer_statistics' => ['class' => UtilitiesAcerStatistics::class, 'permissionClass' => UtilitiesAcerStatisticsUserPermission::class],
            'customer_statistics' => ['class' => UtilitiesCustomerStatistics::class, 'permissionClass' => UtilitiesCustomerStatisticsUserPermission::class],
            'dashboard_quality' => ['class' => UtilitiesDashboardQuality::class, 'permissionClass' => UtilitiesDashboardQualityUserPermission::class],
            'dashboard_maintenance' => ['class' => UtilitiesDashboardMaintenance::class, 'permissionClass' => UtilitiesDashboardMaintenanceUserPermission::class],
            'warranty' => ['class' => UtilitiesWarranty::class, 'permissionClass' => UtilitiesWarrantyUserPermission::class],
            'service' => ['class' => UtilitiesService::class, 'permissionClass' => UtilitiesServiceUserPermission::class],
            'informatics' => ['class' => UtilitiesInformatics::class, 'permissionClass' => UtilitiesInformaticsUserPermission::class], 
            'sale' => ['class' => UtilitiesSale::class, 'permissionClass' => UtilitiesSaleUserPermission::class],
            'production' => ['class' => UtilitiesProduction::class, 'permissionClass' => UtilitiesProductionUserPermission::class],
            'maintenance' => ['class' => UtilitiesMaintenance::class, 'permissionClass' => UtilitiesMaintenanceUserPermission::class],
            'quality' => ['class' => UtilitiesQuality::class, 'permissionClass' => UtilitiesQualityUserPermission::class],
            'mechanicals' => ['class' => UtilitiesMechanicals::class, 'permissionClass' => UtilitiesMechanicalsUserPermission::class],
            'electronics' => ['class' => UtilitiesElectronics::class, 'permissionClass' => UtilitiesElectronicsUserPermission::class],
        ];

        $model_category = $req->model;

        if (!array_key_exists($model_category, $modelMapping)) {
            return response()->json(['error' => 'Invalid model category'], 400);
        }

        $modelClass = $modelMapping[$model_category]['class'];
        $permissionClass = $modelMapping[$model_category]['permissionClass'];
        $tool = new $modelClass();

        $userId = $req->user()->id;

        $rules = [
            'title' => 'required|unique:utilities_sale,title',
            'link' => 'nullable|url',
            'file' => 'nullable|file|mimes:jpg,png,pdf,docx,zip,rar,pptx',
            'users' => 'nullable|array'
        ];

        $validation = Validator::make($req->all(), $rules);
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 500);
        }

        $tool->title = $req->title;
        $tool->is_folder = $req->is_folder ?? 0;

        if ($req->has('parent')) {
            $tool->is_folder = !!$req->is_folder;
            $tool->parent_folder_id = $req->parent;

            if ($req->hasFile('file')) {
                $file = $req->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public');
                $tool->link = "https://acer.avensys-srl.com/storage/" . $filePath;
            } else {
                $tool->link = $req->link;
            }
        } else {
            $tool->link = $req->link;
        }

        if ($req->hasFile('file')) {
            $file = $req->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');
            $tool->title = $req->title;
            $tool->link = "https://acer.avensys-srl.com/storage/" . $filePath;
        }

        $tool->save();

        $permissionClass::create(['utilities_sale_id' => $tool->id, 'user_id' => $userId]);

        if (isset($req->users) && is_array($req->users)) {
            foreach ($req->users as $userId) {
                $permissionClass::create(['utilities_sale_id' => $tool->id, 'user_id' => $userId]);
            }
        }

        if ($tool->is_folder == 1) {
            $permissionClass::create(['utilities_sale_id' => $tool->id, 'user_id' => $userId]);
        }

        return response()->json(['result' => $tool]);
    }

    
    public function update(Request $req, $id)
    {
        abort_if(Gate::denies('utilities_sale_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $modelName = $req->model;
        $modelMapping = [
            'acer_statistics' => ['class' => UtilitiesAcerStatistics::class, 'permissionClass' => UtilitiesAcerStatisticsUserPermission::class],
            'customer_statistics' => ['class' => UtilitiesCustomerStatistics::class, 'permissionClass' => UtilitiesCustomerStatisticsUserPermission::class],
            'dashboard_quality' => ['class' => UtilitiesDashboardQuality::class, 'permissionClass' => UtilitiesDashboardQualityUserPermission::class],
            'dashboard_maintenance' => ['class' => UtilitiesDashboardMaintenance::class, 'permissionClass' => UtilitiesDashboardMaintenanceUserPermission::class],
            'warranty' => ['class' => UtilitiesWarranty::class, 'permissionClass' => UtilitiesWarrantyUserPermission::class],
            'service' => ['class' => UtilitiesService::class, 'permissionClass' => UtilitiesServiceUserPermission::class], // Fixed typo here
            'informatics' => ['class' => UtilitiesInformatics::class, 'permissionClass' => UtilitiesInformaticsUserPermission::class], // Fixed typo here
            'sale' => ['class' => UtilitiesSale::class, 'permissionClass' => UtilitiesSaleUserPermission::class],
            'production' => ['class' => UtilitiesProduction::class, 'permissionClass' => UtilitiesProductionUserPermission::class],
            'maintenance' => ['class' => UtilitiesMaintenance::class, 'permissionClass' => UtilitiesMaintenanceUserPermission::class],
            'quality' => ['class' => UtilitiesQuality::class, 'permissionClass' => UtilitiesQualityUserPermission::class],
            'mechanicals' => ['class' => UtilitiesMechanicals::class, 'permissionClass' => UtilitiesMechanicalsUserPermission::class],
            'electronics' => ['class' => UtilitiesElectronics::class, 'permissionClass' => UtilitiesElectronicsUserPermission::class],
        ];
        $rules = [
            'title' => 'required|unique:utilities_sale,title,'.$id,
            'link' => 'nullable|url',
            'file' => 'nullable|file|mimes:jpg,png,pdf,docx,zip,rar, pptx',
            'users' => 'nullable|array'
        ];
        $validation = Validator::make($req->all(), $rules);
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()], 500);
        }
        if (!array_key_exists($modelName, $modelMapping)) {
            return response()->json(['error' => 'Invalid model category'], 400);
        }
        $modelClass = $modelMapping[$modelName]['class'];
        $permissionClass = $modelMapping[$modelName]['permissionClass'];
        $tool = $modelClass::find($id);
        if (!$tool) {
            return response()->json(['result' => 'The selected utility record not found.'], 500);
        }
        $tool->title = $req->title;
        $tool->link = ($req->parent) ? $req->link : null;
        $tool->parent_folder_id = $req->parent ?? null;
        
        // Handle file upload
        if ($req->hasFile('file')) {
            $file = $req->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');
            $tool->link = "https://acer.avensys-srl.com/storage/" . $filePath;
        }
        
        $tool->save();
        
        if (isset($req->users) && $req->users) {
            $permissionClass::where('utilities_sale_id', $id)->delete();
            $users = array_values(array_filter(array_unique($req->users)));
            foreach ($users as $userId) {
                $permissionClass::create([
                    'utilities_sale_id' => $tool->id,
                    'user_id' => $userId
                ]);
            }
        }
        return response()->json(['result' => $tool]);
    }

    public function show(Request $req, int $id)
    {
        abort_if(Gate::denies('utilities_sale_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $modelName = $req->modelname;
        $models = [
            'acer_statistics' => UtilitiesAcerStatistics::class,
            'customer_statistics' => UtilitiesCustomerStatistics::class,
            'dashboard_quality' => UtilitiesDashboardQuality::class,
            'dashboard_maintenance' => UtilitiesDashboardMaintenance::class,
            'warranty' => UtilitiesWarranty::class,
            'service' => UtilitiesService::class,
            'informatics' => UtilitiesInformatics::class,
            'sale' => UtilitiesSale::class,
            'production' => UtilitiesProduction::class,
            'maintenance' => UtilitiesMaintenance::class,
            'quality' => UtilitiesQuality::class,
            'mechanicals' => UtilitiesMechanicals::class,
            'electronics' => UtilitiesElectronics::class,
        ];

        $tool = $models[$modelName]::with('saleUserPermission')->where('id', $id)->first();
        if(!$tool) return response()->json(['result' => 'The selected utility record not found.'], 500);
        
        return response()->json(['result' => $tool], 200);
    }

    public function delete(Request $req, int $id)
    {
        abort_if(Gate::denies('utilities_sale_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        $modelName = $req->modelname;
        $models = [
            'acer_statistics' => UtilitiesAcerStatistics::class,
            'customer_statistics' => UtilitiesCustomerStatistics::class,
            'dashboard_quality' => UtilitiesDashboardQuality::class,
            'dashboard_maintenance' => UtilitiesDashboardMaintenance::class,
            'warranty' => UtilitiesWarranty::class,
            'service' => UtilitiesService::class,
            'informatics' => UtilitiesInformatics::class,
            'sale' => UtilitiesSale::class,
            'production' => UtilitiesProduction::class,
            'maintenance' => UtilitiesMaintenance::class,
            'quality' => UtilitiesQuality::class,
            'mechanicals' => UtilitiesMechanicals::class,
            'electronics' => UtilitiesElectronics::class,
        ];
    
        if (!array_key_exists($modelName, $models)) {
            return response()->json(['result' => 'Invalid model name.'], 400);
        }
        
        $modelClass = $models[$modelName];
        $existTool = $modelClass::find($id);
    
        if (!$existTool) {
            return response()->json(['result' => 'The selected utility record not found.'], 404);
        }

        $existTool->delete();
        return response()->json(['result' => 'The record deleted successfully.'], 200);
    }
    public function trashdata(Request $req)
    {
        $modelname = $req->modelname;
        $models = [
            'acer_statistics' => UtilitiesAcerStatistics::class,
            'customer_statistics' => UtilitiesCustomerStatistics::class,
            'dashboard_quality' => UtilitiesDashboardQuality::class,
            'dashboard_maintenance' => UtilitiesDashboardMaintenance::class,
            'warranty' => UtilitiesWarranty::class,
            'service' => UtilitiesService::class,
            'informatics' => UtilitiesInformatics::class,
            'sale' => UtilitiesSale::class, 
            'production' => UtilitiesProduction::class,
            'maintenance' => UtilitiesMaintenance::class,
            'quality' => UtilitiesQuality::class,
            'mechanicals' => UtilitiesMechanicals::class,
            'electronics' => UtilitiesElectronics::class,
        ];

        if (!array_key_exists($modelname, $models)) {
            return redirect()->back()->withErrors(['error' => 'Invalid model name']);
        }

        $modelClass = $models[$modelname];
        $result = $modelClass::onlyTrashed()->with('saleUserPermission.user')->orderBy('id', 'DESC')->paginate(10);
        $data = $modelClass::onlyTrashed()->select('id', 'title')->where("is_folder", true)->get()->toArray();

        $users = User::pluck('name', 'id');

        return view('admin.utilities.trashdata', [
            '_page_title' => trans('global.utilities.utilities'),
            'utilities_sale' => $result,
            'users' => $users,
            'parentUtilities' => $data,
            'model' => $modelname
        ]);
    }
    public function trashdelete(Request $req, int $id)
    {
        $modelName = $req->modelname;

        $models = [
            'acer_statistics' => UtilitiesAcerStatistics::class,
            'customer_statistics' => UtilitiesCustomerStatistics::class,
            'dashboard_quality' => UtilitiesDashboardQuality::class,
            'dashboard_maintenance' => UtilitiesDashboardMaintenance::class,
            'warranty' => UtilitiesWarranty::class,
            'service' => UtilitiesService::class,
            'informatics' => UtilitiesInformatics::class,
            'sale' => UtilitiesSale::class,
            'production' => UtilitiesProduction::class,
            'maintenance' => UtilitiesMaintenance::class,
            'quality' => UtilitiesQuality::class,
            'mechanicals' => UtilitiesMechanicals::class,
            'electronics' => UtilitiesElectronics::class,
        ];

        if (!array_key_exists($modelName, $models)) {
            return response()->json(['result' => 'Invalid model name.'], 400);
        }

        $modelClass = $models[$modelName];
        $existTool = $modelClass::onlyTrashed()->find($id);

        if (!$existTool) {
            return response()->json(['result' => 'The selected utility record not found.'], 404);
        }

        try {
            $existTool->forceDelete();
            return response()->json(['result' => 'The record deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['result' => 'Failed to delete the record.', 'error' => $e->getMessage()], 500);
        }
    }
    public function trashrestore(Request $req, int $id)
    {
        $modelName = $req->modelname;

        $models = [
            'acer_statistics' => UtilitiesAcerStatistics::class,
            'customer_statistics' => UtilitiesCustomerStatistics::class,
            'dashboard_quality' => UtilitiesDashboardQuality::class,
            'dashboard_maintenance' => UtilitiesDashboardMaintenance::class,
            'warranty' => UtilitiesWarranty::class,
            'service' => UtilitiesService::class,
            'informatics' => UtilitiesInformatics::class,
            'sale' => UtilitiesSale::class,
            'production' => UtilitiesProduction::class,
            'maintenance' => UtilitiesMaintenance::class,
            'quality' => UtilitiesQuality::class,
            'mechanicals' => UtilitiesMechanicals::class,
            'electronics' => UtilitiesElectronics::class,
        ];

        if (!array_key_exists($modelName, $models)) {
            return response()->json(['result' => 'Invalid model name.'], 400);
        }

        $modelClass = $models[$modelName];
        $existTool = $modelClass::onlyTrashed()->find($id);

        if (!$existTool) {
            return response()->json(['result' => 'The selected utility record not found.'], 404);
        }

        try {
            $existTool->restore();
            return response()->json(['result' => 'The record restored successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['result' => 'Failed to restore the record.', 'error' => $e->getMessage()], 500);
        }
    }
}
