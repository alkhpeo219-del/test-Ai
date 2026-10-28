<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $guarded = [];

    public function assignment() { return $this->belongsTo(TeachingAssignment::class, 'teaching_assignment_id'); }
}
