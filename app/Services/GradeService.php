<?php

namespace App\Services;

use App\Models\Assessment;
use App\Models\Grade;
use App\Models\GradeHistory;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class GradeService
{
    /**
     * Weighted course total for a student: sum(score/max*weight)/sum(weights of assessments with data).
     * Returns ['total' => float|null, 'components' => [...], 'pass' => bool].
     */
    public function courseTotal(Student $student, int $courseId, int $semesterId): array
    {
        $assessments = Assessment::where('course_id', $courseId)
            ->where('semester_id', $semesterId)->get();
        $grades = Grade::whereIn('assessment_id', $assessments->pluck('id'))
            ->where('student_id', $student->id)->get()->keyBy('assessment_id');

        $weighted = 0.0; $weightSum = 0.0; $components = [];
        foreach ($assessments as $a) {
            $g = $grades->get($a->id);
            $components[] = [
                'assessment_id' => $a->id, 'name_lao' => $a->name_lao, 'type' => $a->type,
                'max_score' => (float) $a->max_score, 'weight_percent' => (float) $a->weight_percent,
                'score' => $g && $g->score !== null ? (float) $g->score : null,
            ];
            if ($g && $g->score !== null && (float) $a->weight_percent > 0 && (float) $a->max_score > 0) {
                $weighted += ((float) $g->score / (float) $a->max_score) * (float) $a->weight_percent;
                $weightSum += (float) $a->weight_percent;
            }
        }
        $total = $weightSum > 0 ? round($weighted / $weightSum * 100, 2) : null;
        $passScore = optional($assessments->first())->course
            ? $assessments->first()->course->pass_score : 50;
        return [
            'total' => $total,
            'components' => $components,
            'pass' => $total !== null ? $total >= (float) $passScore : null,
        ];
    }

    /** GPA on 4-point scale from 0-100 totals: A(90+)=4, B(80+)=3, C(70+)=2, D(60+)=1, F(<60)=0 */
    public static function letterGrade(float $total): array
    {
        return match (true) {
            $total >= 90 => ['letter' => 'A', 'point' => 4.0],
            $total >= 80 => ['letter' => 'B', 'point' => 3.0],
            $total >= 70 => ['letter' => 'C', 'point' => 2.0],
            $total >= 60 => ['letter' => 'D', 'point' => 1.0],
            default       => ['letter' => 'F', 'point' => 0.0],
        };
    }

    public function recordGrade(Assessment $assessment, int $studentId, ?float $score, ?string $remark,
                                int $userId, ?string $changeReason = null): Grade
    {
        if ($assessment->is_locked) {
            abort(422, 'ບົດສອບນີ້ຖືກລັອກ (ຢືນຢັນຄະແນນແລ້ວ)');
        }
        if ($score !== null && ($score < 0 || $score > (float) $assessment->max_score)) {
            abort(422, "ຄະແນນຕ້ອງຢູ່ລະຫວ່າງ 0 ຫາ {$assessment->max_score}");
        }
        return DB::transaction(function () use ($assessment, $studentId, $score, $remark, $userId, $changeReason) {
            $grade = Grade::firstOrNew(['assessment_id' => $assessment->id, 'student_id' => $studentId]);
            $old = $grade->exists ? $grade->score : null;
            $grade->score = $score;
            $grade->remark = $remark;
            $grade->recorded_by = $userId;
            $grade->save();
            if ($old !== null && $score !== null && (float) $old !== (float) $score) {
                GradeHistory::create([
                    'grade_id' => $grade->id, 'user_id' => $userId,
                    'old_score' => $old, 'new_score' => $score,
                    'reason' => $changeReason ?? 'ບໍ່ລະບຸເຫດຜົນ',
                    'created_at' => now(),
                ]);
            }
            return $grade;
        });
    }
}
