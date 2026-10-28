<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $guarded = [];
    protected function casts(): array
    {
        return ['published_at' => 'datetime', 'is_public' => 'boolean'];
    }

    public function creator() { return $this->belongsTo(User::class, 'created_by'); }

    public function scopePublished($q) { return $q->where('status', 'published'); }
    public function scopePublicVisible($q) { return $q->published()->where('is_public', true); }
}
