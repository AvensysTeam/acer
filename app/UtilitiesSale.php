<?php

namespace App;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UtilitiesSale extends Model
{

    use SoftDeletes;

    protected $table = 'utilities_sale';

    protected $fillable = [
        'title',
        'link',
        'is_folder',
        'parent_folder_id',
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
        return $this->hasMany(UtilitiesSaleUserPermission::class, 'utilities_sale_id', 'id');
    }

}
