<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected function casts(): array { return ['pass_gpa' => 'decimal:2']; }

    public function department() { return $this->belongsTo(Department::class); }
    public function majors()     { return $this->hasMany(Major::class); }
}
