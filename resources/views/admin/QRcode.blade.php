@extends('layouts.admin')
@section('content')

QRcode
<div class="body">
    <div class="w-full table-responsive">
        <a href='exportexcel' class="btn btn-success mb-3">
            <i class="fas fa-file-excel"></i> Export to Excel
        </a>
        <table class="stripe hover bordered datatable datatable-Permission">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>ProjectName</th>
                    <th>Remarks</th>
                    <th>Image</th>
                    <th>Device Id</th>
                    <th>Serial Id</th>
                    <th>Firmware Version Id</th>
                    <th>Software Version</th>
                    <th>Hardware Version</th>
                    <th>KTS</th>
                    <th>Counter</th>
                    <th>Probes</th>
                    <th>AccessoryHW0</th>
                    <th>AccessoryHW1</th>
                    <th>AccessoryHW2</th>
                    <th>AccessoryHW3</th>
                    <th>MOTDEP</th>
                    <th>Alarm0</th>
                    <th>Alarm1</th>
                    <th>Alarm2</th>
                    <th>Alarm3</th>
                    <th>Alarm4</th>
                    <th>Alarm5</th>
                    <th>Alarm6</th>
                    <th>Alarm7</th>
                    <th>Alarm8</th>
                    <th>Alarm9</th>
                    <th>Alarm10</th>
                    <th>Alarm11</th>
                    <th>Alarm12</th>
                    <th>Timestamp</th>
                    
                </tr>
            </thead>
            <tbody>
                @if($qrcode->count())
                @foreach($qrcode as $qr)
                <tr data-entry-id="{{ $qr->id }}">
                    <td align="center">
                        {{ str_pad($loop->iteration, 1, '0', STR_PAD_LEFT) }}
                    </td>
                    <td align="center">
                        {{ $qr->projectname }}
                    </td>
                    <td align="center">
                        {{ $qr->remarks }}
                    </td>
                    <td align="center">
                        @if($qr->picture)
                            <a href="{{ asset('storage/uploads/' . $qr->picture) }}" target="_blank">
                                <i class="fas fa-thumbtack"></i>
                            </a>
                        @else
                            
                        @endif
                    </td>
                    
                    <td align="center">
                        {{ $qr->device_id }}
                    </td>
                    <td align="center">
                        {{ $qr->serial_id }}
                    </td>
                    <td align="center">
                        {{ $qr->firmware_version }}
                    </td>
                    <td align="center">
                        {{ $qr->software_version }}
                    </td>
                    <td align="center">
                        {{ $qr->hardware_version }}
                    </td>
                    <td align="center">
                        {{ $qr->kts }}
                    </td>
                    <td align="center">
                        {{ $qr->counter }}
                    </td>
                    <td align="center">
                        {{ $qr->probes }}
                    </td>
                    <td align="center">
                        {{ $qr->accessoryHW0 }}
                    </td>
                    <td align="center">
                        {{ $qr->accessoryHW1 }}
                    </td>
                    <td align="center">
                        {{ $qr->accessoryHW2 }}
                    </td>
                    <td align="center">
                        {{ $qr->accessoryHW3 }}
                    </td>
                    <td align="center">
                        {{ $qr->motdep }}
                    </td>
                    <td align="center">
                        {{ $qr->alarm0 }}
                    </td>
                    <td align="center">
                        {{ $qr->alarm1 }}
                    </td>
                    <td align="center">
                        {{ $qr->alarm2 }}
                    </td>
                    <td align="center">
                        {{ $qr->alarm3 }}
                    </td>
                    <td align="center">
                        {{ $qr->alarm4 }}
                    </td>
                    <td align="center">
                        {{ $qr->alarm5 }}
                    </td>
                    <td align="center">
                        {{ $qr->alarm6 }}
                    </td>
                    <td align="center">
                        {{ $qr->alarm7 }}
                    </td>
                    <td align="center">
                        {{ $qr->alarm8 }}
                    </td>
                    <td align="center">
                        {{ $qr->alarm9 }}
                    </td>
                    <td align="center">
                        {{ $qr->alarm10 }}
                    </td>
                    <td align="center">
                        {{ $qr->alarm11 }}
                    </td>
                    <td align="center">
                        {{ $qr->alarm12 }}
                    </td>
                    <td align="center">
                        {{ $qr->timestamp }}
                    </td>
                   
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="31" class="text-center">No data found.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection