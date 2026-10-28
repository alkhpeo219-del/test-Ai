# PROJECT SPEC — EduTrack LMS

> ເອກະສານນີ້ຖືກສ້າງຂຶ້ນໃນ Phase 1 ເພາະ Repository ເລີ່ມຕົ້ນບໍ່ມີ PROJECT_SPEC.md.
> ມັນຖືກສະຫຼຸບຈາກຄຳສັ່ງພັດທະນາ (User Command) ແລະ ເປັນເອກະສານຂໍ້ກຳນົດຫຼັກ.

## 1. ວິໄສທັດ
EduTrack LMS ແມ່ນລະບົບບໍລິຫານ ແລະ ຕິດຕາມການຮຽນການສອນ (School Learning & Teaching Management System)
ສຳລັບສະຖາບັນການສຶກສາ 1 ແຫ່ງ: Web Application ທີ່ສວຍງາມ, ທັນສະໄໝ, ປອດໄພ, ໄວ, ຮອງຮັບ Multi-user
ແລະ ນຳໄປຕິດຕັ້ງໃຊ້ງານຈິງໄດ້. ຫ້າມເປັນພຽງ UI Demo/Static HTML — ທຸກ Feature ຕ້ອງເຊື່ອມຕໍ່ Backend + Database ຈິງ.

## 2. Technology Stack
- Backend: Laravel 12 (PHP 8.2), Eloquent ORM, Form Requests, Policies, Middleware, Events/Listeners, Jobs/Queues, Sanctum (API-ready).
- Frontend: Vue 3 (Composition API, `<script setup>`), Vite, Pinia, Axios, TypeScript-friendly structure (JS + JSDoc; ບັນທຶກເຫດຜົນໃນ IMPLEMENTATION_PLAN.md).
- Database: MySQL/MariaDB ຈິງ, utf8mb4_unicode_ci, Migrations + Seeders, FK/Index/Unique/Transactions.
- UI: Tailwind CSS 3, SweetAlert2, Tippy.js (+@popperjs/core = Floating UI engine), Chart.js (animated numbers/dashboard), @vueuse/core (gesture/touch composables — ແທນ use-gesture ເພາະເປັນ React-only), QRCode (generate) + html5-qrcode (scan), Stepper/Switch/Tabs/TagsInput/Modal ສ້າງເອງເປັນ Reusable Components.
- Realtime: Laravel Broadcasting (Reverb/Pusher config; fallback polling ໃນ environment ທີ່ບໍ່ມີ WebSocket server).

## 3. ພາສາລາວ
- UI, Menu, Labels, Validation, Notifications, Reports ແລະ ຂໍ້ມູນຕົວຢ່າງທັງໝົດເປັນພາສາລາວ.
- Font: Noto Sans Lao (Google Fonts) ເປັນຟອນຫຼັກ.
- MySQL utf8mb4; ຮູບແບບວັນທີ ວ/ດ/ຢ ຄ.ສ., ເວລາ 24 ຊມ., ຕົວເລກ千分隔按ລາວ (,).
- Search/Collation: ຄົ້ນຫາພາສາລາວດ້ວຍ LIKE utf8mb4_unicode_ci.

