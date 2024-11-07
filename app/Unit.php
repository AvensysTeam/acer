<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class Unit extends Model
{
    use SoftDeletes;

    public $table = 'units';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'pid',
        'name',
        'pdf',
        'layout',
        'indoor',
        'ex1',
        'ex2',
        'airflow',
        'pressure',
        'Tfin',
        'Trin',
        'Hfin',
        'Hrin',
        'modelId',
        'priceId',
        'price',
        'delivery_time',
        'standard_climatic',
        's_Tfin',
        's_Trin',
        's_Hfin',
        's_Hrin',
        'p_r_airflow',
        'p_r_pressure',
        'p_sfp',
        'm_rfl'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
