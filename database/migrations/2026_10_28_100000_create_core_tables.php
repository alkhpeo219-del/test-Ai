<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── RBAC ──
        Schema::create('roles', function (Blueprint $t) {
            $t->id();
            $t->string('name', 100)->unique();
            $t->string('label_lao', 150);
            $t->text('description')->nullable();
            $t->timestamps();
        });
        Schema::create('permissions', function (Blueprint $t) {
            $t->id();
            $t->string('name', 150)->unique();
            $t->string('module', 80);
            $t->string('action', 40);
            $t->string('label_lao', 150);
            $t->timestamps();
        });
        Schema::create('permission_role', function (Blueprint $t) {
            $t->foreignId('role_id')->constrained()->cascadeOnDelete();
            $t->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $t->primary(['role_id', 'permission_id']);
        });
        Schema::create('role_user', function (Blueprint $t) {
            $t->foreignId('role_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->primary(['role_id', 'user_id']);
        });

        // ── Institution ──
        Schema::create('institution_settings', function (Blueprint $t) {
            $t->id();
            $t->string('key', 100)->unique();
            $t->text('value')->nullable();
            $t->timestamps();
        });
        Schema::create('departments', function (Blueprint $t) {
            $t->id();
            $t->string('code', 30)->unique();
            $t->string('name_lao', 200);
            $t->string('head_name_lao', 150)->nullable();
            $t->string('phone', 30)->nullable();
            $t->string('email', 120)->nullable();
            $t->boolean('is_active')->default(true);
            $t->softDeletes();
            $t->timestamps();
        });
        Schema::create('academic_years', function (Blueprint $t) {
            $t->id();
            $t->string('code', 30)->unique();
            $t->string('name_lao', 150);
            $t->date('start_date');
            $t->date('end_date');
            $t->boolean('is_current')->default(false);
            $t->timestamps();
        });
        Schema::create('semesters', function (Blueprint $t) {
            $t->id();
            $t->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $t->tinyInteger('number');
            $t->string('name_lao', 150);
            $t->date('start_date');
            $t->date('end_date');
            $t->boolean('is_current')->default(false);
            $t->unique(['academic_year_id', 'number']);
            $t->timestamps();
        });
        Schema::create('programs', function (Blueprint $t) {
            $t->id();
            $t->string('code', 30)->unique();
            $t->string('name_lao', 200);
            $t->foreignId('department_id')->constrained()->restrictOnDelete();
            $t->string('level_lao', 80);
            $t->unsignedTinyInteger('duration_years')->default(4);
            $t->unsignedInteger('total_credits')->default(0);
            $t->decimal('pass_gpa', 3, 2)->default(2.00);
            $t->text('description')->nullable();
            $t->enum('status', ['draft', 'published', 'archived'])->default('published');
            $t->softDeletes();
            $t->timestamps();
        });
        Schema::create('majors', function (Blueprint $t) {
            $t->id();
            $t->string('code', 30)->unique();
            $t->string('name_lao', 200);
            $t->foreignId('program_id')->constrained()->restrictOnDelete();
            $t->timestamps();
        });
        Schema::create('courses', function (Blueprint $t) {
            $t->id();
            $t->string('code', 40)->unique();
            $t->string('name_lao', 250);
            $t->foreignId('major_id')->constrained()->restrictOnDelete();
            $t->foreignId('department_id')->constrained()->restrictOnDelete();
            $t->unsignedTinyInteger('credits')->default(2);
            $t->unsignedTinyInteger('year_level')->default(1);
            $t->enum('type', ['theory', 'practice', 'both'])->default('theory');
            $t->decimal('midterm_weight', 5, 2)->default(30);
            $t->decimal('final_weight', 5, 2)->default(40);
            $t->decimal('homework_weight', 5, 2)->default(30);
            $t->unsignedTinyInteger('pass_score')->default(50);
            $t->enum('status', ['draft', 'published', 'archived'])->default('published');
            $t->softDeletes();
            $t->timestamps();
        });
        Schema::create('classes', function (Blueprint $t) {
            $t->id();
            $t->string('code', 40)->unique();
            $t->string('name_lao', 150);
            $t->foreignId('academic_year_id')->constrained()->restrictOnDelete();
            $t->foreignId('major_id')->constrained()->restrictOnDelete();
            $t->unsignedTinyInteger('capacity')->default(40);
            $t->string('room', 60)->nullable();
            $t->timestamps();
        });

        // ── People ──
        Schema::create('teachers', function (Blueprint $t) {
            $t->id();
            $t->string('code', 40)->unique();
            $t->string('first_name_lao', 100);
            $t->string('last_name_lao', 100);
            $t->string('title_lao', 100)->nullable();
            $t->enum('gender', ['male', 'female'])->default('male');
            $t->date('birth_date')->nullable();
            $t->string('phone', 30)->nullable();
            $t->string('email', 120)->nullable();
            $t->foreignId('department_id')->constrained()->restrictOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->enum('employment_status', ['active', 'leave', 'retired', 'resigned'])->default('active');
            $t->text('teaching_experience')->nullable();
            $t->string('photo_path')->nullable();
            $t->softDeletes();
            $t->timestamps();
            $t->index(['department_id', 'employment_status']);
        });
        Schema::create('students', function (Blueprint $t) {
            $t->id();
            $t->string('student_code', 40)->unique();
            $t->string('first_name_lao', 100);
            $t->string('last_name_lao', 100);
            $t->enum('gender', ['male', 'female'])->default('male');
            $t->date('birth_date')->nullable();
            $t->string('phone', 30)->nullable();
            $t->string('email', 120)->nullable();
            $t->string('address_lao', 255)->nullable();
            $t->foreignId('class_id')->nullable()->constrained()->restrictOnDelete();
            $t->foreignId('major_id')->constrained()->restrictOnDelete();
            $t->foreignId('academic_year_id')->constrained()->restrictOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->enum('status', ['studying', 'transferred', 'changed_major', 'leave_of_absence', 'graduated', 'dropped'])->default('studying');
            $t->string('photo_path')->nullable();
            $t->softDeletes();
            $t->timestamps();
            $t->index(['class_id', 'status']);
            $t->index(['major_id', 'status']);
            $t->index(['last_name_lao', 'first_name_lao']);
        });
        Schema::create('enrollments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('student_id')->constrained()->cascadeOnDelete();
            $t->foreignId('course_id')->constrained()->cascadeOnDelete();
            $t->foreignId('semester_id')->constrained()->cascadeOnDelete();
            $t->enum('status', ['active', 'withdrawn'])->default('active');
            $t->unique(['student_id', 'course_id', 'semester_id']);
            $t->timestamps();
        });
        Schema::create('teaching_assignments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $t->foreignId('course_id')->constrained()->cascadeOnDelete();
            $t->foreignId('class_id')->constrained()->cascadeOnDelete();
            $t->foreignId('semester_id')->constrained()->cascadeOnDelete();
            $t->boolean('is_primary')->default(true);
            $t->unique(['teacher_id', 'course_id', 'class_id', 'semester_id'], 'teaching_unique');
            $t->timestamps();
        });
        Schema::create('schedules', function (Blueprint $t) {
            $t->id();
            $t->foreignId('teaching_assignment_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('weekday');
            $t->time('start_time');
            $t->time('end_time');
            $t->string('room', 60)->nullable();
            $t->index(['teaching_assignment_id', 'weekday']);
            $t->timestamps();
        });

        // ── Lessons & materials ──
        Schema::create('lessons', function (Blueprint $t) {
            $t->id();
            $t->foreignId('teaching_assignment_id')->constrained()->cascadeOnDelete();
            $t->date('lesson_date');
            $t->string('title_lao', 250);
            $t->text('plan_content')->nullable();
            $t->text('notes')->nullable();
            $t->enum('status', ['draft', 'taught', 'pending', 'published', 'archived'])->default('draft');
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->index(['teaching_assignment_id', 'lesson_date']);
            $t->timestamps();
        });
        Schema::create('lesson_materials', function (Blueprint $t) {
            $t->id();
            $t->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $t->string('title_lao', 250);
            $t->string('file_path', 500);
            $t->string('mime_type', 120);
            $t->unsignedBigInteger('size_bytes')->default(0);
            $t->enum('kind', ['study', 'teaching'])->default('teaching');
            $t->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
        });

        // ── Attendance ──
        Schema::create('attendance_sessions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('teaching_assignment_id')->constrained()->cascadeOnDelete();
            $t->date('session_date');
            $t->time('open_time');
            $t->time('close_time');
            $t->string('qr_token', 64)->unique();
            $t->timestampTz('token_expires_at')->nullable();
            $t->boolean('token_used')->default(false);
            $t->enum('status', ['scheduled', 'open', 'closed'])->default('scheduled');
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->unique(['teaching_assignment_id', 'session_date'], 'attendance_session_unique');
            $t->index(['session_date', 'status']);
            $t->timestamps();
        });
        Schema::create('attendance_records', function (Blueprint $t) {
            $t->id();
            $t->foreignId('attendance_session_id')->constrained()->cascadeOnDelete();
            $t->foreignId('student_id')->constrained()->cascadeOnDelete();
            $t->enum('status', ['present', 'late', 'absent', 'excused'])->default('present');
            $t->string('method', 20)->default('manual');
            $t->timestamp('checked_at')->nullable();
            $t->string('device_info', 255)->nullable();
            $t->text('correction_reason')->nullable();
            $t->foreignId('corrected_by')->nullable()->constrained('users')->nullOnDelete();
            $t->unique(['attendance_session_id', 'student_id'], 'attendance_record_unique');
            $t->index(['student_id', 'status']);
            $t->timestamps();
        });
        Schema::create('leave_requests', function (Blueprint $t) {
            $t->id();
            $t->foreignId('student_id')->constrained()->cascadeOnDelete();
            $t->foreignId('attendance_session_id')->nullable()->constrained()->nullOnDelete();
            $t->date('from_date');
            $t->date('to_date');
            $t->text('reason_lao');
            $t->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $t->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->index(['student_id', 'status']);
        });

        // ── Grades ──
        Schema::create('assessments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('course_id')->constrained()->cascadeOnDelete();
            $t->foreignId('semester_id')->constrained()->cascadeOnDelete();
            $t->enum('type', ['homework', 'quiz', 'midterm', 'final'])->default('homework');
            $t->string('name_lao', 200);
            $t->decimal('max_score', 6, 2)->default(100);
            $t->decimal('weight_percent', 5, 2)->default(0);
            $t->date('due_date')->nullable();
            $t->boolean('is_locked')->default(false);
            $t->timestamps();
            $t->index(['course_id', 'semester_id']);
        });
        Schema::create('grades', function (Blueprint $t) {
            $t->id();
            $t->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $t->foreignId('student_id')->constrained()->cascadeOnDelete();
            $t->decimal('score', 6, 2)->nullable();
            $t->text('remark')->nullable();
            $t->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $t->unique(['assessment_id', 'student_id'], 'grade_unique');
            $t->index('student_id');
            $t->timestamps();
        });
        Schema::create('grade_histories', function (Blueprint $t) {
            $t->id();
            $t->foreignId('grade_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->decimal('old_score', 6, 2)->nullable();
            $t->decimal('new_score', 6, 2)->nullable();
            $t->text('reason');
            $t->timestamp('created_at')->useCurrent();
            $t->index('grade_id');
        });
        Schema::create('student_events', function (Blueprint $t) {
            $t->id();
            $t->foreignId('student_id')->constrained()->cascadeOnDelete();
            $t->enum('type', ['transfer', 'major_change', 'leave_start', 'leave_end', 'status_change', 'note']);
            $t->string('title_lao', 250);
            $t->text('detail')->nullable();
            $t->date('event_date');
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->index(['student_id', 'type']);
            $t->timestamps();
        });

        // ── Documents ──
        Schema::create('student_documents', function (Blueprint $t) {
            $t->id();
            $t->foreignId('student_id')->constrained()->cascadeOnDelete();
            $t->enum('category', ['personal', 'academic'])->default('academic');
            $t->string('title_lao', 250);
            $t->string('file_path', 500);
            $t->string('original_name', 255);
            $t->string('mime_type', 120);
            $t->unsignedBigInteger('size_bytes')->default(0);
            $t->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $t->softDeletes();
            $t->timestamps();
            $t->index(['student_id', 'category']);
        });

        // ── Announcements / Notifications / Audit / Backup ──
        Schema::create('announcements', function (Blueprint $t) {
            $t->id();
            $t->string('title_lao', 250);
            $t->text('body_lao');
            $t->enum('kind', ['news', 'notice', 'registration'])->default('notice');
            $t->boolean('is_public')->default(false);
            $t->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $t->timestamp('published_at')->nullable();
            $t->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $t->timestamps();
            $t->index(['status', 'published_at']);
        });
        Schema::create('notifications', function (Blueprint $t) {
            $t->id();
            $t->uuid('uuid')->unique();
            $t->foreignId('notifiable_id')->constrained('users')->cascadeOnDelete();
            $t->string('notifiable_type', 120)->default('App\\Models\\User');
            $t->string('type', 150);
            $t->json('data');
            $t->timestamp('read_at')->nullable();
            $t->timestamps();
            $t->index(['notifiable_id', 'read_at']);
        });
        Schema::create('activity_logs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('action', 60);
            $t->string('module', 80);
            $t->string('record_type', 120)->nullable();
            $t->unsignedBigInteger('record_id')->nullable();
            $t->string('description_lao', 500)->nullable();
            $t->json('metadata')->nullable();
            $t->string('ip_address', 45)->nullable();
            $t->string('user_agent', 255)->nullable();
            $t->enum('result', ['success', 'failed'])->default('success');
            $t->timestamp('created_at')->useCurrent();
            $t->index(['module', 'action']);
            $t->index(['user_id', 'created_at']);
        });
        Schema::create('backup_logs', function (Blueprint $t) {
            $t->id();
            $t->string('disk_path', 500);
            $t->unsignedBigInteger('size_bytes')->default(0);
            $t->enum('scope', ['database', 'files', 'full'])->default('full');
            $t->enum('status', ['success', 'failed', 'verified'])->default('success');
            $t->text('verification_note')->nullable();
            $t->foreignId('triggered_by')->nullable()->constrained('users')->nullOnDelete();
            $t->boolean('auto')->default(false);
            $t->timestamps();
        });

        // ── Extra user columns ──
        Schema::table('users', function (Blueprint $t) {
            $t->string('employee_code', 40)->nullable()->unique()->after('id');
            $t->string('first_name_lao', 100)->nullable()->after('name');
            $t->string('last_name_lao', 100)->nullable()->after('first_name_lao');
            $t->string('avatar_path', 500)->nullable()->after('email');
            $t->boolean('is_active')->default(true)->after('avatar_path');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->dropUnique(['employee_code']);
            $t->dropColumn(['first_name_lao', 'last_name_lao', 'employee_code', 'avatar_path', 'is_active']);
        });
        foreach ([
            'backup_logs', 'activity_logs', 'notifications', 'announcements', 'student_documents',
            'student_events', 'grade_histories', 'grades', 'assessments', 'leave_requests',
            'attendance_records', 'attendance_sessions', 'lesson_materials', 'lessons', 'schedules',
            'teaching_assignments', 'enrollments', 'students', 'teachers', 'classes', 'courses', 'majors',
            'programs', 'semesters', 'academic_years', 'departments', 'institution_settings',
            'role_user', 'permission_role', 'permissions', 'roles',
        ] as $tbl) {
            Schema::dropIfExists($tbl);
        }
    }
};
