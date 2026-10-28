<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AuditService
{
    /** Sensitive keys must NEVER be written to logs. */
    private const REDACTED = ['password', 'password_confirmation', 'token', 'qr_token',
        'remember_token', 'access_token', 'refresh_token', 'secret', 'session'];

    public function log(?int $userId, string $action, string $module, ?Model $record = null,
                        string $descriptionLao = '', array $metadata = [], string $result = 'success',
                        ?Request $request = null): ActivityLog
    {
        $request ??= request();
        foreach ($metadata as $k => $v) {
            if (in_array(strtolower($k), self::REDACTED, true)) {
                $metadata[$k] = '[REDACTED]';
            }
        }
        return ActivityLog::create([
            'user_id'        => $userId,
            'action'         => $action,
            'module'         => $module,
            'record_type'    => $record ? class_basename($record) : null,
            'record_id'      => $record?->id,
            'description_lao'=> mb_substr($descriptionLao, 0, 500),
            'metadata'       => $metadata ?: null,
            'ip_address'     => $request?->ip(),
            'user_agent'     => mb_substr((string) $request?->userAgent(), 0, 255),
            'result'         => $result,
            'created_at'     => now(),
        ]);
    }
}
