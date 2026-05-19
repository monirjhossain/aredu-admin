# Invoice Module Refactor

## Overview

`StudentInvoiceController` এর `index` এবং `store` method clean করা হয়েছে। নতুন files তৈরি, model scopes যোগ, এবং dead code remove করা হয়েছে।

---

## নতুন Files

### 1. `app/Http/Requests/Invoice/InvoiceIndexFilterRequest.php`
Invoice index page-এর filter parameters validate করে।

| Field | Rules |
|---|---|
| `student` | nullable, integer, exists:students |
| `search` | nullable, string, max:255 |
| `date_from` | nullable, date |
| `date_to` | nullable, date, after_or_equal:date_from |
| `user_id` | nullable, integer, exists:users |
| `branch_id` | nullable, integer, exists:branches |
| `perpage` | nullable, integer, in:5,10,15,25,50,100,200,500 |

### 2. `app/Services/InvoiceCreationService.php`
Invoice create করার business logic controller থেকে আলাদা করা হয়েছে।

**Methods:**
- `create(Request, User): Invoice` — DB transaction-এ invoice save, currency/exchange rate সেট, products payload build, FinanceEntry create, wallet debitCredit
- `buildProductsPayload(Request): array` — products/prices/ids থেকে payload তৈরি করে
- `postWalletTransaction(...)` — wallet_id থাকলে wallet-এ transaction post করে

---

## পরিবর্তিত Files

### 3. `app/Models/Account/Invoice.php` — Query Scopes

**যোগ করা হয়েছে:**

| Scope | কাজ |
|---|---|
| `scopeForStudent($studentId)` | নির্দিষ্ট student এর invoices |
| `scopeForBranch($branchId)` | নির্দিষ্ট branch এর invoices |
| `scopeSearchTerm($search)` | invoice_no, products, student name/mobile/passport, user name দিয়ে search |
| `scopeInvoiceDateBetween($from, $to)` | date range filter |
| `scopeCollectedBy($userId)` | নির্দিষ্ট user এর collect করা invoices |

**Remove করা হয়েছে:**
- `scopeForBranchOrStudent` — 3-argument hack ছিল, `scopeForStudent` + `scopeForBranch` দিয়ে replace
- `scopePaymentStatus` — view থেকে payment status filter remove করার পর dead code হয়ে গিয়েছিল

### 4. `app/Http/Controllers/Admin/StudentInvoiceController.php`

**`index` method:**
- `InvoiceIndexFilterRequest` inject করা হয়েছে (আগে plain `Request` ছিল)
- `->when($studentId, ...)` দিয়ে conditionally `forStudent` বা `forBranch` scope apply হয়
- `branch_id` filter থাকলে সেই branch, না থাকলে current user-এর branch use হয়
- `$data['branches']` = সব branches view-এ pass হচ্ছে
- `$data['collectors']` = current user-এর branch-এর users

**`store` method:**
- `InvoiceCreationService->create()` call করে — controller শুধু request/response handle করে

**Remove করা হয়েছে:**
- Unused imports: `Category`, `Inquiry`, `WalletService`
- Dead `.paymentStatus()` scope call

### 5. `resources/views/admin/invoices/index.blade.php`

**Filter row-এ আছে:**
- Search input (invoice_no, student name, mobile, passport, user)
- Date From / Date To
- Collected By dropdown
- Branch dropdown
- Per page dropdown

**Remove করা হয়েছে:**
- Payment Status dropdown

---

## Design Patterns Used

- **Thin Controller** — business logic Service-এ
- **Form Request** — validation controller-এ না থেকে আলাদা class-এ
- **Eloquent Scopes** — query logic Model-এ, controller clean রাখে
- **Single Responsibility** — প্রতিটি class একটা কাজ করে
