# Product Module — Specification

**Status:** Spec only — **do not implement until the owner says "done"**  
**Version:** 1.3  
**Date:** 2026-09-25  
**Scope:** Fully standalone module — **100% online** + **dedicated Student Portal** — bank transfer checkout (SSLCommerz later)

---

## 1. Purpose

**Primary audience:** students who will **not come to the office**. They use the website + a **separate Student Portal** to do everything online.

Flow:

1. Public site → browse Products  
2. Register / Login → **Student Portal**  
3. Select product → checkout → see bank details  
4. Pay from home → upload Trx / proof in portal  
5. Track order status in portal until admin activates  

This module is **completely separate** from the rest of OrangeToolz business logic (and separate from the existing public Login page if it was only a placeholder).

### Design principle: make online easy

1. Mobile-first Student Portal  
2. No office visit required  
3. Own portal login (email/phone + password)  
4. Dashboard: my orders, payment status, bank info, proof upload  
5. One-tap copy: bank account, amount, order number  
6. WhatsApp help with order details  
7. Bangla payment instructions  

---

## 2. What this module is NOT

Do **not** mix with:

| Existing feature | Why separate |
|------------------|--------------|
| Settings → Products (`products` table) | Agency fee line items |
| Invoices / Wallets / Finance | Different ledger |
| Admin `students` / `student_university` | Office CRM application pipeline — **not** this portal |
| Website CMS posts | Catalog lives only in Product Module tables |
| `website_settings` | Bank info has module-own settings |

---

## 3. Two surfaces on frontend

| Surface | URL idea | Who | Purpose |
|---------|----------|-----|---------|
| **Public marketing site** | `/products`, `/product/:slug` | Anyone | Browse & filter offerings |
| **Student Portal** | `/portal/...` | Logged-in students | Orders, pay instructions, track, profile |

Public catalog can show products to guests; **checkout and order management require Student Portal account**.

---

## 4. User roles

| Role | Can do |
|------|--------|
| **Guest** | Browse product list + detail only |
| **Portal student** | Register/login, checkout, see bank info, my orders, upload proof, track status, profile |
| **Admin** | Product Module: products CRUD, orders (mark paid / activate / reject), bank settings, see student accounts if needed |

---

## 5. Student Portal (frontend) — pages

Base path: **`/portal`**

| Route | Page |
|-------|------|
| `/portal/register` | Create account (name, phone, email, password) |
| `/portal/login` | Login |
| `/portal/forgot-password` | Reset (v1: simple email/phone reset or admin reset) |
| `/portal` or `/portal/dashboard` | Home: active/pending orders summary + quick links |
| `/portal/products` | Same catalog, logged-in context (or redirect to public `/products`) |
| `/portal/orders` | My orders list |
| `/portal/orders/:orderNo` | Order detail: status, bank block, copy buttons, Trx/proof upload |
| `/portal/checkout/:slug` | Checkout (auth required) |
| `/portal/profile` | Edit name, phone, email, password |

**Portal UX must-haves**

- [ ] Mobile-first layout  
- [ ] Clear status badges: Pending payment / Paid / Active / Rejected  
- [ ] Copy bank / amount / order no  
- [ ] Bangla “how to pay” text  
- [ ] WhatsApp deep link  
- [ ] Proof upload (image + Trx ID)  
- [ ] Logout  

Auth: token-based API (Laravel Sanctum or simple API token) stored in frontend — **portal users table is module-own**, not admin `users` / `students`.

---

## 6. Portal auth data (`program_portal_students`)

Separate table (standalone module):

| Field | Notes |
|-------|--------|
| `id` | PK |
| `name` | |
| `phone` | unique |
| `email` | unique |
| `password` | hashed |
| `whatsapp` | optional |
| `status` | active / blocked |
| `remember` / timestamps | |

Orders link via `program_portal_student_id` (FK) + keep buyer snapshot fields for admin display.

---

## 7. Product fields (catalog item)

Each Product = one **program offering**.

| Field | Type | Description |
|-------|------|-------------|
| `program_id` | string, unique | Code shown to students |
| `title` | string | Display title |
| `slug` | string, unique | URL key |
| `university_name` | string | Stored on product |
| `city_name` | string | Stored on product |
| `degree` | string | Bachelor, Master, PhD, MBBS, Diploma… |
| `teaching_language` | string | Chinese / English / Both |
| `regular_rmb` | decimal | Full tuition in RMB (shown on card) |
| `discount_rmb` | decimal | Discounted tuition in RMB (shown on card) |
| `currency` | string | Default `RMB` / CNY |
| `finance_type` | enum | `scholarship` \| `self_financed` \| `both` |
| `scholarship_types` | JSON | `A`, `B`, `C`, `D` |
| `scholarship_info` | text | Full scholarship explanation (eligibility, coverage, stipend, notes) |
| `intake` | string | e.g. March-2026 |
| `description` | text | |
| `image` | file/url | Optional |
| `status` | enum | `draft` \| `active` \| `inactive` |

### Card vs detail (student view)

List and portal catalog show **cards**. Each card shows at a glance:

