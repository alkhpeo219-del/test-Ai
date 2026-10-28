<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeachingAssignment extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['is_primary' => 'boolean']; }

    public function teacher()   { return $this->belongsTo(Teacher::class); }
    public function course()    { return $this->belongsTo(Course::class); }
    public function klass()     { return $this->belongsTo(ClassModel::class, 'class_id'); }
    public function semester()  { return $this->belongsTo(Semester::class); }
    public function schedules() { return $this->hasMany(Schedule::class); }
    public function lessons()   { return $this->hasMany(Lesson::class); }
    public function attendanceSessions() { return $this->hasMany(AttendanceSession::class); }
}
