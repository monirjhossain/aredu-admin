# Student Module — Optimization & Clean Code Plan

**Project:** OrangeToolz Admin  
**Scope:** Student CRUD, lists/reports, applications, inline status, public application form  
**Approach:** Strangler Fig — ছোট PR/ধাপ, production ভাঙবে না  
**Last updated:** May 2026  

---

## 1. Goals

| Goal | Success metric |
|------|----------------|
| Thin controllers | `StudentController` < 250 lines (or split into 2–3 controllers) |
| Business logic এক জায়গায় | Store/Update/Export/Notify → Services |
| দ্রুত list & status UX | Index query optimized; notifications queue (optional phase) |
| নিরাপত্তা | সব mutating action-এ Policy + Form Request |
| Maintainable views | Show/Edit = **এক Blade**; Index/List = এক ফাইল + শুধু **shared** partials (status dropdown) |
| Regression safety | Feature tests on critical paths |

---

## 2. Current state (inventory)

### ✅ Already done (keep, extend)

| Item | Location |
|------|----------|
| Student show (thin controller) | `StudentProfileService`, `show.blade.php` |
| Application edit/update | `UniversityApplicationEditPresenter`, `UniversityApplicationUpdateService`, `UpdateUniversityApplicationRequest` |
| Inline office/app status | `OfficeStatusService`, `UniversityApplicationStatusService`, routes, `inline-status-select` + JS |
| Application list report | `UniversityApplicationListService`, `UniversityApplicationListQuery` |
| Status dropdown cache | `StudentStatusDropdownComposer`, `StatusDropdownCache` |
| Policy (partial) | `StudentPolicy` — manage, updateOfficeStatus, updateApplicationStatus |
| Assets | `public/css/admin/student-profile.css`, `application-edit.css`, JS files |

### ❌ Still heavy / messy

| Item | Problem |
|------|---------|
| `StudentController` (~736 lines) | `index` ~280 lines, `store`/`update` mail+relations inline, duplicate CSCA helpers |
| `StudentController@index` | Filters, export CSV, 3 report modes এক method-এ |
| `create` / `edit` | Same dropdown queries repeated (create, edit, applicationForm) |
| `store` / `update` | Sync mail, `OfficeStatusService` duplicate path on update, education loop `$count = 1` hack |
| `StudentApplicationController` | `index`, `store`, `show`, `destroy` — legacy / duplicate of student flows |
| Views | `index.blade.php` 480 lines; dead `application-status-select*` partials |
| Tests | None for student module |
| Performance | Sync SMTP on create/update/status (known ~5s) |

---

## 3. Principles (আপনার preference সহ)

1. **এক পেজ = এক Blade** — `show`, `edit` (student), `student_applications/edit` ইতিমধ্যে; `index` / `university-student-list` এক ফাইল রাখুন, শুধু `@include('inline-status-select')` shared।
2. **CSS/JS** — `public/css|js/admin/`; Blade-এ inline style নয়।
3. **কোনো big-bang rewrite নয়** — প্রতি ধাপে smoke test।
4. **Behavior unchanged** — refactor প্রথমে; feature change আলাদা ticket।

---

## 4. Phased roadmap

```
Phase A ─ Cleanup & safety gate     (2–3 days)   ← শুরু এখান থেকে
Phase B ─ Index & reports           (4–6 days)
Phase C ─ Create / Edit / Store     (5–7 days)
Phase D ─ Application controller    (2–3 days)
Phase E ─ Views & dead code         (2 days)
Phase F ─ Tests                     (3–4 days)   ← B/C-র পর বা parallel
Phase G ─ Performance (optional)    (2–3 days)   ← আলাদা, আপনি defer করেছিলেন
```

**Total (A–F):** ~3–4 সপ্তাহ part-time, ~1.5–2 সপ্তাহ full focus  

---

## Phase A — Cleanup & safety gate (2–3 days)

**Risk:** Low  