## 4. ໂມດູນ
1. Dashboard: ຕົວເລກນັກສຶກສາ/ອາຈານ/ຫ້ອງ/ຫຼັກສູດ, ສະຖິຕິເຂົ້າຮຽນ, ກຣາຟ, ວຽກຈະມາເຖິງ, notifications — ຂໍ້ມູນຈາກ DB ຈິງ.
2. ຂໍ້ມູນສະຖາບັນ: ສະຖາບັນ, ພາກວິຊາ, ສົກຮຽນ/ພາກຮຽນ, ຫຼັກສູດ/ສາຂາວິຊາ/ລະດັບ, ຫ້ອງຮຽນ, ຕາຕະລາງຮຽນ, ວິຊາ + ການມອບໝາຍອາຈານ.
3. ນັກສຶກສາ: ລະຫັດ (unique), ຊື່/ນາມສະກຸນ/ເພດ/ວັນເກີດ/ຕິດຕໍ່, ສາຂາ, ຫ້ອງ, ສົກຮຽນ, ສະຖານະ, ຮູບ, ປະຫວັດ (yaintaing/switch/major leave), ຄົ້ນຫາ/ກອງ/import/export ຕາມສິດ.
4. ອາຈານ-ພະນັກງານ: ລະຫັດ unique, ຕຳແໜ່ງ, ພາກວິຊາ, ປະຫວັດການສອນ, ສະຖານະເຮັດວຽກ, RBAC.
5. ການຮຽນການສອນ: ວິຊາ/ຫຼັກສູດ/ຕາຕະລາງ, ມອບໝາຍອາຈານ, ແຜນການສອນ/ບົດຮຽນ/ສື່, ບັນທຶກການສອນຈິງ, Draft/Published/Archived.
6. ເຊັກຊື່: session ຕາມວິຊາ/ຫ້ອງ/ວັນ/ເວລາ/ອາຈານ, ເຊັກຜ່ານລາຍຊື່ + QR token (bind session, expiry, single-use), unique(student,session) + transaction, ເປີດ/ປິດເວລາ, ສະຖານະ present/late/absent/excused, ລາ-ອະນຸມັດ, ລາຍງານ. ⚠️ QR ຢ່າງດຽວຮັບປະກັນ 100% ບໍ່ໄດ້ — ຕ້ອງ login + token + audit.
7. ຄະແນນ: homework/midterm/final + ຄະແນນລວມ, ນ້ຳໜັກ/ເກນຜ່ານຕາມຫຼັກສູດ, validate 0..max, grade_histories, GPA, ສິດບັນທຶກ/ຢືນຢັນ/ແກ້ໄຂ.
8. ເອກະສານ/ສື່: upload/download/preview/replace/delete ຕາມສິດ, whitelist MIME/ext/size, private storage disk, signed URLs. ຫ້າມເກັບໄຟລ໌ສ່ວນຕົວໃນ public.
9. ລາຍງານ: ລາຍຊື່ນັກສຶກສາ, ການເຂົ້າຮຽນ, ຄະແນນ, ການສອນ; PDF/Excel/CSV; ໃບປະກາດ template (ໂລໂກ້/ຊື່/ລາຍເຊັນ/ເລກອ້າງອີງ); ກວດສິດກ່ອນ download.
10. Users/Roles: login/logout/reset/profile, RBAC + policies, create roles + permissions (view/create/edit/delete/approve/export), Super Admin/ຜູ້ບໍລິຫານ/ຫົວໜ້າພາກ/ອາຈານ/ພະນັກງານ/ນັກສຶກສາ. Backend ຕ້ອງກວດສິດທຸກຄັ້ງ.
11. Notifications/Realtime: Broadcast channel auth, Laravel Echo, reconnect + fallback poll, update component ບໍ່ reload ທັງໜ້າ.
12. Audit Logs: user/action/module/record_id/result/timestamp, ຄົ້ນຫາ+ກອງ, ຫ້າມ user ທົ່ວໄປແກ້/ລຶບ, ຫ້າມ log password/token.
13. Backup/Restore: ຄຳສັ່ງ automated mysqldump + files zip, schedule, retention, verification (gzip -t / restore dry-run), backup_logs, manual ຄູ່ມື.

## 5. UI/UX
Modern Education SaaS Dashboard: ພື້ນຂາວ/ເທົາ, ຟ້າ+ຂຽວ+accent, Card/rounded/shadow/spacing ສະໝ່ຳສະເໝີ,
Sidebar (desktop) + Top nav + Breadcrumbs + Profile; Mobile: Bottom Navigation 4–5 items + More (donut) menu grid,
safe areas, responsive tables/cards, touch-friendly. Custom Accessible Select/Dropdown (searchable, keyboard, z-index ກວດແລ້ວ),
Modal Forms ທຸກ CRUD (client+server validation, inline errors, loading, double-submit guard, unsaved-close confirm),
Skeleton/Empty/Error states, SweetAlert2 ທຸກ delete/confirm. ຫ້າມມີປຸ່ມທີ່ບໍ່ມີ function.

## 6. CRUD/Pagination/Search
Server-side pagination 10/页 default, total/current/prev/next/page numbers, search+filter+sort ຮັກສາໃນ query string,
FK check ກ່ອນ delete, soft deletes ສຳລັບຂໍ້ມູນປະຫວັດ, SweetAlert2 confirm.

## 7. Security
Hashed passwords, CSRF, XSS/SQLi prevention, secure sessions, rate limiting (login/reset/api), backend authorization ທຸກ endpoint,
transactions, eager loading (N+1), indexes, cache ສົມເຫດສົມຜົນ, queues ວຽກໜັກ, APP_DEBUG=false production, secrets in .env (gitignored).

## 8. SEO (Public Pages)
ໜ້າສະຖາບັນ/ຫຼັກສູດ/ຂ່າວ-ປະກາດ/ຕິດຕໍ່-ຮັບສະໝັກ: Title/Meta/Canonical/OG, sitemap.xml, robots.txt (Disallow ໜ້າ login/dashboard),
semantic HTML, structured data. ຫ້າມ index ໜ້າຕ້ອງ login; ຄວາມປອດໄພໃຊ້ auth ຫຼຸມ (ບໍ່ແມ່ນ robots.txt).

## 9. Testing
Backend: authz, CRUD+validation, relations/unique, duplicate/concurrent attendance, grade calc, file upload, notifications, audit.
Frontend: components/states/validation. Integration: FE→BE→MySQL real roundtrip, events/jobs, backup verify.
ຫ້າມລາຍງານ test ຜ່ານຖ້າບໍ່ run ຈິງ.

## 10. Definition of Done
ຕາມ User Command ມ.16 — ລວມ `npm run build` ຜ່ານ, `php artisan test` ຜ່ານ, docs ຄົບ.
