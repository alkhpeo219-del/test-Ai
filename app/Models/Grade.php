<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['score' => 'decimal:2']; }

    public function assessment() { return $this->belongsTo(Assessment::class); }
    public function student()    { return $this->belongsTo(Student::class); }
    public function histories()  { return $this->hasMany(GradeHistory::class); }
}
