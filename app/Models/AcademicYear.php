<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['start_date' => 'date', 'end_date' => 'date', 'is_current' => 'boolean']; }

    public function semesters() { return $this->hasMany(Semester::class); }
    public function classes()   { return $this->hasMany(ClassModel::class); }
    public function students()  { return $this->hasMany(Student::class); }
}