- Title / university / city / degree / intake
- **Regular RMB** (original fee)
- **Discount RMB** (fee after discount / scholarship path)

**Click the card** → open product detail. That page shows **all scholarship-related information** for that offering:

- Finance type (Scholarship / Self-financed)
- Scholarship types available: **A, B, C, D**
- `scholarship_info` (full text: what is covered, conditions, stipend, etc.)
- Regular RMB vs Discount RMB
- Teaching language, city, degree, intake, program id
- Then student can continue to portal checkout if they want to buy

Admin fills Regular RMB, Discount RMB, and scholarship details on the product form. Cards and the detail page read those fields. No extra calculation in the frontend.

---

## 8. Order fields

| Field | Description |
|-------|-------------|
| `order_no` | Unique, e.g. `PP-20260924-ABCD` |
| `program_product_id` | FK |
| `program_portal_student_id` | FK to portal student |
| Snapshot | program_id, title, university, city, degree, intake, regular_rmb, discount_rmb |
| Buyer snapshot | name, phone, email, whatsapp |
| `selected_finance_type` | scholarship / self_financed |
| `selected_scholarship_type` | A/B/C/D or null |
| `amount`, `currency` | Amount student will pay (Discount RMB by default; Regular RMB if no discount) |
| `payment_method` | `bank_transfer` (v1) |
| `payment_status` | `pending` → `paid` → `cancelled` |
| `order_status` | `pending_payment` → `active` → `rejected` |
| `trx_id`, `sender_account`, `payment_proof` | From portal |
| `payment_note`, `paid_at`, `activated_at`, `activated_by` | Admin |

---

## 9. Bank settings (module-own)

- Account name, bank, number, branch, routing/swift  
- Instructions in **Bangla + English**  
- Shown in portal order detail until paid/active  

---

## 10. Fully online journey (with portal)

```
Guest browses /products
        ↓
Register / Login → Student Portal
        ↓
Select product → /portal/checkout/:slug
        ↓
Order created → pending
        ↓
Portal order page:
  bank details + copy + Bangla steps + WhatsApp
        ↓
Pay from home → upload Trx / proof in portal
        ↓
Admin Mark Paid → Activate
        ↓
Portal shows Active (student refreshes / notified later)
```

**Phase 2:** SSLCommerz inside same portal checkout.

---

## 11. Admin UI

**Product Module** (top-level sidebar)

1. **Products** — CRUD  
2. **Orders** — pending / paid / active; Mark paid, Activate, Reject; view proof  
3. **Portal students** (optional list) — view / block account  
4. **Bank settings**  

Routes:

- `/program-products`  
- `/program-orders`  
- `/program-portal-students`  
- `/program-product-settings`  

---

## 12. Public / Portal API (proposed)

| Method | Endpoint | Auth | Purpose |
|--------|----------|------|---------|
| GET | `/api/program-products` | Public | Catalog |
| GET | `/api/program-products/{slug}` | Public | Detail |
| POST | `/api/program-portal/register` | Public | Create portal account |
| POST | `/api/program-portal/login` | Public | Login → token |
| GET | `/api/program-portal/me` | Portal | Profile |
| PUT | `/api/program-portal/me` | Portal | Update profile |
| POST | `/api/program-orders` | Portal | Create order |
| GET | `/api/program-orders` | Portal | My orders |
| GET | `/api/program-orders/{order_no}` | Portal | Order detail |
| POST | `/api/program-orders/{order_no}/proof` | Portal | Upload proof |

---

## 13. Database tables (proposed)

1. `program_products`  
2. `program_orders`  
3. `program_product_settings`  
4. `program_portal_students`  

Models: `ProgramProduct`, `ProgramOrder`, `ProgramProductSetting`, `ProgramPortalStudent`

---

## 14. Implementation checklist

- [ ] Migrations + models + seeder  
- [ ] Portal auth APIs (register / login / me)  
- [ ] Admin: Products + Orders + Bank settings (+ portal students list)  
- [ ] Public product APIs  
- [ ] Portal order APIs + proof upload  
- [ ] Frontend public: `/products`, `/product/:slug`  
- [ ] Frontend portal: login, register, dashboard, orders, checkout, profile  
- [ ] Copy buttons + WhatsApp + Bangla pay text  
- [ ] Mobile E2E test  

---

## 15. Defaults (v1)

| Topic | Default |
|-------|---------|
| Audience | Remote students + dedicated portal |
| Browse | Public without login |
| Checkout / orders | Student Portal login required |
| After Activate | Portal shows Active (no admin CRM student auto-create) |
| Gateway | Phase 2 |

---

## 16. Summary

| Item | Decision |
|------|----------|
| Product | University program offering |
| Who | Online-only students |
| Frontend | Public catalog + **separate Student Portal** |
| Module | Fully standalone |
| Payment v1 | Bank transfer + admin activate |
| Payment v2 | SSLCommerz later |
| Mix with office Student CRM | No |

---

*Document v1.3 — cards show Regular RMB + Discount RMB; click card shows full scholarship info. **Implementation stays paused until the owner says "done".***
