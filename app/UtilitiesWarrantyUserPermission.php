<?php

namespace App;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UtilitiesWarrantyUserPermission extends Model
{

    use SoftDeletes;

    protected $table = 'utilities_warranty_user_permission';

    protected $fillable = [
        'utilities_sale_id',
        'user_id'
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

    public function user()  {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
