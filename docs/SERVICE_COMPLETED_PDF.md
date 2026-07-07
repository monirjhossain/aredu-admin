# Service Completed PDF — Documentation

> **Status: Implemented (Phase 1)**  
> When a student’s **last invoice** is fully paid (`due = 0`), staff can download a **Service Completed** PDF from the student invoice list.  
> The PDF has **Student Copy** (top) and **Office Copy** (bottom) on **one A4 page**, separated by a cut line.  
> On first successful download, office status auto-updates to **Service Complete**.

---

## 1. How it works (quick)

1. Open `/student/invoices?student={id}`
2. On the **last invoice** row where **Due = 0** → green PDF button in the Action column
3. Click → PDF downloads (`Service-Completed-{student-id}.pdf`)
4. Student office status → **Service Complete** (skipped if already set)

```
Last invoice?  No → no button
Due == 0?      No → no button
Yes            → show PDF button → validate → update status → stream PDF
```

---

## 2. Business rules (confirmed)

| # | Rule | Implementation |
|---|------|----------------|
| 1 | Button only on **last invoice** when `due = 0` | `Invoice::lastForStudent()` + `isFullyPaid()` |
| 2 | Payment history = **invoices only** | All student invoices in one table (no `finance_entries`) |
| 3 | **One admitted** university | Most advanced admitted `student_university` row |
| 4 | **English** | All PDF text in English |
| 5 | Auto status update | `OfficeStatusService::update()` → **Service Complete** |
| 6 | **No branch logo** on this PDF | Text-only certificate header (invoice print still uses branch logo separately) |

---

## 3. Button visibility

**Last invoice** = newest by `COALESCE(invoice_date, DATE(created_at)) DESC`, then `id DESC`.

| Row | Due | Last? | Button? |
|-----|-----|-------|---------|
| Older invoice | 0 | No | No |
| Last invoice | 5,000 | Yes | No |
| Last invoice | 0 | Yes | **Yes** |

- **UI:** Green `btn-success` with `fa-file-pdf` icon, title *Download Service Completed PDF*
- **Scope:** Only on student invoice list (`$url === 'student.invoices'`), not finance-entries invoices
- **Controller:** `StudentInvoiceController@index` sets `$serviceCompletedInvoiceId` when last invoice is fully paid

**Route:** `GET /student/invoices/{invoice}/service-completed`  
**Name:** `student.invoices.service-completed`

---

## 4. PDF layout

**One A4 page** — vertical stack, no page break between copies:

```
┌─────────────────────────────────────┐
│              SERVICE COMPLETED      │  ← right-aligned text header
│              CERTIFICATE            │     (no branch logo)
│              STUDENT COPY           │
│  Student Information                │
│  Admitted University                │
│  Invoice Payment History            │
│  Declaration + Signatures           │
├ ─ ─ ─ cut here ─ ─ ─ ─ ─ ─ ─ ─ ─ ─┤
│  (same blocks)                      │
│              OFFICE COPY            │
└─────────────────────────────────────┘
```

Rendered by `pdf/service-completed.blade.php` including `pdf/partials/service-completed-copy.blade.php` twice.

---

## 5. PDF content

### Header (each copy)

| Field | Source |
|-------|--------|
| Title | SERVICE COMPLETED / CERTIFICATE (right-aligned) |
| Certificate No | `SC-{branch_short}-{year}-{student_id}` e.g. `SC-DHA-2026-0009` |
| Date | Generation date (`d M Y`) |
| Copy label | STUDENT COPY / OFFICE COPY |
| Office note | Office copy only: *Office Copy — For internal record only* |

### Student information

Student ID (`st_office_id` via `StudentDisplay::officeStudentId`), name, passport, phone, email, address, office status, senior/junior counselor.

### Admitted university (one row)

Statuses from `config/service_completed.php` → `admitted_status_names`.  
If multiple applications match, pick the **highest rank** in that list; tie-break by `updated_at` DESC.

Columns: University, Subject, Intake, Status, Application No.

**Intake:** `student_university.intake` first; if empty → `students.intake`; else `—`.

### Invoice payment history

All invoices for the student (oldest first): #, Invoice No, Date, Services, Total, Paid, Due + totals row.

- Services from `invoice.products['product']` via `ServiceCompletedDocumentService::invoiceServiceLines()`
- **Not included:** `finance_entries` table

### Footer

- Declaration text from `config/service_completed.php`
- Signatures: Student | Counselor | Authorized Officer
- Branch name / phone (compact footer)

---

## 6. Office status update

| Item | Value |
|------|-------|
| Target status | **Service Complete** (`student_statuses`, resolved by name from config) |
| When | Inside DB transaction before PDF stream |
| How | `OfficeStatusService::update()` — notifications/email like manual status change |
| Re-download | Allowed; if status already **Service Complete**, update is skipped (`changed: false`) |

