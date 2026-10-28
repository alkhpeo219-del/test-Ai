<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['start_date' => 'date', 'end_date' => 'date', 'is_current' => 'boolean']; }

    public function academicYear()  { return $this->belongsTo(AcademicYear::class); }
    public function assessments()   { return $this->hasMany(Assessment::class); }
    public function enrollments()   { return $this->hasMany(Enrollment::class); }
    public function teachingAssignments() { return $this->hasMany(TeachingAssignment::class); }
}
