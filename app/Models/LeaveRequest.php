<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['from_date' => 'date', 'to_date' => 'date']; }

    public function student() { return $this->belongsTo(Student::class); }
    public function approver() { return $this->belongsTo(User::class, 'approved_by'); }
}
