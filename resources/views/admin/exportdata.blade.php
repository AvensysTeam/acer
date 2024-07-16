<!-- resources/views/admin/exportdata.blade.php -->

@extends('layouts.admin')

@section('content')

<h4>ACER BACK UP MANAGER</h4>
<div class="body">
    <div class="w-full table-responsive">
        <div class="container border p-4 mt-4">
            <div class="btn-group-vertical w-100">
                <button class="btn btn-outline-primary text-left mb-2 border-primary" type="button" data-toggle="collapse" data-target="#collapseButton1" aria-expanded="false" aria-controls="collapseButton1">
                    <i class="fas fa-calendar-alt"></i> Back up scheduler
                </button>
                <div class="collapse w-100" id="collapseButton1">
                    <div class="card card-body w-100 mb-4 border-primary">
                        <form id="backup-scheduler-form" action="{{ route('admin.scheduler') }}" method="POST">
                            @csrf
                            <div class="d-flex justify-content-end">
                                <input type="submit" class="btn btn-primary" value="Apply">
                            </div>
                            <div class="d-flex justify-content-center mb-3">
                                <div class="mr-5">
                                    <label for="hour">Hour</label>
                                    <select name="hour" id="hour" class="form-control" style="border-radius: 15px; background-color: darkgrey; width: 120px;">
                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="mr-5">
                                    <label for="minute">Minute</label>
                                    <select name="minute" id="minute" class="form-control" style="border-radius: 15px; background-color: white; width: 120px;">
                                        @for ($i = 0; $i <= 59; $i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="mr-5">
                                    <label for="ampm">AM/PM</label>
                                    <select name="ampm" id="ampm" class="form-control" style="border-radius: 15px; background-color: #007bff; color: white; width: 120px;">
                                        <option value="AM">AM</option>
                                        <option value="PM">PM</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <button class="btn btn-outline-primary text-left mb-2 border-primary" type="button" data-toggle="collapse" data-target="#collapseButton2" aria-expanded="true" aria-controls="collapseButton2">
                    <i class="fas fa-play-circle"></i> Start a new backup
                </button>
                <div class="collapse show w-100" id="collapseButton2">
                
                    <div class="card card-body w-100 mb-4 border-primary">
                            <div class="d-flex justify-content-end">
                            <a id="backup-button" class="btn btn-primary" href="#">Backup</a>
                            </div>
                        @if($backup)
                            Last backup: {{ $backup->hour }}:{{ str_pad($backup->min, 2, '0', STR_PAD_LEFT) }} {{ $backup->ampm }} {{ $backup->cur }}
                        @else
                            No backups yet.
                        @endif
                    </div>
                </div>
                <button class="btn btn-outline-primary text-left mb-2 border-primary" type="button" data-toggle="collapse" data-target="#collapseButton3" aria-expanded="true" aria-controls="collapseButton3">
                    <i class="fas fa-folder-open"></i> Folder where the data are
                </button>
                <div class="collapse show w-100" id="collapseButton3">
                    <div class="card card-body w-100 mb-4 border-primary">
                        DB/avensys.sql
                    </div>
                </div>
                <button class="btn btn-outline-primary text-left mb-2 border-primary" type="button" data-toggle="collapse" data-target="#collapseButton4" aria-expanded="true" aria-controls="collapseButton4">
                    <i class="fas fa-undo-alt"></i> Restore prev backup
                </button>
                <div class="collapse show w-100" id="collapseButton4">
                    <div class="card card-body w-100 mb-4 border-primary">
                        @if($backuphistory)
                            
                        @for ($i = 0; $i < count($backuphistory); $i++)
                           
                            <ul>
                        
                            <li>Backup {{ chr(65 + $i) }}: {{ \Illuminate\Support\Carbon::parse($backuphistory[$i]->created_at)->format('g:i A d.m.Y') }}</li>
                                <!-- Add more fields as needed -->
                            </ul>
                        @endfor
                        @else
                            No backups yet.
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var ampm = hours >= 12 ? 'PM' : 'AM';

        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'

        document.getElementById('hour').value = hours;
        document.getElementById('minute').value = minutes;
        document.getElementById('ampm').value = ampm;
    });
</script>

<script>
    document.getElementById('backup-button').addEventListener('click', function(event) {
        event.preventDefault(); // Prevent the default action (navigation)

        var userConfirmed = confirm("You clicked on restore previous beckup. \nIf you click continue all your mosdiciation will be disregrded.");
        if (userConfirmed) {
            window.location.href = 'restore-database';
        }
    });
</script>

@endsection
