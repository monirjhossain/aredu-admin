# Dashboard Analytics — Plan & Implementation

## Overview

Admin dashboard e **Business Overview KPI cards**, **Highcharts analytics**, ar **Quick Access** tiles add kora hoyeche.
Data source: existing tables — kono notun migration lagbe na.

Dashboard **role-wise** — proti user shudhu tar `role_id` er permission onujayi section dekhte pare.
Permission `config/dashboard.php` theke control hoy; code change chara role access adjust kora jay.

---

## Data model mapping

| Metric | Table | Notes |
|--------|-------|-------|
| Students | `students` | `branch_id`, `created_at`, counselors |
| Applications | `student_university` | `status_id` → `university_statuses` |
| Revenue | `invoices` | `paid`, `invoice_date`, `branch_id`, `currency` |
| Office status | `students.status` → `student_statuses` | Donut chart |
| Finance entries | `finance_entries` | Income vs expense chart |
| Branches | `branches` | Students by branch chart |

**Important:** Application Pending / Approved / Rejected = `student_university.status_id`, **not** `students.status`.

**Revenue rule:** `paid` field = actual collected amount (not `grandtotal`).

---

## Role IDs

`roles` table / `RolesTableSeeder` order:

| ID | Role |
|----|------|
| 1 | Super Admin |
| 2 | Admin |
| 3 | Manager |
| 4 | Branch Manager |
| 5 | Account |
| 6 | IT Officer |
| 7 | Editor |
| 8 | Counselor |
| 9 | Student |

Role 9 (Student) dashboard e kono section define kora nai — blank dashboard.

---

## Role-wise permissions

Configured in `config/dashboard.php` → `permissions` array.
Resolved by `App\Support\DashboardAccess::for($user)`.

### Permission keys

| Key | Content |
|-----|---------|
| `kpi_students` | Total Students, New Students This Month |
| `kpi_applications` | Total / Pending / Approved / Rejected Applications |
| `kpi_revenue` | Total Revenue, Revenue This Month |
| `charts_applications` | Applications by Status (donut) |
| `charts_students` | New Students 12mo, Office Student Status, Students by Intake |
| `charts_branch` | Students by Branch (all branches comparison) |
| `charts_counselor` | Counselor-wise Students |
| `charts_finance` | Revenue 12mo, Paid vs Due, Invoice Count, Income vs Expense |
| `quick_my_students` | My Students tile |
| `quick_all_students` | All Students tile |
| `quick_universities` | Universities tile |
| `quick_users` | Total Users tile |

### Role matrix (default config)

| Role | Student KPI | App KPI | Revenue KPI | Student charts | Branch chart | Counselor chart | Finance charts | Quick access |
|------|:-----------:|:-------:|:-----------:|:--------------:|:------------:|:---------------:|:--------------:|:------------|
| 1 Super Admin | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | All + Users |
| 2 Admin | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | All + Users |
| 3 Manager | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | My, All, Universities |
| 4 Branch Manager | ✓ | ✓ | ✓ | ✓ | — | ✓ | ✓ | My, All, Universities |
| 5 Account | — | — | ✓ | — | — | — | ✓ | My Students |
| 6 IT Officer | — | — | — | — | — | — | — | My Students |
| 7 Editor | — | — | — | — | — | — | — | My Students |
| 8 Counselor | ✓ | ✓ | — | ✓ | — | — | — | My Students |

### Scope labels (badge on KPI section)

| Condition | Badge text | Data scope |
|-----------|------------|------------|
| `all_branches_role_ids` (1, 2) | All branches | Branch = user branch; branch chart shows all branches |
| `branch_overview_role_ids` (1–4) | Branch overview | Full branch students/applications |
| `counselor_scope_role_ids` (8) | My assigned students | `junior_counselor` OR `senior_counselor` = user id |
| Account (5) with revenue only | Finance overview | Branch invoices / finance entries |

Config keys:

```php
'counselor_scope_role_ids' => [8],
'branch_overview_role_ids' => [1, 2, 3, 4],
'all_branches_role_ids'    => [1, 2],
```

### Permission change kora

`config/dashboard.php` e role ID add/remove koro, tarpor:

```bash
php artisan config:clear
```

Example — Account role ke student KPI dekhte chaile:

```php
'kpi_students' => [1, 2, 3, 4, 5, 8],
```

Code e check: `DashboardAccess::can($user, 'kpi_students')`.

---

## KPI Cards (8)