---

## 7. Technical flow

```
/student/invoices?student=9
    → index sets serviceCompletedInvoiceId (last + due=0)
    → [PDF button] on matching row
    → GET /student/invoices/{id}/service-completed
    → StudentInvoiceController@serviceCompletedPdf
        → ServiceCompletedDocumentService::build()     validate + load data
        → DB::transaction → OfficeStatusService::update()
        → view pdf.service-completed → PdfGenerator (mPDF)
    → browser download .pdf
    → on error: flash message + redirect back
```

**Download filename:** `Service-Completed-{officeStudentId}.pdf` (non-alphanumeric chars sanitized)

---

## 8. Files (implemented)

| File | Role |
|------|------|
| `config/service_completed.php` | Admitted statuses, declaration, certificate prefix, office status name |
| `app/Services/ServiceCompletedDocumentService.php` | Validation, data assembly, intake resolve, `canShowButton()` |
| `app/Support/PdfGenerator.php` | mPDF wrapper (`basePath` = `public_path()`) |
| `app/Services/Student/OfficeStatusService.php` | Status update + notifications |
| `app/Models/Account/Invoice.php` | `lastForStudent()`, `isFullyPaid()`, `isLastForStudent()` |
| `app/Http/Controllers/Admin/StudentInvoiceController.php` | `index()` button id, `serviceCompletedPdf()` |
| `resources/views/pdf/service-completed.blade.php` | Main PDF wrapper + CSS |
| `resources/views/pdf/partials/service-completed-copy.blade.php` | Single copy block (included twice) |
| `resources/views/admin/invoices/index.blade.php` | PDF button in action column |
| `routes/web.php` | Route registered **before** `invoices` resource under `student` prefix |

**Related (separate feature):** Invoice browser print (`resources/views/admin/invoices/invoice.blade.php`) — unchanged; uses `asset($student->branchInfo->logo)` for branch logo. Not part of Service Completed PDF.

**Dependency:** `mpdf/mpdf` ^8.0 (`composer.json`)

---

## 9. Config (`config/service_completed.php`)

```php
'admitted_status_names' => [
    'Pre Admitted',
    'Admission Confirmation Fee Paid',
    'Admitted',
    'JW Issued',
    'File Processing Completed',
],
'office_status_name' => 'Service Complete',
'certificate_prefix' => 'SC',
'declaration' => 'This certifies that...',
```

After edit: `php artisan config:clear`

---

## 10. Security & validation

| Check | On failure |
|-------|------------|
| User logged in | Laravel auth |
| Invoice `branch_id` = user `branch` | RuntimeException → flash error |
| Invoice linked to student | RuntimeException |
| Requested invoice = last for student | RuntimeException |
| `due = 0` on that invoice | RuntimeException |
| At least one admitted university application | RuntimeException, no PDF |

---

## 11. Testing checklist

| Test | Expected |
|------|----------|
| Last invoice due > 0 | No button |
| Last invoice due = 0 | Button on last row only |
| Finance-entries invoice list | No button (student list only) |
| No admitted application | Error flash, no PDF |
| Successful generate | PDF downloads, status = Service Complete |
| Re-download | PDF OK; status not re-notified if already Service Complete |
| Intake on student only | Shows in PDF (fallback) |
| Header | Text-only; no branch logo image |

---

## 12. FAQ

**Q: Why last invoice only?**  
Final payment row — when its due is 0, the billing journey for that student is complete.

**Q: Finance entries in PDF?**  
No — only invoice payment history (by design).

**Q: Branch logo on Service Completed PDF?**  
No — certificate uses a text header only. Branch logo still appears on the separate invoice print view.

**Q: Invoice print vs Service Completed?**  
Invoice print = single receipt (browser print, with logo). Service Completed = final certificate with full invoice history + admitted university + dual copy (PDF download, no logo).

---

## 13. Future (not implemented)

- [ ] `service_completed_certificates` audit log table
- [ ] Button on student show page
- [ ] Email PDF to student
- [ ] Role-based button visibility policy
- [ ] Optional branch logo on certificate (if required later)

---

## 14. Changelog

| Date | Change |
|------|--------|
| 2026-06-03 | Beginner-friendly plan created |
| 2026-06-03 | Phase 1 implemented: PDF, button, status update |
| 2026-06-03 | Removed finance_entries from PDF; single-page top/bottom layout |
| 2026-06-03 | Intake fallback: application → student profile |
| 2026-06-03 | Removed BranchLogo helper; Service Completed PDF is text-header only |
| 2026-06-03 | Doc synced with current implementation |