### A.1 Dead code removal

- [x] Delete unused partials: `application-status-select*`, `inline-status-select-styles` → `public/css/admin/inline-status.css`
- [x] Delete `edit.blade_old.php`
- [ ] Remove commented mail blocks in `StudentController@store`
- [ ] Fix wrong PHPDoc (`@param Category` → `Student`)

### A.2 Small consistency fixes

- [x] `UpdateOfficeStatusRequest` + controller-এ inline `validate()` সরানো
- [ ] `StudentProfileService::canViewCredentials()` — role IDs policy/config-এ এক জায়গায় (`config/student.php` বা policy method)
- [x] `ViewServiceProvider` → `extends ServiceProvider` (convention)
- [ ] Settings-এ status table change হলে `Cache::forget` for dropdown keys (migration plan Phase 1.4)

### A.3 Smoke checklist (manual, প্রতি phase শেষে)

| URL / action | Check |
|--------------|-------|
| `/students` | List, filters, inline office status |
| `/students?universitylist=1&applicationlist=1&...` | Application report + filter |
| `/students/create` | Form load |
| POST create | Save + relations (slow OK for now) |
| `/students/{id}` | Show, CSCA, applications, status dropdown |
| `/students/{id}/edit` | Update |
| `/student/application/{id}/edit` | Application update |
| PATCH office-status / application status | JSON + CSRF |

**Exit:** Log-এ নতুন error নেই; dead partials নেই।

---

## Phase B — Index & reports (4–6 days)

**Risk:** Medium — সবচেয়ে বেশি user traffic  

### B.1 Query layer

নতুন ফাইল:

```
app/Queries/StudentIndexQuery.php
```

`StudentController@index` থেকে সরানো:

- Branch scope (`Auth::user()->branch`)
- Filters: subject, university, intake, status, CSCA, passport, search, counselor
- Eager loads: `studentCscaExams.cscaExam`, `universitiesAppStatus.statusUni`, `student_status`, …
- Pagination + default sort

### B.2 Service + Form Request

```
app/Services/Student/StudentIndexService.php      → viewData(Request): array
app/Services/Student/StudentExportService.php     → rows(), filename()
app/Http/Requests/Student/StudentIndexFilterRequest.php
```

Controller target:

```php
public function index(StudentIndexFilterRequest $request, StudentIndexService $index)
{
    if ($request->wantsApplicationReport()) {
        return view('...', $this->applicationListService->listViewData(...));
    }
    if ($request->wantsExport()) {
        return $this->exportService->download($request);
    }
    return view('admin.students.index', $index->viewData($request));
}
```

### B.3 Report controllers (optional split)

যদি `index` এখনও বড় হয়:

```
StudentReportController@universityList      → university-list view
StudentReportController@statusList          → status ways list
```

Routes: same query params, শুধু controller class change — **URL same রাখুন**।

### B.4 Index view

- [ ] `index.blade.php` — logic নয়, শুধে markup; filter form এক block
- [ ] Shared: `inline-status-select` + styles (এক partial যথেষ্ট)
- [ ] `university-applications-inline` — index-এ inline করা যায় (optional, এক ফাইল চাইলে)

**Exit:** Index load time কমেছে (N+1 নেই); export CSV আগের মতো কাজ করে।

---

## Phase C — Create / Edit / Store / Update (5–7 days)

**Risk:** Medium–High — data + mail  

### C.1 Form data service (duplicate queries দূর)

```
app/Services/Student/StudentFormDataService.php
```

Methods:

- `createFormData(): array` — intake, branches, statuses, users, subjects, documents, studies, universities, csca_exams
- `editFormData(Student $student): array` — উপরের + loaded relations

`create()` / `edit()` → ৩–৫ লাইন each.

### C.2 Store / Update services

```
app/Services/Student/StudentStoreService.php
app/Services/Student/StudentUpdateService.php
app/Services/Student/StudentEducationSyncService.php   (optional)
app/Services/Student/StudentRelationSyncService.php  (subjects, documents, universities)
```

