<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['checked_at' => 'datetime']; }

    public function session() { return $this->belongsTo(AttendanceSession::class, 'attendance_session_id'); }
    public function student() { return $this->belongsTo(Student::class); }
}
