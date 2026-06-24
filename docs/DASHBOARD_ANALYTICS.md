# Dashboard Analytics — Plan & Implementation

## Overview

Admin dashboard e **Business Overview KPI cards** + **Highcharts** charts add kora hocche.
Data source: existing tables — kono notun migration lagbe na (Phase 1).

---

## Data model mapping

| Metric | Table | Notes |
|--------|-------|-------|
| Students | `students` | `branch_id`, `created_at`, counselors |
| Applications | `student_university` | `status_id` → `university_statuses` |
| Revenue | `invoices` | `paid`, `invoice_date`, `branch_id`, `currency` |
| Office status | `students.status` → `student_statuses` | Charts only (Phase 1) |

**Important:** Application Pending / Approved / Rejected = `student_university.status_id`, **not** `students.status`.

---

## Phase 1 — KPI Cards (8)

| # | Card | Query |
|---|------|-------|
| 1 | Total Students | `students` count (branch / counselor scope) |
| 2 | New Students This Month | `created_at` current month |
| 3 | Total Applications | `student_university` count (via student scope) |
| 4 | Pending Applications | `status_id` in pending group |
| 5 | Approved Applications | `status_id` in approved group |
| 6 | Rejected Applications | `status_id` in rejected group |
| 7 | Total Revenue | `sum(invoices.paid)` branch scope |
| 8 | Revenue This Month | `sum(paid)` + `invoice_date` this month |

**Revenue rule:** `paid` field = actual collected amount (not `grandtotal`).

---

## Application status groups

Configured in `config/dashboard.php` by **status name** (ID live DB te alada holeo kaj korbe).

| Group | Status names |
|-------|----------------|
| **Pending** | File Processing, File Submitted, Application Fee Pending, Application Fee Paid, Pass Initial Review, Interview Pending, Interview Done, Admission Confirmation Fee Pending |
| **Approved** | Pre Admitted, Admission Confirmation Fee Paid, Admitted, JW Issued, File Processing Completed |
| **Rejected** | Reject, Rejected |

Update `config/dashboard.php` if office adds new statuses.

---

## Phase 1 — Charts (3)

| Chart | Type | Data |
|-------|------|------|
| Application by Status | Donut | `student_university` group by status name |
| New Students (12 months) | Line/Area | Monthly `students.created_at` |
| Revenue (12 months) | Column | Monthly `sum(invoices.paid)` |

Highcharts already loaded in layout — reuse CDN scripts.

---

## Access scope

| User | Students | Applications | Revenue |
|------|----------|--------------|---------|
| Counselor (default) | `junior_counselor` OR `senior_counselor` = user id | Via student join | Branch invoices |
| Admin / Super Admin (role 1–3) | Full branch | Full branch | Branch invoices |

Branch = `auth()->user()->branch` (existing app convention).

---

## File structure

```
config/dashboard.php
app/Services/Dashboard/DashboardAnalyticsService.php
app/Http/Controllers/DashboardController.php
resources/views/dashboard/main.blade.php
resources/views/dashboard/partials/kpi-cards.blade.php
resources/views/dashboard/partials/charts.blade.php
public/css/admin/dashboard.css
public/js/admin/dashboard-charts.js
```

---

## Phase 2 (future)

- Branch filter (Super Admin)
- Top universities bar chart
- Office student status pie
- Date range filter
- Cache (`Cache::remember`, 5 min)
- Card click → filtered list URLs

---

## Deploy checklist

1. Upload all files above
2. `php artisan config:clear`
3. Verify `university_statuses` names match config
4. Hard refresh dashboard

---

## Changelog

| Date | Change |
|------|--------|
| 2026-06-03 | Phase 1 doc + KPI cards + 3 charts implemented |
