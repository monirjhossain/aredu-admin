# OrangeToolz — Laravel Best Practice Migration Plan

**Project:** OrangeToolz Admin (`admin/`)  
**Framework:** Laravel 8.x  
**Goal:** Refactor codebase to Laravel best practices **without breaking production**  
**Approach:** Phase-by-phase (Strangler Fig) — no big-bang rewrite  

**Last updated:** May 2026  

---

## Table of Contents

1. [Target Architecture](#1-target-architecture)
2. [Current State Assessment](#2-current-state-assessment)
3. [Golden Rules](#3-golden-rules)
4. [Smoke Test Checklist](#4-smoke-test-checklist)
5. [Phase 0 — Preparation](#phase-0--preparation)
6. [Phase 1 — Quick Wins](#phase-1--quick-wins)
7. [Phase 2 — Student Module](#phase-2--student-module)
8. [Phase 3 — Application & University](#phase-3--application--university)
9. [Phase 4 — Finance & Invoice](#phase-4--finance--invoice)
10. [Phase 5 — Settings, Post, CMS](#phase-5--settings-post-cms)
11. [Phase 6 — Reports, SMS, Messages](#phase-6--reports-sms-messages)
12. [Phase 7 — API Layer](#phase-7--api-layer)
13. [Phase 8 — Routes, Config, Frontend](#phase-8--routes-config-frontend)
14. [Phase 9 — Quality Gate](#phase-9--quality-gate)
15. [Target Folder Structure](#target-folder-structure)
16. [Timeline](#timeline)
17. [Already Done (Status Refactor)](#already-done-status-refactor)
18. [Risk & Rollback](#risk--rollback)

---

## 1. Target Architecture

Each concern lives in **one place**:

| Layer | Responsibility | Location |
|-------|----------------|----------|
| **Routes** | URL, middleware, route names | `routes/web.php` → split to `routes/web/*.php` |
| **Controllers** | HTTP in/out only (thin: ~20–80 lines per action) | `app/Http/Controllers/Admin/` |
| **Form Requests** | Validation rules & messages | `app/Http/Requests/` |
| **Policies** | Authorization (who can do what) | `app/Policies/` |
| **Services / Actions** | Business logic, notifications, sync | `app/Services/` |
| **Queries** | Complex read/filter/sort (Eloquent builders) | `app/Queries/` |
| **Models** | Relations, scopes, accessors, fillable | `app/Models/` |
| **Support** | Pure helpers (e.g. status tone map) | `app/Support/` |
| **Notifications / Mail** | Alerts & email templates | `app/Notifications/`, `app/Mail/` |
| **Views** | Presentation only | `resources/views/`, `resources/views/components/` |
| **JS** | Inline UI behavior (dropdowns, toastr) | `public/js/admin/` |
| **Exports / Imports** | Excel/CSV (Maatwebsite) | `app/Exports/`, `app/Imports/` |
| **Migrations** | Schema changes only | `database/migrations/` |
| **Tests** | Regression safety | `tests/Feature/Admin/` |

### Request flow (target)

```
Browser → Route → Middleware (auth) → Controller
    → authorize(Policy)
    → FormRequest (validate)
    → Service (business logic)
    → Model / Query
    → Response (view / JSON)
```

---

## 2. Current State Assessment

### Strengths (keep & extend)

- `app/Services/Student/OfficeStatusService.php`
- `app/Services/Student/UniversityApplicationStatusService.php`
- `app/Services/Status/RoleBasedStatusNotifier.php`
- `app/Support/StatusTone.php`
- `app/Queries/StudentCscaQuery.php`
- `app/Policies/StudentPolicy.php` (partial)
- `resources/views/admin/students/partials/inline-status-select.blade.php`
- `public/js/admin/inline-status-select.js`
- View Composer for cached status dropdowns (`ViewServiceProvider`)

### Issues (fix in phases)

| Issue | Example | Priority |
|-------|---------|----------|
| Fat controllers | `StudentController.php` ~800 lines | High |
| God method | `StudentController@index` (list + 2 reports + export) | High |
| Dead / unreachable code | `StudentController::update()` lines after `return` | **Immediate** |
| Legacy files | `*_old.php` controllers | Medium |
| Single routes file | `web.php` ~95 routes | Medium |
| Almost no tests | Only `ExampleTest` | High (from Phase 2) |
| Inconsistent authorization | Only some actions use Policy | High |
| Logic in Blade | `@php`, FQCN in views | Medium |
| Public maintenance routes | `/run_migration`, `/all_cache_clear` | High (security) |
| HTML bloat on list pages | Many full `<select>` per row | Low (scale later) |

### Largest controllers (line count reference)

| File | ~Lines |
|------|--------|
| `Admin/StudentController.php` | 798 |
| `Admin/SettingController.php` | 368 |
| `Admin/PostController.php` | 340 |
| `Admin/ReportsController.php` | 338 |
| `Admin/StudentApplicationController.php` | 313 |
| `Admin/StudentInvoiceController.php` | 303 |
| `Admin/FinanceEntryController.php` | 290 |

---

## 3. Golden Rules

1. **No full rewrite** — one module at a time.
2. **Run smoke tests** after every merge (see §4).
3. **Small Git branches** — e.g. `refactor/student-index-query`.
4. **Do not rename routes** until all `route()` references are updated.
5. **Do not change DB schema** in early phases (code-only refactor).
6. **Delete old code** only when the new path is verified.
7. **Backup database** before production deploy.
8. **Deploy:** local → staging → production.

---

## 4. Smoke Test Checklist

Run after **every phase** (manual, ~15–20 minutes):

```
□ Login / logout
□ Dashboard loads
□ Student list — open, filter, paginate
□ Student list — office status dropdown (name column) + toastr
□ Student list — university application status dropdown
□ Student create
□ Student edit + save
□ Student show
□ Student export (CSV)
□ University report list (?universitylist=1)
□ University application list (?universitylist=1&applicationlist={id})
□ Application status dropdown on report page
□ Invoice list
□ Finance entries list
□ Settings — student status CRUD
□ Settings — university status CRUD
□ Notifications (if used daily)
□ Bulk SMS (if used)
```

Record date + tester name in a simple log (spreadsheet or this doc).

---

## Phase 0 — Preparation

**Duration:** 1–2 days  
**Risk:** Very low  

### Tasks

- [ ] Confirm Git branching (`main` + `refactor/*` branches)
- [ ] Staging `.env` matches production structure
- [ ] Copy §4 smoke checklist to team channel / printout
- [ ] List all `*_old.php` files — move to `app/Http/Controllers/_archive/` (do not delete yet)
- [ ] Document current production URL and admin users for testing

### Optional folder scaffold (empty, no moves yet)

```
app/Actions/
app/Services/{Module}/
app/Queries/
app/Policies/
resources/views/components/
routes/web/
tests/Feature/Admin/
```

---

## Phase 1 — Quick Wins

**Duration:** 3–5 days  
**Risk:** Very low  

### 1.1 Remove dead code

| File | Action |
|------|--------|
| `StudentController::update()` | Remove unreachable code after first `return` (~lines 739–790) |
| All controllers | Remove unused `use` imports (e.g. `PhpParser\...`) |
| `*_old.php` | Move to `_archive/`, ensure no route points to them |

### 1.2 Route hygiene

- Add comments / sections in `routes/web.php` by domain (students, finance, settings, tools).
- Plan split (Phase 8): `routes/web/admin.php`, `auth.php`, `tools.php`.

### 1.3 Security

- Restrict dev-only routes (`/run_migration`, `/all_cache_clear`, `/link_storage`):
  - Only `APP_ENV=local`, or
  - IP whitelist, or
  - Remove from production `web.php` entirely.

### 1.4 Cache invalidation

When student/university statuses are updated in Settings, call:

```php
Cache::forget('dropdown.student_statuses');
Cache::forget('dropdown.university_statuses');
```

(Add in `SettingController` when status tables change.)

### Phase 1 exit criteria

- [ ] Smoke tests pass
- [ ] No new PHP errors in `storage/logs/laravel.log`
- [ ] `StudentController` line count reduced

---

## Phase 2 — Student Module (Template for Other Modules)

**Duration:** 2–3 weeks  
**Risk:** Medium — requires testing  

Use this module as the **reference** for Phases 3–6.

### 2.1 Split controller responsibilities

| Current | Proposed |
|---------|----------|
| `StudentController@index` (list + reports + export) | `StudentListController` or `StudentIndexController` |
| University ways report | `UniversityStudentReportController` |
| CSV export | `StudentExportController` or `ExportStudentsAction` |
| CRUD (create, store, show, edit, update, destroy) | `StudentController` (resource only) |
| `updateOfficeStatus` | Keep on `StudentController` OR `StudentOfficeStatusController` |

### 2.2 Services to add

```
app/Services/Student/
├── OfficeStatusService.php              ✅ exists
├── UniversityApplicationStatusService.php  ✅ exists (used by Application controller)
├── StudentStoreService.php              ← create + relations
├── StudentUpdateService.php             ← update + education + CSCA + sync
└── StudentExportService.php             ← CSV row building
```

**Controller example (target):**

```php
public function update(StudentRequest $request, Student $student)
{
    $this->authorize('update', $student);
    $this->studentUpdateService->handle($student, $request);
    return redirect()->route('students.index')->with('success', 'Updated successfully.');
}
```

### 2.3 Query layer

```
app/Queries/StudentIndexQuery.php
```

Move from `index()`:

- Branch filter
- Counselor, subject, university, intake, status, CSCA, passport, search
- Pagination / ordering

### 2.4 Form requests

```
app/Http/Requests/Student/
├── StudentRequest.php              (existing — refine)
├── UpdateOfficeStatusRequest.php
└── StudentIndexFilterRequest.php
```

### 2.5 Policies

Extend `StudentPolicy`:

| Method | Purpose |
|--------|---------|
| `viewAny` | List students |
| `view` | Show one |
| `create` | Create form |
| `update` | Edit save |
| `delete` | Destroy |
| `updateOfficeStatus` | Inline dropdown ✅ |
| `export` | CSV download |

Apply `$this->authorize()` on every mutating action.

### 2.6 Views

- Keep `resources/views/admin/students/partials/` for large blocks.
- Migrate `inline-status-select` to `resources/views/components/inline-status-select.blade.php` (Blade component with `@props`).
- Move styles to `@push('styles')` or compiled CSS long-term.

### 2.7 Tests (minimum)

```
tests/Feature/Admin/Student/
├── StudentListTest.php
├── UpdateOfficeStatusTest.php
└── StudentPolicyTest.php
```

### Phase 2 exit criteria

- [ ] `StudentController` under ~200 lines (or split into 2–3 controllers)
- [ ] All smoke tests pass
- [ ] At least 3 feature tests green

---

## Phase 3 — Application & University

**Duration:** 1–2 weeks  
**Risk:** Medium  

| File | Target |
|------|--------|
| `StudentApplicationController` | Thin + `UniversityApplicationStatusService` ✅ + Form Requests |
| `StudentUniversityController` | Same pattern as Application |
| Policies | Authorize via parent `Student` |

---

## Phase 4 — Finance & Invoice

**Duration:** 2 weeks  
**Risk:** Medium  

| Component | Target |
|-----------|--------|
| `FinanceEntryController` | `Services/Finance/`, `Queries/FinanceEntryQuery` |
| `StudentInvoiceController` | `Services/Invoice/` |
| `Wallet/*` | Extend existing `WalletService` |

Keep `Exports/` and `Imports/` as-is; call from services.

---

## Phase 5 — Settings, Post, CMS

**Duration:** 1–2 weeks  
**Risk:** Low–medium  

| Component | Target |
|-----------|--------|
| `SettingController` | Split by type or `Settings/*Service` |
| `PostController` | `PostService` + meta handling |
| `CategoryController`, `TagController` | Standard CRUD + policies |

---

## Phase 6 — Reports, SMS, Messages

**Duration:** 1 week  
**Risk:** Low  

| Component | Target |
|-----------|--------|
| `ReportsController` | `Services/Reports/*` |
| `BulkSmsController` | `SmsService`; consider Queue `Job` |
| `MessageController` | Dedicated service / events |

---

## Phase 7 — API Layer

**Duration:** 1 week  
**Risk:** Low  

- Thin `app/Http/Controllers/API/*`
- Use `app/Http/Resources/` for JSON shape
- **Share Services** with web controllers (no duplicate business logic)

---

## Phase 8 — Routes, Config, Frontend

**Duration:** 3–5 days  
**Risk:** Low  

### Route split (`RouteServiceProvider`)

```php
Route::middleware(['web', 'auth'])
    ->group(base_path('routes/web/admin.php'));
```

### Frontend

- `public/js/admin/` — one file per feature (`inline-status-select.js`, etc.)
- Optional later: Laravel Mix / Vite for bundling

### Global

- Audit `app/helpers.php` — move logic to Services where possible

---

## Phase 9 — Quality Gate (Ongoing)

| Practice | When |
|----------|------|
| Smoke checklist (§4) | Every PR merge |
| Feature tests for critical paths | New refactors |
| Larastan / PHPStan level 2+ | After Phase 2 |
| PR template with checklist | Optional |

---

## Target Folder Structure

```
admin/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/           # Thin, module-based
│   │   │   ├── API/
│   │   │   └── Auth/
│   │   ├── Middleware/
│   │   ├── Requests/
│   │   │   └── Admin/Student/
│   │   └── Resources/           # API responses
│   ├── Models/
│   ├── Services/                # Primary business logic
│   │   ├── Student/
│   │   ├── Finance/
│   │   ├── Status/
│   │   └── Wallet/
│   ├── Queries/
│   ├── Policies/
│   ├── Support/
│   ├── Notifications/
│   ├── Mail/
│   ├── Exports/
│   ├── Imports/
│   └── Rules/
├── routes/
│   ├── web.php                  # Eventually slim — loads sub-files
│   └── web/
│       ├── admin.php
│       ├── students.php
│       └── finance.php
├── resources/
│   └── views/
│       ├── admin/{module}/
│       ├── components/
│       └── layouts/
├── public/js/admin/
├── tests/Feature/Admin/
├── database/migrations/
└── docs/
    └── LARAVEL_BEST_PRACTICE_MIGRATION_PLAN.md   ← this file
```

---

## Timeline

| Week | Phase | Risk |
|------|-------|------|
| 1 | 0 + 1 | Very low |
| 2–4 | 2 (Student) | Medium |
| 5–6 | 3 + 4 | Medium |
| 7–8 | 5 + 6 | Low |
| 9 | 7 + 8 | Low |
| Ongoing | 9 | — |

**Estimate:** ~2 months part-time OR ~1 month focused full-time.

---

## Already Done (Status Refactor)

Reference implementation — repeat this pattern elsewhere.

### Inline status UI

| Item | Path |
|------|------|
| Shared partial | `resources/views/admin/students/partials/inline-status-select.blade.php` |
| Styles | `resources/views/admin/students/partials/inline-status-select-styles.blade.php` |
| University inline rows | `resources/views/admin/students/partials/university-applications-inline.blade.php` |
| JavaScript | `public/js/admin/inline-status-select.js` |

### Backend

| Item | Path |
|------|------|
| Office status API | `PATCH students/{student}/office-status` → `StudentController@updateOfficeStatus` |
| Application status API | `PATCH student/application/{application}/status` → `StudentApplicationController@updateStatus` |
| Office service | `app/Services/Student/OfficeStatusService.php` |
| Application service | `app/Services/Student/UniversityApplicationStatusService.php` |
| Role notifier | `app/Services/Status/RoleBasedStatusNotifier.php` |
| Tone map | `app/Support/StatusTone.php` |
| Policy | `app/Policies/StudentPolicy.php` |
| View composer cache | `app/Providers/ViewServiceProvider.php` |

### Two status types (do not confuse)

| UI location | Status type | DB |
|-------------|-------------|-----|
| Name column dropdown | **Office Status** | `students.status` → `student_statuses` |
| University column dropdown | **Application Status** | `student_university.status_id` → `university_statuses` |

---

## Risk & Rollback

| Risk | Mitigation |
|------|------------|
| Broken route names | Grep `route('students.` before rename |
| Policy 403 on AJAX | Return JSON 403; test as branch user |
| Stale dropdown cache | `Cache::forget` on settings save |
| Service binding errors | `php artisan optimize:clear` after deploy |
| Regression | §4 smoke tests + feature tests |

**Rollback:** Git revert merge commit; `php artisan config:clear && php artisan route:clear && php artisan cache:clear`

---

## Progress Tracker

Copy and tick as you go:

```
Phase 0:  [ ] Started  [ ] Done  Date: __________
Phase 1:  [ ] Started  [ ] Done  Date: __________
Phase 2:  [ ] Started  [ ] Done  Date: __________
Phase 3:  [ ] Started  [ ] Done  Date: __________
Phase 4:  [ ] Started  [ ] Done  Date: __________
Phase 5:  [ ] Started  [ ] Done  Date: __________
Phase 6:  [ ] Started  [ ] Done  Date: __________
Phase 7:  [ ] Started  [ ] Done  Date: __________
Phase 8:  [ ] Started  [ ] Done  Date: __________
Phase 9:  [ ] Ongoing
```

---

## Contacts & Notes

| Role | Name | Notes |
|------|------|-------|
| Developer | | |
| Reviewer | | |
| Staging URL | | |
| Production URL | | |

_Add project-specific notes below._

---

---

*This document is the single source of truth for the OrangeToolz Laravel refactor. Update the Progress Tracker and "Already Done" sections as work completes.*
