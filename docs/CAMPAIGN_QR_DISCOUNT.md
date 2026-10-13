# Campaign QR Discount

**Project:** OrangeToolz Admin  
**Last updated:** 13 October 2026  
**Audience:** Office staff  

The discount is for the **File Opening charge** only. Staff create the campaign, the QR amounts, and the student limits from the admin **Campaigns** menu.

**Mark used** does not change any invoice. It only records that the office has already applied that discount. Staff subtract the amount on the File Opening charge themselves, then click **Mark used**.

---

## 1. What it does

One campaign can have several QR codes. Each QR has its own discount and its own student limit.

Example:

| QR | Discount | Limit |
|----|----------|-------|
| QR 1 | 5,000 | 500 students |
| QR 2 | 10,000 | 500 students |
| QR 3 | 15,000 | 500 students |
| QR 4 | 20,000 | 500 students |

A student scans a QR and fills **Name**, **Mobile number**, and **College Name**. A celebration popup then shows the discount and a **Claim Code**. From that popup they can **Download PDF**. The PDF is a slip with the AR Education logo, the discount, the code, and the student's name, mobile, and college.

The campaign page lists every student who claimed, with search by name, mobile, college, or code.

---

## 2. Who can open Campaigns

The sidebar menu is visible to **Super Admin**, **Admin**, **Manager**, and **IT Officer**.

---

## 3. Office flow

1. Open **Campaigns → New Campaign**.
2. Set the campaign name, status (**Active** or **Closed**), and one row per QR (label, discount in BDT, student limit).
3. Save. Open the campaign, then use **Download QR** or **Open form** for each code.
4. Students scan and submit. They see the popup and can save the PDF.
5. The campaign page shows who claimed, the amount, the code, and how many spots are left.
6. When the student comes to the office, find the row by mobile or Claim Code.
7. Subtract that discount from the File Opening charge on the invoice.
8. Click **Mark used**. The row becomes **Used**, with the time and the staff member who clicked it.

If **Mark used** was clicked by mistake, click **Mark available**. The claim goes back to **Available**.

---

## 4. Rules

- The student does not type the discount. The QR sets the amount.
- The same mobile number can claim the same QR only once. A later scan shows the original discount and the original code, including after the QR is full, paused, or the campaign is closed.
- Spaces and dashes in the mobile number are ignored. `01711-111 222` and `01711111222` are the same number. `017...` and `88017...` are treated as two different numbers.
- Each QR is separate. The same mobile can claim a different QR in the same campaign and receive that QR's own discount.
- When the limit is full, new students cannot claim that QR. Students who already claimed can still open their code by submitting the same mobile.
- Pausing a QR, or setting the campaign to **Closed**, stops new claims. Existing claims stay.
- Changing a QR amount later does not change discounts already given. New claims use the new amount.
- The student limit cannot be saved below the number of claims that QR already has.
- A QR that already has claims is kept if its row is removed from the edit form.
- **Mark used** does not deduct the invoice. It is the office record that the discount was applied.

---

## 5. Where it lives

| Item | Location |
|------|----------|
| Admin menu | Campaigns |
| Public form | `/campaign/{token}` |
| Popup and PDF | `/campaign/{token}/claimed/{code}` and `/campaign/{token}/claimed/{code}/pdf` |
| Tables | `campaigns`, `campaign_offers`, `campaign_claims` |
| Claim service | `app/Services/Campaign/CampaignClaimService.php` |
| Staff guide | this file |
