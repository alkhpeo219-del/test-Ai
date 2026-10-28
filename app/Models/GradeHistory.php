<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeHistory extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $fillable = ['grade_id', 'user_id', 'old_score', 'new_score', 'reason', 'created_at'];

    public function grade() { return $this->belongsTo(Grade::class); }
    public function user()  { return $this->belongsTo(User::class); }
}
