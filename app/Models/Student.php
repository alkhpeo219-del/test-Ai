<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected function casts(): array { return ['birth_date' => 'date']; }

    public function klass()       { return $this->belongsTo(ClassModel::class, 'class_id'); }
    public function major()       { return $this->belongsTo(Major::class); }
    public function academicYear(){ return $this->belongsTo(AcademicYear::class); }
    public function user()        { return $this->belongsTo(User::class); }
    public function enrollments() { return $this->hasMany(Enrollment::class); }
    public function events()      { return $this->hasMany(StudentEvent::class); }
    public function documents()   { return $this->hasMany(StudentDocument::class); }
    public function attendanceRecords() { return $this->hasMany(AttendanceRecord::class); }
    public function leaveRequests()     { return $this->hasMany(LeaveRequest::class); }

    public function getFullNameLaoAttribute(): string
    {
        return trim("{$this->first_name_lao} {$this->last_name_lao}");
    }
}
