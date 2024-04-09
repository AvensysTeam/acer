<?php

namespace App;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UtilitiesSaleUserPermission extends Model
{

    use SoftDeletes;

    protected $table = 'utilities_sale_user_permission';

    protected $fillable = [
        'utilities_sale_id',
        'role_id'
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

    public function role()  {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}
