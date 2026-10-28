# TODO — EduTrack LMS

## Phase 1 — Audit & Plan ✅
- [x] ອ່ານ repo (ບໍ່ມີ PROJECT_SPEC.md → ສ້າງໃໝ່)
- [x] ຕິດຕັ້ງ PHP 8.2, Composer, MariaDB 10.11, Laravel 12 skeleton
- [x] ສ້າງ DB edutrack utf8mb4 + user
- [x] PROJECT_SPEC.md / IMPLEMENTATION_PLAN.md / TODO.md

## Phase 2 — Setup & Auth & Layout
- [ ] .env/.env.example: MySQL, locale, broadcast, queue, sanctum
- [ ] npm deps (vue3, vue-router, pinia, axios, tailwind3, sweetalert2, tippy.js, vue-tippy, @popperjs/core, chart.js, @vueuse/core, qrcode, html5-qrcode, laravel-echo)
- [ ] Tailwind + Noto Sans Lao + base CSS
- [ ] Auth API (login/logout/me/register/reset/password/profile) + rate limit
- [ ] App layout: sidebar/topbar/breadcrumbs/user menu; Login page

## Phase 3 — Schema/Models/Seeders/RBAC
- [ ] Migrations ຄົບຕາແຜນ + indexes/FK/unique
- [ ] Models + relationships + factories
- [ ] RBAC tables/policies/middleware
- [ ] Seeders Lao data (idempotent, ≥5 records/main table)

## Phase 4 — Core CRUD
- [ ] ພາກວິຊາ, ສົກຮຽນ, ພາກຮຽນ, ຫຼັກສູດ/ສາຂາ, ຫ້ອງຮຽນ, ວິຊາ
- [ ] ນັກສຶກສາ (unique code, photo, events, import/export)
- [ ] ອາຈານ/ພະນັກງານ
- [ ] Pagination/search/filter/sort server-side + modal forms + SweetAlert2 delete

## Phase 5 — Teaching & Attendance
- [ ] teaching assignments, schedules, lessons (draft/published/archived), materials
- [ ] attendance sessions open/close, list check-in, QR token generate/scan, unique+transaction
- [ ] late/absent/excused, leave requests approve, stats reports

## Phase 6 — Grades & Documents & Reports
- [ ] assessments weights/pass, grades entry grid, validation ranges, grade_histories, GPA
- [ ] documents private disk + signed urls + whitelist
- [ ] reports: students/attendance/grades/teaching; CSV/Excel/print; certificate template

## Phase 7 — Realtime/Audit/Backup
- [ ] broadcast events + channels auth + Echo client + polling fallback
- [ ] notification center UI
- [ ] audit log observer + search/filter UI (admin only)
- [ ] backup:artisan command mysqldump+zip+verify+backup_logs+schedule; restore guide

## Phase 8 — UI/UX
- [ ] mobile bottom nav + donut more menu (grid, safe-area)
- [ ] accessible searchable select, tabs, switch, stepper, tags input, skeletons, empty states
- [ ] a11y keyboard + contrast

## Phase 9 — Tests & Perf
- [ ] feature tests (authz, CRUD, constraints, concurrent attendance, grades, uploads, audit)
- [ ] integration vs real MySQL test DB; queue/broadcast tests; backup test
- [ ] eager loading/index review; npm run build passes

## Phase 10 — Docs & Final
- [ ] README.md (Lao): install/env/db/migrate/seed/dev/build/realtime/tests/backup
- [ ] public SEO pages + sitemap + robots
- [ ] final verification report (status honestly)
