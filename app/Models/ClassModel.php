<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    protected $table = 'classes';
    protected $guarded = [];

    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
    public function major()        { return $this->belongsTo(Major::class); }
    public function students()     { return $this->hasMany(Student::class, 'class_id'); }
}
