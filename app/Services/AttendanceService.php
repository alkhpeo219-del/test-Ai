<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Enrollment;
use App\Models\TeachingAssignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AttendanceService
{
    public const LATE_GRACE_MINUTES = 10;

    public function createSession(TeachingAssignment $assignment, string $date, string $openTime,
                                  string $closeTime, ?int $expiresMinutes = 15, int $createdBy = null): AttendanceSession
    {
        return AttendanceSession::create([
            'teaching_assignment_id' => $assignment->id,
            'session_date' => $date,
            'open_time' => $openTime,
            'close_time' => $closeTime,
            'qr_token' => bin2hex(random_bytes(24)),
            'token_expires_at' => $expiresMinutes ? now()->addMinutes($expiresMinutes) : null,
            'status' => 'scheduled',
            'created_by' => $createdBy,
        ]);
    }

    /** Check-in inside transaction; duplicate is prevented by unique constraint + pre-check. */
    public function checkIn(AttendanceSession $session, int $studentId, string $method = 'manual',
                            ?string $deviceInfo = null): AttendanceRecord
    {
        return DB::transaction(function () use ($session, $studentId, $method, $deviceInfo) {
            $enrolled = Enrollment::where('student_id', $studentId)
                ->where('course_id', $session->assignment->course_id)
                ->where('semester_id', $session->assignment->semester_id)
                ->where('status', 'active')->exists();
            if (!$enrolled) {
                throw ValidationException::withMessages(['student' => 'ນັກສຶກສາບໍ່ໄດ້ລົງທະບຽນວິຊານີ້ໃນພາກຮຽນນີ້']);
            }
            $existing = AttendanceRecord::where('attendance_session_id', $session->id)
                ->where('student_id', $studentId)->lockForUpdate()->first();
            if ($existing) {
                throw ValidationException::withMessages(['student' => 'ເຊັກຊື່ຊ້ຳແລ້ວໃນການຮຽນຄັ້ງນີ້']);
            }
            $now = now();
            $status = 'present';
            $openAt = \Illuminate\Support\Carbon::parse($session->session_date->format('Y-m-d') . ' ' . $session->open_time);
            $lateCut = $openAt->copy()->addMinutes(self::LATE_GRACE_MINUTES);
            if ($now->greaterThan($lateCut)) $status = 'late';
            return AttendanceRecord::create([
                'attendance_session_id' => $session->id,
                'student_id' => $studentId,
                'status' => $status,
                'method' => $method,
                'checked_at' => $now,
                'device_info' => $deviceInfo,
            ]);
        });
    }

    public function qrCheckIn(AttendanceSession $session, string $token, int $studentId, ?string $deviceInfo = null): AttendanceRecord
    {
        if (!$session->isQrValid($token)) {
            throw ValidationException::withMessages(['token' => 'QR token ບໍ່ຖືກຕ້ອງ, ໝົດອາຍຸ ຫຼື ໃຊ້ໄປແລ້ວ']);
        }
        if ($session->status === 'closed') {
            throw ValidationException::withMessages(['session' => 'ເຊັກຊື່ປິດແລ້ວ']);
        }
        $record = $this->checkIn($session, $studentId, 'qr', $deviceInfo);
        // Token remains reusable during its validity window for the whole class;
        // single-use enforcement is per-student via unique constraint (documented limitation: sharing risk mitigated by login+audit).
        return $record;
    }

    /** Mark absentees after close. */
    public function finalize(AttendanceSession $session): int
    {
        $studentIds = Enrollment::where('course_id', $session->assignment->course_id)
            ->where('semester_id', $session->assignment->semester_id)
            ->where('status', 'active')->pluck('student_id');
        $count = 0;
        DB::transaction(function () use ($session, $studentIds, &$count) {
            foreach ($studentIds as $sid) {
                $exists = AttendanceRecord::where('attendance_session_id', $session->id)->where('student_id', $sid)->exists();
                if (!$exists) {
                    AttendanceRecord::create([
                        'attendance_session_id' => $session->id, 'student_id' => $sid,
                        'status' => 'absent', 'method' => 'manual',
                    ]);
                    $count++;
                }
            }
            $session->update(['status' => 'closed']);
        });
        return $count;
    }
}
