<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \DateTimeInterface;

class Role extends Model
{
    use SoftDeletes;

    public $table = 'roles';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'title',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    private $registerRoles = [
        "Administrator",
        "Engineer",
        "Technician",
        "Production operator",
        "Manager",
        "Consultant",
        "Analyst",
        "Developer",
        "Designer",
        "Architect",
        "Researcher",
        "Student",
        "Teacher/Professor",
        "Sales representative",
        "Entrepreneur",
        "HR manager",
        "Customer service agent",
        "Salesperson",
        "Self-employed/Freelancer"
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function getOtherRoles() {
        return $this->registerRoles;
    }
}
