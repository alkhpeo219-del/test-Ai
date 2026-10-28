<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $guarded = [];
    protected function casts(): array
    {
        return ['due_date' => 'date', 'is_locked' => 'boolean', 'max_score' => 'decimal:2', 'weight_percent' => 'decimal:2'];
    }

    public function course()   { return $this->belongsTo(Course::class); }
    public function semester() { return $this->belongsTo(Semester::class); }
    public function grades()   { return $this->hasMany(Grade::class); }
}
