<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Subsection;

class Section extends Model
{
    protected $fillable = ['title' , 'subtitle'];
    public function subsections(){
        return $this->hasMany(Subsection::class);
    }
}