সরানো controller থেকে:

- `normalizeCscaExamPayload`, `syncStudentCscaExams` → existing `StudentCscaQuery` বা dedicated sync class
- Education rows — `$count = 1` hack ঠিক করে real row count
- `store` mail → `StudentWelcomeNotification` বা `StudentMailService` (এক method)

`update` office notify — শুধু `OfficeStatusService` (duplicate if/notify block মুছুন)

### C.3 Controller target

```php
public function store(StudentRequest $request)
{
    $this->authorize('create', Student::class);
    $student = $this->storeService->create($request);
    return redirect()->route('students.show', $student)->with('success', '...');
}

public function update(StudentRequest $request, Student $student)
{
    $this->authorize('update', $student);
    $this->updateService->update($student, $request);
    return redirect()->route('students.index')->with('success', '...');
}
```

### C.4 Policy extend

`StudentPolicy`:

| Method | Use |
|--------|-----|
| `viewAny` | index |
| `view` | show |
| `create` | create/store |
| `update` | edit/update |
| `delete` | destroy (যদি enable করেন) |

### C.5 Student edit view (optional)

- `edit.blade.php` + `form.blade.php` — form partial রাখা OK (এক “page” = edit wrapper + form partial); অথবা form inline এক ফাইলে (~একই দর্শন)

**Exit:** Create/update behavior same; controller methods < 15 lines each.

---

## Phase D — StudentApplicationController (2–3 days)

**Risk:** Low–Medium  

| Action | Plan |
|--------|------|
| `edit` / `update` / `updateStatus` | ✅ Done — maintain |
| `index` / `store` / `destroy` | Audit: কোথায় use হয়; dead হলে deprecate + route comment |
| `show($id)` | University report — `StudentApplicationController@show` vs `StudentController@index` overlap; এক controller/service-এ consolidate |

Target: Application controller শুধু **application resource** (CRUD + status API), list report `UniversityApplicationListService`-এ থাকে।

---

## Phase E — Views & frontend (2 days)

| File | Action |
|------|--------|
| `show.blade.php` | ✅ Single file — CSCA PHP → `StudentProfileService` (presentation logic সরানো) |
| `index.blade.php` | Filter toolbar এক section; no duplicate status markup |
| `inline-status-select-styles` | Merge into `public/css/admin/inline-status.css` এক file |
| `create` / `edit` | Shared `student-form.css` if repeated styles |
| Blade component (later) | `<x-inline-status-select ... />` — optional, partial যথেষ্ট |

**আপনার rule:** Show + application edit = ১ Blade; index/list = ১ Blade + minimal shared partials।

---

## Phase F — Tests (3–4 days)

```
tests/Feature/Admin/Student/
├── StudentPolicyTest.php
├── UpdateOfficeStatusTest.php
├── UpdateApplicationStatusTest.php
├── StudentIndexFilterTest.php
└── StudentStoreUpdateTest.php   (DB transactions + factories)
```

Minimum assertions:

- Unauthorized → 403
- Valid status PATCH → JSON success + DB updated
- Branch user শুধু নিজ branch student
- Filter query returns expected count (seed fixture)

**Exit:** `php artisan test --filter=Student` green on CI/local.

---

## Phase G — Performance (optional, আলাদা ticket)

আপনি আগে বলেছিলেন **“অপাতত থাক”** — refactor শেষে করুন।

| Issue | Fix |
|-------|-----|
| Status change ~5s | `StatusChangedNotification` → `ShouldQueue`; staff = database only |
| Create slow | Welcome mail queue; `RoleBasedStatusNotifier` batch/queue |
| Index slow | Indexes on `students.branch_id`, `student_university.status_id`; query select minimal columns |

---

## 5. Target folder structure (শেষ অবস্থা)

