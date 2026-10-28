<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    public $timestamps = false;
    protected $guarded = [];
    protected $fillable = [
        'user_id', 'action', 'module', 'record_type', 'record_id', 'description_lao',
        'metadata', 'ip_address', 'user_agent', 'result', 'created_at',
    ];

    protected function casts(): array
    {
        return ['metadata' => 'array', 'created_at' => 'datetime'];
    }

    public function user() { return $this->belongsTo(User::class); }

    // Audit logs are immutable: mass update/delete via this model is blocked.
    public function delete(): bool
    {
        return false;
    }
}
