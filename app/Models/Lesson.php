<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['lesson_date' => 'date']; }

    public function assignment() { return $this->belongsTo(TeachingAssignment::class, 'teaching_assignment_id'); }
    public function materials()  { return $this->hasMany(LessonMaterial::class); }
    public function creator()    { return $this->belongsTo(User::class, 'created_by'); }
}