```
app/
├── Http/
│   ├── Controllers/Admin/
│   │   ├── StudentController.php          (~150–200 lines, resource CRUD)
│   │   ├── StudentReportController.php      (optional: reports only)
│   │   └── StudentApplicationController.php (application CRUD + status)
│   ├── Requests/Student/
│   │   ├── StudentRequest.php
│   │   ├── StudentIndexFilterRequest.php
│   │   ├── UpdateOfficeStatusRequest.php
│   │   └── UpdateUniversityApplicationRequest.php
│   └── View/Composers/StudentStatusDropdownComposer.php
├── Policies/StudentPolicy.php
├── Queries/
│   ├── StudentIndexQuery.php
│   ├── StudentCscaQuery.php
│   └── UniversityApplicationListQuery.php
└── Services/Student/
    ├── StudentProfileService.php
    ├── StudentFormDataService.php
    ├── StudentStoreService.php
    ├── StudentUpdateService.php
    ├── StudentIndexService.php
    ├── StudentExportService.php
    ├── OfficeStatusService.php
    ├── UniversityApplicationStatusService.php
    ├── UniversityApplicationUpdateService.php
    ├── UniversityApplicationEditPresenter.php
    └── UniversityApplicationListService.php

resources/views/admin/students/
├── index.blade.php
├── show.blade.php
├── create.blade.php
├── edit.blade.php
├── form.blade.php              (shared form fields — OK)
├── university-student-list.blade.php
├── university-list.blade.php
└── partials/
    ├── inline-status-select.blade.php
    └── university-applications-inline.blade.php   (optional inline into index)

public/css/admin/
public/js/admin/
```

---

## 6. Suggested execution order (PR-sized)

| # | PR / branch | Phase | Est. |
|---|-------------|-------|------|
| 1 | `student/cleanup-dead-code` | A | 1 day |
| 2 | `student/office-status-request` | A | 0.5 day |
| 3 | `student/index-query-service` | B | 2 days |
| 4 | `student/export-service` | B | 1 day |
| 5 | `student/form-data-service` | C | 1 day |
| 6 | `student/store-service` | C | 2 days |
| 7 | `student/update-service` | C | 2 days |
| 8 | `student/application-controller-audit` | D | 1 day |
| 9 | `student/views-css-consolidate` | E | 1 day |
| 10 | `student/feature-tests` | F | 3 days |
| 11 | `student/queue-notifications` | G | optional |

---

## 7. What NOT to do in this refactor

- University / Finance / Invoice modules একসাথে rewrite
- Live-এ route URL বা permission name change (শুধে internal split OK)
- সব partial এক ফাইলে merge করা **index**-এর জন্য (520+ line হবে, maintainability খারাপ)
- Performance queue ছাড়া শুধু “আরও service” যোগ করা — measure আগে

---

## 8. Definition of done (whole module)

- [ ] `StudentController` < 250 lines OR split documented
- [ ] No business mail/sync logic in controller
- [ ] Index filters + export in Query + Service
- [ ] Policy on create/update/delete/viewAny
- [ ] 5+ feature tests passing
- [ ] `docs/STUDENT_STATUS_INLINE_UPDATE.md` + smoke checklist updated
- [ ] Dead partials removed
- [ ] (Optional) Notifications queued — separate approval

---

## 9. Related docs

- `LARAVEL_BEST_PRACTICE_MIGRATION_PLAN.md` — global Phase 2 template
- `STUDENT_STATUS_INLINE_UPDATE.md` — inline status behavior
- `STUDENT_MODULE_OPTIMIZATION_PLAN.md` — **this file**

---

## 10. Next action (আজকে শুরু)

1. Phase **A.1** dead partials + smoke test  
2. Phase **B.1** `StudentIndexQuery` sketch (copy filters from `index()` line 118–290)  
3. একটা PR = এক phase — merge করার পর পরের phase  

চাইলে agent দিয়ে Phase A implement করা যায় — বলুন **“Phase A শুরু করো”**।
