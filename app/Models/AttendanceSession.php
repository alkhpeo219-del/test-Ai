<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    protected $guarded = [];
    protected function casts(): array
    {
        return ['session_date' => 'date', 'token_expires_at' => 'datetime', 'token_used' => 'boolean'];
    }

    public function assignment() { return $this->belongsTo(TeachingAssignment::class, 'teaching_assignment_id'); }
    public function records()    { return $this->hasMany(AttendanceRecord::class); }

    public function isQrValid(?string $token): bool
    {
        return $token !== null
            && hash_equals($this->qr_token, $token)
            && !$this->token_used
            && ($this->token_expires_at === null || $this->token_expires_at->isFuture());
    }
}
