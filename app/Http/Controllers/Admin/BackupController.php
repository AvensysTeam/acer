<?php

// app/Http/Controllers/Admin/BackupController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Backup; // Ensure the namespace is correct for your Backup model
use Carbon\Carbon; // Use Carbon for date manipulation

class BackupController extends Controller 
{
    public function index()
    {
        $backup = Backup::latest()->first();
        return view('admin.exportdata', compact('backup'));
    }

    public function storeOrUpdate(Request $request)
    {
        $hour = $request->input('hour');
        $min = $request->input('minute');
        $ampm = $request->input('ampm');

        // Convert 12-hour format to 24-hour format
        // Get current date in the desired format
        $date = Carbon::now()->format('m.d.Y');

        // Check if backup entry exists
        $backup = Backup::first();
        
        
        if ($backup) {
            $backup->hour = $hour;
            $backup->min = $min;
            $backup->ampm = $ampm;
            $backup->cur = $date;
            $backup->save();
        } else {
            // Create a new entry
            Backup::create([
                'hour' => $hour,
                'min' => $min,
                'ampm' => $ampm,
                'cur' => $date,
            ]);
        }

        $time = sprintf('%02d:%02d', $hour, $min);
        file_put_contents(base_path('DB/backup_schedule.txt'), $time);


        return back()->with('success', 'Backup schedule updated successfully.');
    }
}
