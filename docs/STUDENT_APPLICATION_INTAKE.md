# Student Application — Intake Feature

**Project:** OrangeToolz Admin  
**Last updated:** June 2026  
**Scope:** New Application form এ Intake dropdown; `student_university.intake` column; profile এ application-wise intake display

**Related:** [STUDENT_DETAILS_UNIVERSITY_SUBJECT.md](./STUDENT_DETAILS_UNIVERSITY_SUBJECT.md) (University/Subject preferences on profile)

---

## Table of Contents

1. [Overview](#1-overview)
2. [Two intake levels](#2-two-intake-levels)
3. [Data model & migration](#3-data-model--migration)
4. [Intake options](#4-intake-options)
5. [User flow](#5-user-flow)
6. [Architecture](#6-architecture)
7. [Files created / updated](#7-files-created--updated)
8. [UI](#8-ui)
9. [Backend (store & validation)](#9-backend-store--validation)
10. [Live server deploy](#10-live-server-deploy)
11. [How to test](#11-how-to-test)
12. [Troubleshooting](#12-troubleshooting)
13. [Future work](#13-future-work)

---

## 1. Overview

আগে intake শুধু **student level** (`students.intake`) — create/edit form ও list filter এ।  
University application (`student_university`) row তে intake ছিল না।

এই feature যোগ করে:

| Area | Change |
|------|--------|
| **Database** | `student_university.intake` — nullable, per application |
| **New Application** (student profile) | Subject + University + **Intake** dropdown |
| **Store** | Intake validate + save on application row |
| **Student sync** | `students.intake` খালি থাকলে প্রথম application এর intake copy |
| **University Applications list** (profile) | Subject ও **Intake আলাদা** meta box এ display |

---

## 2. Two intake levels

| Level | Column | Meaning |
|-------|--------|---------|
| **Student** | `students.intake` | Student এর overall / default intake (list filter, profile hero) |
| **Application** | `student_university.intake` | ওই university application কোন intake — **source of truth per app** |

এক student এর একাধিক application হলে প্রতিটিতে **আলাদা intake** রাখা যায়।

পুরনো application row → `intake = NULL` (স্বাভাবিক)। নতুন application থেকে track শুরু।

---

## 3. Data model & migration

### Table

`student_university`

### New column

| Column | Type | Nullable | Example |
|--------|------|----------|---------|
| `intake` | `VARCHAR(50)` | Yes | `March-2026`, `September-2025` |

Position: after `subject_id`

### Migration file

```
database/migrations/2026_06_03_120000_add_intake_to_student_university_table.php
```

- `Schema::hasColumn` guard — live এ duplicate add error হবে না
- Rollback: `down()` drops column if exists

### Model

`App\Models\Student\StudentUniversity` — `$fillable` এ `intake` যোগ।

---

## 4. Intake options

**Class:** `App\Support\IntakeOptions`

### `IntakeOptions::list()`

March ও September, current year ± 2 (মোট ১০টি option):

```
March-2024, September-2024, March-2025, … March-2028, September-2028
```

(বছর `now()->year` অনুযায়ী dynamic)

### `IntakeOptions::defaultForStudent(?string $studentIntake)`

New Application dropdown default:

1. `$student->intake` list এ থাকলে → সেটা selected  
2. না হলে → current year ধারণকারী প্রথম option (create form এর মতো)  
3. fallback → list এর প্রথম item  

### Reuse

`StudentController::intakeCreate()` এখন `IntakeOptions::list()` return করে — create/edit/index filter একই list।

---

## 5. User flow

```
1. Student profile → New Application
2. Select Subject, University, Intake (default pre-selected)
3. Submit → POST student.application.store
4. student_university row created with intake
5. If students.intake empty → student row updated
6. Redirect to profile → application card shows Subject + Intake (separate meta box)
```

Normal form POST — **AJAX নয়** (University/Subject preference add AJAX থেকে আলাদা)।

---

## 6. Architecture

```
show.blade.php (New Application form)
    └── name="intake" + $intakeOptions, $defaultIntake
            └── POST → StudentApplicationController@store
                    ├── validate intake ∈ IntakeOptions::list()
                    ├── StudentUniversity::fill (includes intake)
                    └── optional students.intake sync

StudentProfileService::viewData()
    └── intakeOptions, defaultIntake
```

---

## 7. Files created / updated

### Created

| File | Purpose |
|------|---------|
| `database/migrations/2026_06_03_120000_add_intake_to_student_university_table.php` | Add column |
| `app/Support/IntakeOptions.php` | Shared intake list + default selection |
| `docs/STUDENT_APPLICATION_INTAKE.md` | This document |

### Updated

| File | Change |
|------|--------|
| `app/Models/Student/StudentUniversity.php` | `intake` in fillable |
| `app/Services/Student/StudentProfileService.php` | `intakeOptions`, `defaultIntake` |
| `app/Http/Controllers/Admin/StudentApplicationController.php` | Validate + save intake; sync student |
| `app/Http/Controllers/Admin/StudentController.php` | `intakeCreate()` → `IntakeOptions::list()` |
| `resources/views/admin/students/show.blade.php` | New Application 3-col form; application meta row |
| `public/css/admin/student-profile.css` | `.application-meta-row`, `.application-meta-item` |

---

## 8. UI

### New Application form

Location: student profile, `@can('updateApplicationStatus')` block

| Field | Column | Required |
|-------|--------|----------|
| Subject | `col-md-4` | Yes |
| University | `col-md-4` | Yes |
| Intake | `col-md-4` | Yes |

Route: `POST` → `route('student.application.store')`

Hidden fields unchanged: `student_id`, `status_id`

### University Applications section (each card)

**Header:** University name + App # (if any)

**Body — meta box** (`.application-meta-row`):

| Block | Content |
|-------|---------|
| Subject | Subject name or `—` |
| Intake | Calendar icon + label badge (`label-light-info`) or `—` |

Subject ও Intake **আলাদা** — এক লাইনে `·` join করা হয় না।

---

## 9. Backend (store & validation)

**Controller:** `StudentApplicationController@store`

### Validation rules (intake)

```php
'intake' => ['required', 'string', 'max:50', Rule::in(IntakeOptions::list())],
```

### Save

```php
$stUni->fill($request->only($stUni->getFillable())); // includes intake
$stUni->save();

if (empty($student->intake)) {
    $student->update(['intake' => $request->input('intake')]);
}
```

### Authorization

`StudentPolicy@updateApplicationStatus` (same as other application actions)

### Success redirect

`students.show` with flash: `Application added successfully`

---

## 10. Live server deploy

**Order matters:** migration **আগে**, তারপর code যেখানে intake save/display করে।

### Step 1 — Backup

Database backup (full or `student_university` table)

### Step 2 — Upload code

Migration file + updated PHP/Blade/CSS

### Step 3 — Migrate

```bash
cd /path/to/admin
php artisan migrate --force
```

শুধু এই migration:

```bash
php artisan migrate --path=database/migrations/2026_06_03_120000_add_intake_to_student_university_table.php --force
```

### Step 4 — Verify

```sql
SHOW COLUMNS FROM student_university LIKE 'intake';
```

### Manual SQL (alternative)

```sql
ALTER TABLE `student_university`
ADD COLUMN `intake` VARCHAR(50) NULL DEFAULT NULL
AFTER `subject_id`;
```

(Laravel migration already run থাকলে duplicate avoid — migration has `hasColumn` check)

### Rollback

```bash
php artisan migrate:rollback --step=1
```

---

## 11. How to test

### New application with intake

1. Open student profile (permission: update application)
2. New Application → Intake dropdown visible, default selected
3. Submit → success redirect
4. Application card → Intake meta box এ value দেখা যায়
5. DB: `SELECT intake FROM student_university WHERE id = ?`

### Old student (empty student intake)

1. Student যার `students.intake` NULL
2. Add application with intake `September-2026`
3. Check `students.intake` updated to same value
4. Second application different intake → `student_university.intake` differs; `students.intake` unchanged (already set)

### Old applications

Existing rows → `intake` NULL → UI shows `—` in meta box

### Regression

- New Application without intake → validation error (required)
- Invalid intake string → 422 validation error
- University/Subject preference AJAX — unchanged

---

## 12. Troubleshooting

| Symptom | Cause / fix |
|---------|-------------|
| SQL error on save: unknown column `intake` | Live এ migrate চালানো হয়নি |
| Intake dropdown empty | `IntakeOptions::list()` — check server date/year |
| Card shows `—` for new app | Row save failed or old code deployed |
| Student intake not updated | `students.intake` already had value — by design only fills when empty |
| Validation fails on intake | Value not in allowed list — must match `March-YYYY` / `September-YYYY` from helper |

---

## 13. Future work

| Item | Notes |
|------|--------|
| Edit application | `student_applications/edit` form এ intake field + update service |
| Application list report | Filter by `student_university.intake` |
| Backfill old rows | One-off script from `students.intake` where app intake NULL |
| Intake on application edit only | Student intake sync policy (always update vs keep separate) |

---

## Quick reference

```bash
# Live column add
php artisan migrate --force

# Intake options (PHP)
App\Support\IntakeOptions::list()
App\Support\IntakeOptions::defaultForStudent($student->intake)
```

**Routes:** `POST /student/application` → `student.application.store`  
**Column:** `student_university.intake`  
**View:** `resources/views/admin/students/show.blade.php`
