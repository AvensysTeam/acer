<?php

namespace App;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UtilitiesWarranty extends Model
{

    use SoftDeletes;

    protected $table = 'utilities_warranty';

    protected $fillable = [
        'title',
        'link',
        'is_folder',
        'parent_folder_id',
        'file_path',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function saleUserPermission()  {
        return $this->hasMany(UtilitiesWarrantyUserPermission::class, 'utilities_sale_id', 'id');
    }

}
