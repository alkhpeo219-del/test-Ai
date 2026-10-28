<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected function casts(): array { return ['birth_date' => 'date']; }

    public function department()  { return $this->belongsTo(Department::class); }
    public function user()        { return $this->belongsTo(User::class); }
    public function assignments() { return $this->hasMany(TeachingAssignment::class); }

    public function getFullNameLaoAttribute(): string
    {
        return trim("{$this->first_name_lao} {$this->last_name_lao}");
    }
}
