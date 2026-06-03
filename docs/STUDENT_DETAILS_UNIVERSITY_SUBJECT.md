# Student Details — University & Subject Preferences

**Project:** OrangeToolz Admin  
**Last updated:** June 2026  
**Scope:** Student profile (`show`) page — right sidebar এ University/Subject add করা এবং **New Application** dropdown-এ auto sync

---

## Table of Contents

1. [Overview](#1-overview)
2. [Business requirement](#2-business-requirement)
3. [Data model](#3-data-model)
4. [User flow](#4-user-flow)
5. [Architecture](#5-architecture)
6. [Files created / updated](#6-files-created--updated)
7. [Routes & API](#7-routes--api)
8. [Authorization](#8-authorization)
9. [Frontend (Blade, CSS, JS)](#9-frontend-blade-css-js)
10. [Relationship to other pages](#10-relationship-to-other-pages)
11. [How to test](#11-how-to-test)
12. [Troubleshooting](#12-troubleshooting)
13. [Future work](#13-future-work)

---

## 1. Overview

আগে Student **details** page-এ Universities ও Subjects শুধু **read-only tags** ছিল। Create/Edit form থেকে checkbox দিয়ে assign করা যেত, কিন্তু profile থেকে নতুন university/subject যোগ করা যেত না।

এই feature যোগ করে:

| Location | Change |
|----------|--------|
| **Right sidebar** — Subjects card | Assigned tags + dropdown (সব subject) + **Add** button |
| **Right sidebar** — Universities card | Assigned tags + dropdown (সব university) + **Add** button |
| **New Application** form | Subject/University `<select>` — student-এর linked list; AJAX add-এর পর **নতুন option auto append** |
| **Stat cards** (top) | Subjects / Universities count — add-এর পর JS দিয়ে update |

Save হয় **AJAX** (`fetch`) — full page reload লাগে না। Database path **create/edit-এর মতোই** pivot `attach`।

---

## 2. Business requirement

### Problem

- Counselor student profile খুলে নতুন university/subject assign করতে **Edit Student** page-এ যেতে হত।
- **New Application** form শুধু আগে থেকে linked university/subject দেখাত — profile থেকে নতুন add করলে application form-এ দেখা যেত না (reload ছাড়া)।

### Solution

1. Details page sidebar থেকে full list dropdown দিয়ে add।
2. Add = pivot-এ insert (same as student create checkbox)।
3. Success → tag list + New Application dropdown একসাথে update (no reload)।

### Out of scope (এই ticket-এ)

- Intake dropdown on New Application (planned separately — see [§13](#13-future-work))।
- Remove/unlink university or subject from profile।
- Application-level intake storage।

---

## 3. Data model

কোনো নতুন table বা migration **নেই**। Existing many-to-many pivots ব্যবহার হয়।

| Relation | Pivot table | Keys | Source list |
|----------|-------------|------|-------------|
| Student ↔ Subject | `student_subject` | `student_id`, `subject_id` | `subjects` table |
| Student ↔ University | `post_student` | `student_id`, `post_id` | `posts` where `post_type = 'university'` |

### Models

- `App\Models\Student\Student::subjects()` → `belongsToMany(Subject::class)`
- `App\Models\Student\Student::universities()` → `belongsToMany(Post::class)` (university posts)

### New Application (unchanged storage)

Application row: `student_university` — `student_id`, `university_id`, `subject_id`, `status_id`, …  
Form still posts to `StudentApplicationController@store`; শুধু dropdown options student preferences থেকে আসে।

---

## 4. User flow

```
1. User opens Students → Profile (show)
2. Right column: Subjects / Universities cards
3. Select item from dropdown → click Add
4. AJAX POST → pivot attach
5. UI updates:
   - New tag under card
   - Option removed from sidebar dropdown (already assigned)
   - New <option> in New Application subject/university select
   - Stat number +1
6. User fills New Application (optional) → normal form POST
```

Duplicate add: server `already_exists: true` → info toastr; UI duplicate tag যোগ হয় না (JS checks `data-preference-tag-id`).

---

## 5. Architecture

```
show.blade.php
    └── #student-profile-preferences (data URLs + CSRF)
            ├── attachSubject / attachUniversity (fetch)
            └── student-show.js (DOM update)

StudentController@show
    └── StudentProfileService::viewData()
            ├── student (with subjects, universities, …)
            ├── allSubjects, allUniversities (full lists for dropdown)

StudentController@attachSubject | @attachUniversity
    └── AttachStudent*Request (validate + authorize)
            └── pivot attach + JSON
```

Inline office/application status-এর মতো **JSON + fetch + toastr** pattern follow করা হয়েছে (`inline-status-select.js`)。

---

## 6. Files created / updated

### Created

| File | Purpose |
|------|---------|
| `app/Http/Requests/Student/AttachStudentSubjectRequest.php` | Validate `subject_id`, authorize `updatePreferences` |
| `app/Http/Requests/Student/AttachStudentUniversityRequest.php` | Validate `university_id` (university post), authorize |
| `docs/STUDENT_DETAILS_UNIVERSITY_SUBJECT.md` | This document |

### Updated

| File | Change |
|------|--------|
| `routes/web.php` | `POST students/{student}/subjects`, `…/universities` |
| `app/Policies/StudentPolicy.php` | `updatePreferences()` → same rules as `manage()` |
| `app/Http/Controllers/Admin/StudentController.php` | `attachSubject()`, `attachUniversity()` |
| `app/Services/Student/StudentProfileService.php` | `allSubjects`, `allUniversities` for show view |
| `resources/views/admin/students/show.blade.php` | Sidebar UI, `data-*` hooks, New Application `data-new-app-select` |
| `public/js/admin/student-show.js` | Preference AJAX + DOM sync (existing delete-app helper retained) |
| `public/css/admin/student-profile.css` | `.student-preference-select` — dropdown-only styling |

### Not changed

- Student create/edit forms (`form.blade.php`) — checkbox flow intact
- `StudentApplicationController@store` — application create logic intact
- Migrations / `StudentUniversity` model

---

## 7. Routes & API

Routes are registered **before** `Route::resource('students', …)` to avoid conflicts.

| Method | URI | Name | Controller |
|--------|-----|------|------------|
| `POST` | `/students/{student}/subjects` | `students.attach-subject` | `StudentController@attachSubject` |
| `POST` | `/students/{student}/universities` | `students.attach-university` | `StudentController@attachUniversity` |

### Request headers

```
Content-Type: application/json
Accept: application/json
X-CSRF-TOKEN: {token}
X-Requested-With: XMLHttpRequest
```

### Request body

**Subject:**

```json
{ "subject_id": 12 }
```

**University:**

```json
{ "university_id": 45 }
```

### Response (success — new attach)

```json
{
  "success": true,
  "already_exists": false,
  "item": { "id": 12, "name": "Computer Science" },
  "message": "Subject added successfully."
}
```

University response uses `name` = `post_title`.

### Response (already linked)

```json
{
  "success": true,
  "already_exists": true,
  "item": { "id": 12, "name": "..." },
  "message": "Subject is already assigned to this student."
}
```

### Errors

| Status | Cause |
|--------|--------|
| `403` | Policy `updatePreferences` failed |
| `422` | Validation failed |
| `419` | CSRF / session expired (JS shows refresh message) |

---

## 8. Authorization

| Ability | Policy method | Who |
|---------|---------------|-----|
| View profile | `view` | `StudentPolicy::manage` — Admin, Super Admin, Manager, Counselor, or same branch |
| Add subject/university on profile | `updatePreferences` | Same as `manage()` |

Blade: `@can('updatePreferences', $student)` wraps dropdown + Add button.  
Read-only users see tags only.

Form requests call `$this->user()->can('updatePreferences', $student)` in `authorize()`.

---

## 9. Frontend (Blade, CSS, JS)

### Blade hooks (`show.blade.php`)

| Attribute | Element | Purpose |
|-----------|---------|---------|
| `#student-profile-preferences` | Right column wrapper | `data-csrf`, attach URLs |
| `data-preference-tags="subject\|university"` | Tag container | Append labels after add |
| `data-preference-select` | Sidebar `<select>` | Source value for POST |
| `data-preference-add` | Add button | Triggers attach |
| `data-new-app-select` | New Application `<select>` | Append `<option>` on success |
| `data-student-stat="subjects\|universities"` | Stat card numbers | Increment count |

Sidebar dropdown options: শুধু **এখনো assign না** করা items (`$assignedSubjectIds` / `$assignedUniversityIds` filter)।

### CSS

`student-profile.css` — class `student-preference-select` on top of Bootstrap `form-control-sm` (border, focus ring, custom chevron). Layout remains **select + Add button** in one row (আগের মতো)।

### JS (`public/js/admin/student-show.js`)

- `initStudentPreferences()` on `DOMContentLoaded`
- `fetch` POST → parse JSON → append tag, new app option, remove sidebar option, update stat
- Toastr: `success` / `info` (duplicate) / `error` / `warning` (no selection)
- `confirmDeleteStudentApplication` — unchanged from before

Script included only on `show.blade.php`:

```blade
<script src="{{ asset('js/admin/student-show.js') }}"></script>
```

---

## 10. Relationship to other pages

| Page | Interaction |
|------|-------------|
| **Students index** | Unchanged; filter by university uses `students` ↔ `posts` relation |
| **Student create/edit** | Checkbox `subjects[]` / `universities[]` — same pivots; no conflict |
| **New Application submit** | Requires `subject_id` + `university_id` on student; must be linked first (this feature enables that from profile) |
| **Application list / inline status** | Independent |

**Isolation:** Feature touches only `students.show` + two POST routes. Other modules unaffected.

**Performance note:** Each profile load runs two extra queries (`allSubjects`, `allUniversities`). Acceptable for single-student page.

---

## 11. How to test

### Manual checklist

1. Login as user with branch access to student.
2. Open **Students → {student} → Profile**.
3. **Subjects:** pick unassigned subject → **Add** → tag appears, stat +1, New Application subject dropdown has new option.
4. **Universities:** same for university.
5. Add same item again → info message, no duplicate tag.
6. Submit **New Application** with new subject + university → application creates (existing flow).
7. User **without** `updatePreferences` (other branch): no dropdown, tags visible only.
8. Hard refresh after CSS change: `Ctrl+F5`.

### Regression

- Student **Edit** save — subjects/universities still sync via checkboxes.
- Office status inline update on same page still works.
- Delete application (SweetAlert) still works.

---

## 12. Troubleshooting

| Symptom | Check |
|---------|--------|
| Add button does nothing | Browser console; `student-show.js` loaded; `#student-profile-preferences` present |
| 419 error | Session expired; re-login / refresh |
| 403 | User branch vs `student.branch_id`; role not in policy list |
| New Application missing option | Add failed; or subject/university not in student pivot — retry Add |
| Dropdown empty | All items already assigned — expected |
| Styles not applied | Cache bust `student-profile.css`; hard refresh |

---

## 13. Application intake column (live deploy)

Migration file (run on live after backup):

`database/migrations/2026_06_03_120000_add_intake_to_student_university_table.php`

| Action | Command |
|--------|---------|
| Add column on live | `php artisan migrate --force` |
| Single file only | `php artisan migrate --path=database/migrations/2026_06_03_120000_add_intake_to_student_university_table.php --force` |

- Table: `student_university`
- Column: `intake` — `VARCHAR(50)`, **nullable**
- Safe re-run: `Schema::hasColumn` check — duplicate error হবে না
- Rollback: `php artisan migrate:rollback --step=1` (শুধু যদি এটাই last migration)

**Deploy order:** backup → migrate → তারপর intake UI/code deploy।

---

## 14. Application intake (implemented)

Full documentation: **[STUDENT_APPLICATION_INTAKE.md](./STUDENT_APPLICATION_INTAKE.md)**

| Piece | Location |
|-------|----------|
| Column | `student_university.intake` — migration `2026_06_03_120000_add_intake_to_student_university_table.php` |
| Options helper | `App\Support\IntakeOptions` |
| New Application form | `show.blade.php` — Subject / University / Intake (3 columns) |
| Store | `StudentApplicationController@store` — validates + saves; syncs `students.intake` if empty |
| Application list on profile | Separate meta box: Subject + Intake |
| **Optimize code** | Optional: merge attach endpoints, reload instead of DOM JS, drop separate FormRequest classes |
| **Remove preference** | Not implemented — would need `detach` endpoint + UI |
| **Reuse `intakeCreate()`** | Share intake option list between create/edit/show when Intake is added |

---

## Quick reference

```bash
# Routes (after php artisan route:list works in your env)
POST /students/{id}/subjects      → students.attach-subject
POST /students/{id}/universities  → students.attach-university
```

**Related docs:** `STUDENT_STATUS_INLINE_UPDATE.md`, `STUDENT_MODULE_OPTIMIZATION_PLAN.md`
