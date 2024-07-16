<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class Qrcode extends Model
{
    use SoftDeletes;

    public $table = 'qrcode';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'email',
        'device_id',
        'serial_id',
        'firmware_version',
        'software_version',
        'hardware_version',
        'kts',
        'counter',
        'probes',
        'accessoryHW0',
        'accessoryHW1',
        'accessoryHW2',
        'accessoryHW3',
        'motdep',
        'alarm0',
        'alarm1',
        'alarm2',
        'alarm3',
        'alarm4',
        'alarm5',
        'alarm6',
        'alarm7',
        'alarm8',
        'alarm9',
        'alarm10',
        'alarm11',
        'alarm12',
        'timestamp',
        'projectname',
        'remarks',
        'picture',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}
