# Student Intake History & Carry-over — User Guide

**Project:** OrangeToolz Admin  
**Last updated:** September 2026  
**Audience:** Office staff (Admin, Manager, Counselor)  
**Related:** [STUDENT_APPLICATION_INTAKE.md](./STUDENT_APPLICATION_INTAKE.md) (university application-এর intake — আলাদা বিষয়)

---

## 1. কী কেন

একজন student = একজন মানুষ।  
System Student ID (`st_office_id`, যেমন `DHA2025-001`) **কখনো বদলায় না**।

যদি কোনো student এক intake-এ যেতে না পারে এবং পরের intake-এ রাখা হয়:

- নতুন student create করবেন **না**
- ID regenerate করবেন **না**
- **Carry to Next Intake** ব্যবহার করবেন

এতে পুরনো intake history থাকবে, নতুন intake active হবে।

### উদাহরণ

| | |
|--|--|
| Student | Rahim |
| Permanent ID | `DHA2025-001` |
| Attempt 1 | March-2025 → File Back / Deferred |
| Attempt 2 | March-2027 → Active (File Open) |

---

## 2. মেনু কোথায়

| জায়গা | কী পাবেন |
|--------|----------|
| **Students → All students** | সব student |
| **Students → Carry-over Students** | যাদের ২ বা তার বেশি intake attempt আছে |
| **Dashboard** | Carry-over / Deferred / File Back সংখ্যা |
| **Student Profile** | Intake History + Carry button + Notes |

---

## 3. নতুন student তৈরি

1. **Students → Add New**
2. Intake select করুন (যেমন `March-2026`)
3. Save করুন

System automatically:

- Permanent ID তৈরি করে (`st_office_id`)
- প্রথম **intake attempt** তৈরি করে (active, outcome = In progress)

আলাদা কিছু করতে হবে না।

---

## 4. Student profile-এ Intake History দেখা

1. Students list থেকে student খুলুন (Profile)
2. **Personal Information**-এর নিচে **Intake History** section দেখুন

সেখানে থাকবে:

- Permanent Student ID
- Current intake
- Attempt table (Intake, Status, Outcome, Started, Closed, Active)
- **Attempt Notes Timeline**

---

## 5. Carry to Next Intake (মূল কাজ)

যখন student এই intake-এ যেতে পারছে না / পরের intake-এ রাখতে হবে:

### ধাপ

1. Student **Profile** খুলুন  
2. **Intake History** → **Carry to Next Intake** ক্লিক করুন  
3. ফর্ম পূরণ করুন:

| Field | মানে |
|-------|------|
| **New intake** | পরের intake (বর্তমান intake list-এ থাকবে না) |
| **Close current as** | পুরনো attempt কেন বন্ধ — সাধারণত `Deferred` বা `File back` |
| **Note** | কারণ লিখুন (optional কিন্তু recommended) |

4. **Confirm Carry Over** চাপুন

### System কী করে

1. পুরনো active attempt **বন্ধ** করে (`closed_at`, outcome set)
2. নতুন active attempt খোলে (নতুন intake, সাধারণত File Open)
3. Student-এর current `intake` + `status` update করে
4. **Permanent ID একই থাকে**

### Outcome মানে কী

| Outcome | কখন ব্যবহার করবেন |
|---------|-------------------|
| **Deferred** | ইচ্ছাকৃতভাবে পরের intake-এ সরানো |
| **File back** | ফাইল ফেরত / থেমে গেছে |
| **Cancelled** | ছাত্র withdraw করেছে |
| **Completed** | এই attempt সফলভাবে শেষ |
| **In progress** | শুধু active attempt-এ (নিজে select করবেন না) |

---

## 6. Attempt Note যোগ করা

1. Profile → Intake History → **Add Note**
2. কোন attempt-এ note যাবে select করুন (default = active)
3. Note লিখে **Save Note**

Timeline-এ দেখা যাবে: intake, সময়, কে লিখেছে, note text।

Carry-over-এর সময় যে note দেন, সেটাও timeline-এ যায়।

---

## 7. Office status পরিবর্তন

Profile-এর উপরের **office status** dropdown আগের মতোই কাজ করে।

- Status বদলালে **current (active) attempt**-এর status-ও sync হয়
- পুরনো closed attempt-এর status আলাদা থাকে (history)

---

## 8. Intake ভুল টাইপ হলে (same intake correction)

শুধু spelling/value ভুল হলে (নতুন attempt না):

1. Student **Edit** করুন  
2. Intake field ঠিক করুন  
3. Save  

Active attempt-এর intake sync হবে।  
নতুন attempt তৈরি হবে **না**।

> নতুন intake period-এ সরাতে চাইলে Edit দিয়ে শুধু intake বদলাবেন না — **Carry to Next Intake** ব্যবহার করুন, নাহলে history পরিষ্কার থাকবে না।

---

## 9. Carry-over list দেখা

**Sidebar:** Students → **Carry-over Students**

অথবা Students list-এ filter:

- **Carry-over** dropdown → **Carry-over only**

List-এ Carry-over student-এর পাশে **Carry-over** badge দেখা যায়।

---

## 10. Dashboard

যারা student KPI দেখতে পারেন, Dashboard-এ থাকবে:

- **Carry-over Students** — কতজনের ২+ attempt
- **Deferred Attempts**
- **File Back Attempts**
- Chart: **Intake Attempt Outcomes**

“View carry-overs” লিংক সরাসরি Carry-over list-এ নিয়ে যায়।

---

## 11. কী করবেন না

| ভুল কাজ | কেন |
|---------|-----|
| পরের intake-এর জন্য নতুন student create | Duplicate person, invoice/history ভাঙে |
| `st_office_id` বদলানোর চেষ্টা | System ID permanent — edit করা যায় না |
| Carry না করে শুধু intake edit করে “নতুন season” বানানো | History/report ভুল হবে |

---

## 12. Live deploy checklist

Code deploy-এর পর server-এ:

```bash
php artisan migrate
php artisan view:clear
php artisan route:clear
php artisan cache:clear
```

প্রথম migrate-এ:

- `student_intake_attempts` তৈরি + পুরনো student backfill
- `student_intake_attempt_notes` তৈরি

---

## 13. Quick checklist (office)

**নতুন file open**

- [ ] Add New Student → intake select → save  
- [ ] ID auto generate হয়েছে কিনা দেখুন  

**পরের intake-এ রাখা**

- [ ] Profile → Carry to Next Intake  
- [ ] New intake + outcome + note  
- [ ] Confirm → ID আগের মতোই আছে কিনা চেক  

**Note রাখা**

- [ ] Add Note → attempt select → save  

**List**

- [ ] Students → Carry-over Students  

---

## 14. Developer touchpoints (সংক্ষেপ)

| Item | Location |
|------|----------|
| Attempts table | `student_intake_attempts` |
| Notes table | `student_intake_attempt_notes` |
| Service | `app/Services/Student/StudentIntakeAttemptService.php` |
| Profile UI | `resources/views/admin/students/partials/intake-history.blade.php` |
| Routes | `students.carry-over-intake`, `students.intake-attempt-notes.store` |
| Sidebar | Students → Carry-over Students |

Phase 3 (optional, এখনো করা হয়নি): invoice/university application → specific attempt-এর সাথে link।
