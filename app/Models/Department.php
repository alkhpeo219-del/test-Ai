<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected function casts(): array { return ['is_active' => 'boolean']; }

    public function programs()  { return $this->hasMany(Program::class); }
    public function courses()   { return $this->hasMany(Course::class); }
    public function teachers()  { return $this->hasMany(Teacher::class); }
}
