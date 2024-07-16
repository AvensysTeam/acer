<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Qrcode;
use App\Backup;
use App\Backuphistory;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Carbon\Carbon;



class QrCodeController extends Controller
{
    public function index(Request $req)
    {
        $email = $req->email;
        $QrCodeModel = Qrcode::where('email', $email)->get();
        
        if ($req->user()->can('service_logbook')) {
            $QrCodeModel = Qrcode::all();
        }

        // Store the data in the session
        Session::put('qrcode_data', $QrCodeModel);

        return view('admin.QRcode', ['qrcode' => $QrCodeModel]);
    }
    public function exportdata()
    {
        $backup = Backup::first(); // Example of fetching the latest backup
        $backuphistory = Backuphistory::orderBy('created_at', 'desc')->take(3)->get();

        //return $backup;
        return view('admin.exportdata', compact('backup', 'backuphistory'));
    }
    
    public function restoreDatabase()
{
    // Path to your SQL file within the app directory
    $sqlFilePath = base_path('DB\avensys.sql');

    // Ensure the SQL file exists
    if (!file_exists($sqlFilePath)) {
        return response()->json(['message' => 'SQL file not found.'], 404);
    }

    // Database credentials
    $dbHost = config('database.connections.mysql.host');
    $dbUsername = config('database.connections.mysql.username');
    $dbPassword = config('database.connections.mysql.password');
    $dbDatabase = config('database.connections.mysql.database');

    

    // Construct the command to import the SQL file
    $command = sprintf(
        'mysql -h %s -u %s %s < %s',
        escapeshellarg($dbHost),
        escapeshellarg($dbUsername),
        escapeshellarg($dbDatabase),
        escapeshellarg($sqlFilePath)
    );

   // return $command;

    $startTime = microtime(true); // Start timing
    $output = [];
    $returnVar = null;
   // return $command;
    exec($command . ' 2>&1', $output, $returnVar);

    $currenttime = Carbon::now();
    Backuphistory::create([
        'backuphistory' => $currenttime,
    ]);

    // if ($returnVar != 0) {
    //     return response()->json(['message' => 'Database restore failed.'], 500);
    // }
    return back();
    //return response()->json(['message' => 'Database restored successfully.']);
}


    
    public function export()
    {
        // Retrieve the data from the session
        $qrcodeData = Session::get('qrcode_data');

        // Reassign IDs to start from 1 and prepare data for export
        $modifiedData = $qrcodeData->map(function ($item, $key) {
            return [
                'Id' => $key + 1,
                'User Email' => $item->email,
                'ProjectName' => $item->projectname,
                'Device Id' => $item->device_id,
                'Serial Id' => $item->serial_id,
                'Firmware Version Id' => $item->firmware_version,
                'Software Version' => $item->software_version,
                'Hardware Version' => $item->hardware_version,
                'KTS' => $item->kts,
                'Counter' => $item->counter,
                'Probes' => $item->probes,
                'AccessoryHW0' => $item->accessoryHW0,
                'AccessoryHW1' => $item->accessoryHW1,
                'AccessoryHW2' => $item->accessoryHW2,
                'AccessoryHW3' => $item->accessoryHW3,
                'MOTDEP' => $item->motdep,
                'Alarm0' => $item->alarm0,
                'Alarm1' => $item->alarm1,
                'Alarm2' => $item->alarm2,
                'Alarm3' => $item->alarm3,
                'Alarm4' => $item->alarm4,
                'Alarm5' => $item->alarm5,
                'Alarm6' => $item->alarm6,
                'Alarm7' => $item->alarm7,
                'Alarm8' => $item->alarm8,
                'Alarm9' => $item->alarm9,
                'Alarm10' => $item->alarm10,
                'Alarm11' => $item->alarm11,
                'Alarm12' => $item->alarm12,
                'Timestamp' => $item->timestamp,
                'Remarks' => $item->remarks,
                'Picture' => $item->picture, // Assuming this holds the path to the picture
            ];
        });

        return Excel::download(new UsersExport(new Collection($modifiedData)), 'qrcode.xlsx');
    }
}
