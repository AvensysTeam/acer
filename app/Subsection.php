<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Section;

class Subsection extends Model
{
    protected $fillable = ['title' , 'subtitle' , 'section_id'];

    public function section(){
        return $this->belongsTo(Section::class);
    }
}
