<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $guarded = [];

    public function program()  { return $this->belongsTo(Program::class); }
    public function courses()  { return $this->hasMany(Course::class); }
    public function classes()  { return $this->hasMany(ClassModel::class); }
    public function students() { return $this->hasMany(Student::class); }
}
