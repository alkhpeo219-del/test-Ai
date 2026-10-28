<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentEvent extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['event_date' => 'date']; }

    public function student() { return $this->belongsTo(Student::class); }
}
