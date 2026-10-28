# IMPLEMENTATION PLAN — EduTrack LMS

## 0. Environment Audit (ຜົນກວດສອບຈິງ, ວັນທີ 2026-10-28)
| Item | ຜົນ |
|---|---|
| PROJECT_SPEC.md | ❌ ບໍ່ມີໃນ repo ເລີ່ມຕົ້ນ (repo ມີແຕ່ README.md) → ສ້າງຂຶ້ນໃໝ່ຈາກຄຳສັ່ງ |
| OS | Debian 12 bookworm |
| PHP | ບໍ່ມີເລີ່ມຕົ້ນ → ຕິດຕັ້ງ PHP 8.2.35 CLI + mbstring/xml/curl/mysql/zip/sqlite3/bcmath |
| Composer | ບໍ່ມີ → ຕິດຕັ້ງ 2.10.3 |
| Laravel | ບໍ່ມີ → `laravel/laravel` skeleton = **Laravel 12.70.2** (ເໝາະກັບ PHP 8.2) |
| Node/npm | v20.20.2 / 10.8.2 ✅ |
| MySQL server | ບໍ່ມີ → ຕິດຕັ້ງ **MariaDB 10.11.19** (MySQL-compatible; mysqldump ໃຊ້ mariadb-dump). DB `edutrack` utf8mb4_unicode_ci + user `edutrack` ✅ |
| Network | packagist/npm reachable ✅ |

## 1. Architecture
- **SPA ທີ່ serve ດ້ວຍ Laravel**: Blade root template (`resources/views/app.blade.php`) ຕິດ Vue app; Backend ເປັນ JSON API (`routes/api.php` under `/api`, session-cookie auth ຜ່ານ Sanctum stateful + CSRF) — ຮອງຮັບ Mobile App ໃນອະນາຄົດ (Sanctum tokens).
- **Public SEO pages** ເປັນ Blade SSR (ໜ້າຫຼັກ/ສະຖາບັນ/ຫຼັກສູດ/ຂ່າວ/ຕິດຕໍ່) + sitemap.xml + robots.txt + meta/OG/JSON-LD.
- Frontend: Vue 3 `<script setup>` + vue-router (history mode, catch-all `/{any}`) + Pinia + Axios.
- Realtime: Laravel Broadcasting events on private channels; driver config ໃຫ້ Reverb/Pusher; **fallback polling service** ເມື່ອ WebSocket ບໍ່ available (ບັນທຶກເຫດຜົນ: environment ນີ້ run Reverb ໄດ້ແຕ່ client browser ອາດຕັດ → ຕ້ອງມີ fallback).
- Queue: `database` driver + `php artisan queue:work` (notifications/report generation).

### Library Compatibility Decisions (ກວດສອບ Vue 3 ແລ້ວ)
| Requirement | Choice | Reason |
|---|---|---|
| SweetAlert2 | ✅ `sweetalert2@11` | framework-agnostic, Vue 3 OK |
| Floating UI | ✅ `@popperjs/core@2` (+ tippy.js which bundles it) | engine for tooltips/dropdown positioning |
| Tippy.js | ✅ `tippy.js@6` + `vue-tippy@6` (Vue 3 support) | |
| Animated numbers | ✅ `chart.js@4` + custom `useCountUp` composable | "Number Flow" ເປັນ React-only → ສ້າງ composable ເອງ (documented alternative) |
| Gestures | ✅ `@vueuse/core@10` (`useSwipe`, `useDraggable`) | use-gesture/react = React-only → alternative ສະຖຽນ |
| QR | `qrcode@1` (generate), `html5-qrcode@2` (scan) | framework-agnostic |
| Stepper/Switch/Tabs/TagsInput/Modal | ສ້າງເອງ (reusable components) | ຫຼຸດ dependency risk |
| TypeScript | ໃຊ້ JS + JSDoc ໃນ project ໃໝ່ນີ້ | skeleton ເປັນ JS; ຮັກສາ build ງ່າຍ/ໄວ; ໂຄງສ້າງ `types/` ສຳລັບ typedefs (documented decision) |

## 2. Database Schema (Migrations)
users(+profile fields) · roles · permissions · permission_role · role_user · institution_settings ·
departments · academic_years · semesters · programs(ຫຼັກສູດ) · majors(ສາຂາວິຊາ, level) · courses(ວິຊາ) ·
classes · enrollments(student↔course/class, unique) · teachers(unique code) · teaching_assignments(unique teacher+course+semester) ·
schedules(day,start,end,room; index) · lessons(status draft/published/archived) · lesson_materials ·
attendance_sessions(unique token, open/close time, status) · attendance_records(status present/late/absent/excused; unique(session,student)) ·
leave_requests(status pending/approved/rejected) · assessments(type/homework/mid/final, weight,max,pass) ·
grades(unique assessment+student; total computed in service) · grade_histories · student_events(ປະຫວັດ: transfer/major-change/leave) ·
student_documents(private disk) · announcements · notifications · activity_logs(immutable) · backup_logs.
All InnoDB utf8mb4_unicode_ci, FK cascade rules ຕາມ semantics, indexes ຕາມ search/filter columns, softDeletes ຕາມຄວາມເໝາະສົມ.

## 3. RBAC
Permissions per module × action (view/create/edit/delete/approve/export/manage). Roles seed: Super Admin (*), ຜູ້ບໍລິຫານ, ຫົວໜ້າພາກວິຊາ, ອາຈານ, ພະນັກງານ, ນັກສຶກສາ. Policies + `can:` middleware; backend checks everywhere.

## 4. Development Phases & Status
- [x] Phase 1: Environment audit, PROJECT_SPEC.md, IMPLEMENTATION_PLAN.md, TODO.md
- [ ] Phase 2: Config (.env MySQL), Tailwind, Vue/Vite base, Auth API, layouts
- [ ] Phase 3: Migrations, Models, Relationships, Seeders (Lao data ≥5/table), RBAC
- [ ] Phase 4: Core CRUD modules (institution/departments/years/semesters/programs/majors/classes/courses/students/teachers)
- [ ] Phase 5: Schedules, lessons, assignments, attendance + QR
- [ ] Phase 6: Grades/assessments/GPA, documents, reports (PDF/CSV/print certificate)
- [ ] Phase 7: Notifications+broadcast, audit logs UI, backup command
- [ ] Phase 8: UI/UX refinement, mobile nav (bottom + donut more menu), selects, a11y
- [ ] Phase 9: Tests (backend feature/unit + integration vs MySQL), perf hardening
- [ ] Phase 10: Docs (README Lao), production build, final verification

## 5. Testing Plan
Pest/PHPUnit feature tests hitting real MariaDB test DB (`edutrack_test`): auth, RBAC denial, CRUD validation, unique constraints (student code, duplicate attendance, concurrent check-in), grade calc, upload whitelist, signed URL access, audit immutability, backup command produces verifiable dump. Frontend verified via `npm run build` + component smoke through HTTP integration tests.

## 6. Known Limitations (ລາຍງານຕາມຄວາມເປັນຈິງ)
- QR attendance cannot guarantee identity 100% → mitigations: login-required scan, session-bound expiring single-use tokens, device/user-agent logging, audit trail.
- Reverb requires port 8080 process; if unavailable the UI falls back to polling (no full-page reload).
- PDF export: implemented via print-optimized HTML templates (browser print-to-PDF) + CSV/Excel(HTML/xls) exports — avoids heavy composer deps; documented as deliberate choice.
