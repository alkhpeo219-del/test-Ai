<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function major()       { return $this->belongsTo(Major::class); }
    public function department()  { return $this->belongsTo(Department::class); }
    public function assignments() { return $this->hasMany(TeachingAssignment::class); }
    public function assessments() { return $this->hasMany(Assessment::class); }
    public function enrollments() { return $this->hasMany(Enrollment::class); }
}