| # | Card | Permission | Query |
|---|------|------------|-------|
| 1 | Total Students | `kpi_students` | `students` count (scope below) |
| 2 | New Students This Month | `kpi_students` | `created_at` current month |
| 3 | Total Applications | `kpi_applications` | `student_university` count |
| 4 | Pending Applications | `kpi_applications` | `status_id` in pending group |
| 5 | Approved Applications | `kpi_applications` | `status_id` in approved group |
| 6 | Rejected Applications | `kpi_applications` | `status_id` in rejected group |
| 7 | Total Revenue | `kpi_revenue` | `sum(invoices.paid)` branch scope |
| 8 | Revenue This Month | `kpi_revenue` | `sum(paid)` + `invoice_date` this month |

Cards jodi kono role er jonno allowed na hoy, pura KPI section hide harga na — shudhu allowed card gulo render hoy.

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

## Charts (10)

| # | Chart | Type | Permission | Data |
|---|-------|------|------------|------|
| 1 | Applications by Status | Donut | `charts_applications` | `student_university` group by status name |
| 2 | New Students (12 months) | Area | `charts_students` | Monthly `students.created_at` |
| 3 | Office Student Status | Donut | `charts_students` | `students.status` → `student_statuses` |
| 4 | Students by Intake | Column | `charts_students` | Group by intake field |
| 5 | Students by Branch | Horizontal bar | `charts_branch` | All branches (roles 1–3 only) |
| 6 | Counselor-wise Students | Horizontal bar | `charts_counselor` | Top N counselors in branch |
| 7 | Revenue Paid (12 months) | Column | `charts_finance` | Monthly `sum(invoices.paid)` |
| 8 | Paid vs Due (12 months) | Stacked column | `charts_finance` | Monthly paid + due |
| 9 | Monthly Invoice Count | Column | `charts_finance` | Invoice count per month |
| 10 | Income vs Expense (12 months) | Column | `charts_finance` | `finance_entries` by type |

Chart containers shudhu allowed chart er jonno render hoy; `dashboard-charts.js` missing container skip kore.

Config limits:

```php
'chart_months' => 12,
'counselor_chart_limit' => 10,
'intake_chart_limit' => 12,
```

---

## Data scope (queries)

| Scope | Students / Applications | Revenue / Finance |
|-------|-------------------------|-------------------|
| Counselor (role 8) | `junior_counselor = user.id` OR `senior_counselor = user.id` | User branch (`auth()->user()->branch`) |
| Branch overview (1–4) | All students in branch | Branch invoices |
| All branches label (1–2) | Same branch filter for KPI; branch chart compares all branches | Branch invoices |

Branch = `auth()->user()->branch` (existing app convention).

---

## File structure

```
config/dashboard.php
app/Support/DashboardAccess.php
app/Services/Dashboard/DashboardAnalyticsService.php
app/Http/Controllers/DashboardController.php
resources/views/dashboard/main.blade.php
resources/views/dashboard/partials/kpi-cards.blade.php
resources/views/dashboard/partials/charts.blade.php
public/css/admin/dashboard.css
public/js/admin/dashboard-charts.js
```

### Flow

1. `DashboardController@index` → `DashboardAnalyticsService::forUser($user)`
2. Service calls `DashboardAccess::for($user)` → resolves `sections`
3. Service builds only allowed `cards` + `charts`
4. Views receive `$access`, `$cards`, `$charts` — conditional `@if` per section
5. `window.dashboardChartData` = JSON of allowed charts only

---

## Responsive behaviour

- KPI grid: `col-12` → `col-sm-6` → `col-xl-3`
- Charts full-width on mobile; Highcharts responsive rules + resize reflow
- Horizontal bar charts switch to vertical on mobile (`< 768px`)

Asset cache bust: `dashboard.css?v=2`, `dashboard-charts.js?v=2`.

---

## Phase 2 (future)

- Branch filter dropdown (Super Admin — switch branch without re-login)
- Top universities bar chart
- Date range filter (custom period)
- Cache (`Cache::remember`, 5 min)
- Card click → pre-filtered list URLs with status params

---

## Deploy checklist

1. Upload all files above (including `DashboardAccess.php`)
2. `php artisan config:clear`
3. Verify `university_statuses` names match config
4. Hard refresh dashboard (`Ctrl+F5`)
5. Test at least one user per role (1, 4, 5, 8) to confirm section visibility

---

## Changelog

| Date | Change |
|------|--------|
| 2026-06-03 | Phase 1 doc + KPI cards + 3 charts implemented |
| 2026-06-03 | Full 8 KPI + 10 charts; responsive layout |
| 2026-06-03 | Role-wise dashboard via `DashboardAccess` + `config/dashboard.php` permissions |
