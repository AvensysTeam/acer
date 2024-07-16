<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DumpDatabase extends Command
{
    protected $signature = 'database:dump';
    protected $description = 'Dump the database to a file';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $dbHost = config('database.connections.mysql.host');
        $dbUsername = config('database.connections.mysql.username');
        $dbPassword = config('database.connections.mysql.password');
        $dbDatabase = config('database.connections.mysql.database');

        $date = now()->format('Y-m-d_H-i-s');
       // $backupPath = base_path('DB\avensys1.sql');
        $backupPath = base_path("DB/avensys.sql");

        $command = sprintf(
            'mysqldump -h %s -u %s  %s > %s',
            escapeshellarg($dbHost),
            escapeshellarg($dbUsername),
            escapeshellarg($dbDatabase),
            escapeshellarg($backupPath)
        );

        exec($command, $output, $returnVar);
        file_put_contents(base_path('DB/debug.log'), "Command ran at " . now());
        if ($returnVar !== 0) {
            $this->error('Backup failed!');
        } else {
            $this->info('Backup completed successfully.');
        }
    }
}
