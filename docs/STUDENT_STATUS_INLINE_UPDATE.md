# Student & Application Status — Feature Documentation

**Project:** OrangeToolz Admin  
**Last updated:** May 2026  
**Scope:** Inline status updates on Students index & University Application list, plus application status filter

---

## Table of Contents

1. [Overview](#1-overview)
2. [Three Status Concepts](#2-three-status-concepts)
3. [Application Status (University)](#3-application-status-university)
4. [Office Status (Student)](#4-office-status-student)
5. [Application List — Status Filter](#5-application-list--status-filter)
6. [Architecture](#6-architecture)
7. [Files Created / Updated / Removed](#7-files-created--updated--removed)
8. [Routes & API](#8-routes--api)
9. [Frontend (UI + JS)](#9-frontend-ui--js)
10. [Notifications & Email](#10-notifications--email)
11. [Authorization & Branch Filter](#11-authorization--branch-filter)
12. [Cache](#12-cache)
13. [How to Test](#13-how-to-test)
14. [Troubleshooting](#14-troubleshooting)

---

## 1. Overview

This work adds **inline dropdown status changes** (no full page reload) on:

| Page | Feature |
|------|---------|
| **Students index** (`/students`) | Office status under name + university application status per university |
| **University application list** (`?universitylist=1&applicationlist={id}`) | Application status column + **filter by status** |

All successful updates show a **toastr** notification. Code follows Laravel patterns: **Services**, **Queries**, **Form Requests**, **Policies**, shared **Blade partials**, and one **JS** file.

---

## 2. Three Status Concepts

Do not mix these — they use **different database tables**.

| Name in UI | DB column | Table | Model | Where shown |
|------------|-----------|-------|-------|-------------|
| **Office Status** | `students.status` | `student_statuses` | `StudentStatus` | Students index — **Name** column (below student name) |
| **Application Status** | `student_university.status_id` | `university_statuses` | `UniversityStatus` | Students index — **University / Status** column; Application list — **Status** column |
| **Application status filter** | (query only) | `university_statuses` | — | Application list toolbar — filters which students appear |

**Office Status** = overall student progress at AR Education office.  
**Application Status** = progress for a **specific university application** (one student can have many).

---

## 3. Application Status (University)

### What it does

- Dropdown per application (pill style, color by status).
- On change: `PATCH` API → updates `student_university.status_id`.
- Writes history to `university_application_status` (`active = 1` row).
- Sends notifications + optional email (template `[Status Change]`).

### Where it appears

1. **University application list** — one dropdown per row (`Status` column).
2. **Students index** — one dropdown per university line in **University / Status** column (`university-applications-inline` partial).

### Backend

| Piece | Location |
|-------|----------|
| Route | `PATCH /student/application/{application}/status` |
| Route name | `student.application.update-status` |
| Controller | `StudentApplicationController@updateStatus` |
| Service | `App\Services\Student\UniversityApplicationStatusService` |
| Policy | `StudentPolicy@updateApplicationStatus` (via parent student) |

### JSON response (success)

```json
{
  "success": true,
  "status_name": "Admitted",
  "message": "Status updated to Admitted."
}
```

### History table

Each change deactivates old rows and inserts:

```
university_application_status
  - student_university_id
  - status_id
  - updated_by
  - active = 1
```

---

## 4. Office Status (Student)

### What it does

- Dropdown under student **full name** on students index.
- On change: `PATCH` API → updates `students.status`.
- Notifications + optional email (template `[Student Status]`).
- Edit form (`Office Status` field) still works; inline update uses same notification service.

### Where it appears

- **Students index** — Name column (`office-status` via shared `inline-status-select` partial).

### Backend

| Piece | Location |
|-------|----------|
| Route | `PATCH /students/{student}/office-status` |
| Route name | `students.update-office-status` |
| Controller | `StudentController@updateOfficeStatus` |
| Service | `App\Services\Student\OfficeStatusService` |
| Policy | `StudentPolicy@updateOfficeStatus` |

### JSON response (success)

```json
{
  "success": true,
  "status_name": "Processing",
  "message": "Office status updated to Processing."
}
```

### Edit form integration

`StudentController@update` uses `OfficeStatusService::notifyChange()` when status or note/remarks change (no duplicate notification code in controller).

---

## 5. Application List — Status Filter

### What it does

On **Application List** page (single university), toolbar lets you:

- Select **All application statuses** or one specific status.
- **Filter** — reload list with query string.
- **Reset** — clear status filter.
- **Export** — CSV respects current filter.

### URL parameters

| Parameter | Required | Example |
|-----------|----------|---------|
| `universitylist` | Yes | `1` |
| `applicationlist` | Yes | `{university_post_id}` |
| `application_status` | No | `{university_statuses.id}` |
| `export` | No | `yes` (download CSV) |

**Example:**

```
/students?universitylist=1&applicationlist=12&application_status=7
```

Page title shows filter when active:

```
Application List - ABC University (Admitted)
```

### Backend

| Piece | Location |
|-------|----------|
| Form Request | `UniversityApplicationListFilterRequest` |
| Query | `UniversityApplicationListQuery::make($universityId, $statusId, $branchId)` |
| Service | `UniversityApplicationListService` (list data + export) |
| Controller | `StudentController@index` (applicationlist branch) |

### Branch security

Non Admin / Super Admin users only see students where `students.branch_id` = their branch (same rule as main student list).

---

## 6. Architecture

### Request flow (inline status change)

```
Browser (change <select>)
  → inline-status-select.js (fetch PATCH + CSRF)
  → Route
  → Controller (authorize + validate)
  → Service (update DB + notify)
  → JSON { success, message }
  → toastr success/error
```

### Request flow (application list page)

```
GET /students?universitylist=1&applicationlist=X&application_status=Y
  → UniversityApplicationListFilterRequest (validate)
  → UniversityApplicationListService::listViewData()
  → UniversityApplicationListQuery (join + filter + branch)
  → view university-student-list
```

### Layer responsibilities

| Layer | Responsibility |
|-------|----------------|
| **View / partials** | HTML only; tone passed from parent |
| **inline-status-select.js** | One listener for all `.inline-status-select` |
| **Controller** | HTTP, authorize, delegate to service |
| **Form Request** | Validation for filter query params |
| **Query** | Eloquent join, filters, export row building |
| **Service** | Business logic, notifications, export orchestration |
| **Policy** | Branch + role access |
| **Support** | `StatusTone`, `StatusDropdownCache` |

---

## 7. Files Created / Updated / Removed

### Created

| File | Purpose |
|------|---------|
| `app/Support/StatusTone.php` | Status ID → color tone (success/danger/warning/primary) |
| `app/Support/StatusDropdownCache.php` | Cache keys for status dropdowns |
| `app/Services/Student/OfficeStatusService.php` | Office status update + notify |
| `app/Services/Student/UniversityApplicationStatusService.php` | Application status update + history + notify |
| `app/Services/Student/UniversityApplicationListService.php` | Application list page + export |
| `app/Services/Status/RoleBasedStatusNotifier.php` | Shared role-based notifications |
| `app/Queries/UniversityApplicationListQuery.php` | Join, filter, export rows, titles |
| `app/Http/Requests/Student/UniversityApplicationListFilterRequest.php` | Filter validation |
| `app/Policies/StudentPolicy.php` | manage / updateOfficeStatus / updateApplicationStatus |
| `resources/views/admin/students/partials/inline-status-select.blade.php` | Shared dropdown UI |
| `resources/views/admin/students/partials/inline-status-select-styles.blade.php` | Shared CSS |
| `resources/views/admin/students/partials/university-applications-inline.blade.php` | Multiple universities on index |
| `resources/views/admin/students/partials/application-list-toolbar.blade.php` | Filter + export toolbar |
| `public/js/admin/inline-status-select.js` | AJAX + toastr |

### Updated

| File | Changes |
|------|---------|
| `routes/web.php` | `students.update-office-status`, `student.application.update-status` |
| `app/Http/Controllers/Admin/StudentController.php` | Services injected; application list slim; dead code removed; office notify via service |
| `app/Http/Controllers/Admin/StudentApplicationController.php` | Uses `UniversityApplicationStatusService`; notify via service |
| `app/Models/Student/Student.php` | `universityById()` uses loaded relation (no extra query) |
| `app/Models/Student/StudentUniversity.php` | `student()` relation |
| `app/Providers/ViewServiceProvider.php` | Cached `student_statuses`, `university_statuses`, `status_tone_map` |
| `app/Providers/AuthServiceProvider.php` | `StudentPolicy` registered |
| `app/Http/Controllers/Admin/SettingController.php` | Clears status cache on CRUD |
| `resources/views/admin/students/index.blade.php` | Inline dropdowns + `data-inline-status-root` |
| `resources/views/admin/students/university-student-list.blade.php` | Toolbar + inline status |

### Removed (replaced by shared files)

| File | Replaced by |
|------|-------------|
| `partials/application-status-select.blade.php` | `inline-status-select.blade.php` |
| `partials/office-status-select.blade.php` | `inline-status-select.blade.php` |
| `partials/student-office-status-badge.blade.php` | Inline dropdown |
| `partials/application-status-select-styles.blade.php` | `inline-status-select-styles.blade.php` |
| `public/js/admin/application-status.js` | `inline-status-select.js` |
| `public/js/admin/uni-application-status.js` | `inline-status-select.js` |

### Deprecated alias (backward compatible)

`App\Support\UniversityApplicationStatusTone` extends `StatusTone` — prefer `StatusTone` in new code.

---

## 8. Routes & API

### Routes (`routes/web.php`, inside `auth` middleware)

```php
Route::patch('students/{student}/office-status', [StudentController::class, 'updateOfficeStatus'])
    ->name('students.update-office-status');

Route::patch('student/application/{application}/status', [StudentApplicationController::class, 'updateStatus'])
    ->name('application.update-status');
```

### Office status — PATCH

- **URL:** `/students/{student_id}/office-status`
- **Body:** `{ "status_id": 3 }`
- **Headers:** `Accept: application/json`, `Content-Type: application/json`, `X-CSRF-TOKEN`

### Application status — PATCH

- **URL:** `/student/application/{student_university_id}/status`
- **Body:** `{ "status_id": 5 }`
- **Headers:** same as above

---

## 9. Frontend (UI + JS)

### HTML requirements

Parent container (table) must have:

```html
<table
  data-inline-status-root
  data-csrf="{{ csrf_token() }}"
  data-status-tones='@json($status_tone_map)'
>
```

### Include dropdown partial

```blade
@include('admin.students.partials.inline-status-select', [
    'updateUrl' => route('students.update-office-status', $student),
    'currentStatusId' => $student->status,
    'statuses' => $student_statuses,
    'tone' => \App\Support\StatusTone::forStatusId((int) $student->status),
    'wrapClass' => 'student-office-status-wrap',  // optional
    'compact' => true,  // optional
])
```

### Scripts

```blade
@section('scripts')
<script src="{{ asset('js/admin/inline-status-select.js') }}"></script>
@endsection
```

### Styles

```blade
@include('admin.students.partials.inline-status-select-styles')
```

### Tone colors (config)

Defined in `App\Support\StatusTone` (by status ID):

| Tone | Example IDs |
|------|-------------|
| success | 7, 9, 11 |
| danger | 4, 12, 2 |
| warning | 6, 8, 13 |
| primary | (default for others) |

To change colors for new statuses, update `StatusTone::MAP` or later move to DB column.

---

## 10. Notifications & Email

| Change type | Notification channel | Email template |
|-------------|---------------------|----------------|
| Office status | `student` | `[Student Status]` |
| Application status | `university` | `[Status Change]` |

Notified roles: Admin, Super Admin, Assign Employee, Senior Counselor, Junior Counselor (see `RoleBasedStatusNotifier`).

Failures in notify/email **do not roll back** the saved status (by design).

---

## 11. Authorization & Branch Filter

### Policy (`StudentPolicy`)

| Method | Rule |
|--------|------|
| `manage` | Admin/Super Admin = all; others = same `branch_id` as student |
| `updateOfficeStatus` | uses `manage` |
| `updateApplicationStatus` | uses `manage` on parent student |

### Application list query

`UniversityApplicationListQuery::make()` applies `branch_id` filter for non-admin users.

---

## 12. Cache

### Dropdown data (1 hour)

| Key | Content |
|-----|---------|
| `dropdown.student_statuses` | Office status options |
| `dropdown.university_statuses` | Application status options |

Loaded via `ViewServiceProvider` for:

- `admin.students.index`
- `admin.students.university-student-list`

### Clear cache

Automatically cleared when saving/deleting statuses in **Settings** (`SettingController`).

Manual:

```bash
php artisan cache:clear
```

Or in code:

```php
\App\Support\StatusDropdownCache::forgetAll();
```

---

## 13. How to Test

### Office status (students index)

1. Open `/students`
2. Under a student name, change office status dropdown
3. Expect green toastr: `Office status updated to …`
4. Refresh — value persisted

### Application status (students index)

1. Open `/students` (student with multiple universities)
2. In **University / Status**, change a dropdown
3. Expect toastr: `Status updated to …`

### Application status (application list)

1. Open University Report → pick a university
2. Change status in **Status** column
3. Expect toastr

### Application status filter

1. On application list, choose a status → **Filter**
2. List shows only matching students
3. Title includes status name
4. **Export** — CSV only filtered rows
5. **Reset** — all statuses again

### Branch user

1. Login as non-admin (specific branch)
2. Application list / inline update only for students in that branch
3. Other branch → 403 on PATCH if attempted

---

## 14. Troubleshooting

| Problem | Check |
|---------|--------|
| Dropdown change does nothing | Hard refresh (Ctrl+F5); `inline-status-select.js` loaded; browser console errors |
| 419 / CSRF error | `data-csrf` on table; meta csrf-token in layout |
| 403 Forbidden | User branch vs student branch; policy |
| 422 validation | `status_id` must exist in correct table (`student_statuses` vs `university_statuses`) |
| Toastr not showing | `toastr` loaded in `layouts/default` |
| Filter shows old status names | Run `php artisan cache:clear` after settings change |
| Wrong student on application list | Clear `application_status` param; check join `university_id` |

### Logs

```text
storage/logs/laravel.log
```

---

## Related documentation

- [LARAVEL_BEST_PRACTICE_MIGRATION_PLAN.md](./LARAVEL_BEST_PRACTICE_MIGRATION_PLAN.md) — full project refactor roadmap

---

## Progress checklist (for maintainers)

```
[x] Application status inline dropdown (application list)
[x] Application status inline dropdown (students index)
[x] Office status inline dropdown (students index)
[x] Toastr on success/error
[x] Services + Query + Form Request
[x] StudentPolicy authorization
[x] Branch filter on application list
[x] Application status filter + export
[x] Status dropdown cache + invalidation
[x] Dead code removed from StudentController::update()
[x] Shared partial + single JS file
```

---

*End of document.*
