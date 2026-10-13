# Campaign QR Discount

**Project:** OrangeToolz Admin  
**Last updated:** 13 October 2026  
**Audience:** Office staff  

The discount is for the **File Opening charge** only. Each campaign has **one QR**. Staff set the discount amounts, and how many times each amount can be won, from the admin **Campaigns** menu.

**Mark used** does not change any invoice. It only records that the office has already applied that discount. Staff subtract the amount on the File Opening charge themselves, then click **Mark used**.

---

## 1. What it does

One campaign has one QR code. Behind that QR is a list of discount amounts. Each amount has a count: how many students can win it.

Example:

| Amount | How many times it can be won |
|--------|------------------------------|
| 5,000 | 300 |
| 10,000 | 150 |
| 15,000 | 40 |
| 20,000 | 10 |

A student scans the QR and fills **Name**, **Mobile number**, and **College Name**. The system picks one remaining slot at random. An amount with more slots left is more likely to be picked. When an amount's slots are finished, it drops out of the draw.

A celebration popup then shows the discount they won and a **Claim Code**. From that popup they can **Download PDF**. The PDF is a slip with the AR Education logo, the discount, the code, and the student's name, mobile, and college.

The campaign page lists every student who claimed, with search by name, mobile, college, or code.

---

## 2. Who can open Campaigns

The sidebar menu is visible to **Super Admin**, **Admin**, **Manager**, and **IT Officer**.

---

## 3. Office flow

1. Open **Campaigns → New Campaign**.
2. Set the campaign name, status (**Active** or **Closed**), and one row per discount (label, amount in BDT, how many times it can be won).
3. Save. Open the campaign and use **Download QR** or **Open form**. There is one QR for the whole campaign.
4. Students scan and submit. They see the popup and can save the PDF.
5. The campaign page shows who claimed, the amount, the code, and how many spots are left.
6. When the student comes to the office, find the row by mobile or Claim Code.
7. Subtract that discount from the File Opening charge on the invoice.
8. Click **Mark used**. The row becomes **Used**, with the time and the staff member who clicked it.

If **Mark used** was clicked by mistake, click **Mark available**. The claim goes back to **Available**.

---

## 4. Rules

- The student does not choose the discount. One remaining slot is drawn at random. Amounts with more slots left come up more often.
- The same mobile number can win once per campaign. A later scan shows the original discount and the original code, including after the draw is full, an amount is paused, or the campaign is closed.
- Spaces and dashes in the mobile number are ignored. `01711-111 222` and `01711111222` are the same number. `017...` and `88017...` are treated as two different numbers.
- When every amount's slots are used, new students cannot claim. Students who already claimed can still open their code by submitting the same mobile.
- Pausing one amount removes only that amount from the draw. Setting the campaign to **Closed** stops all new claims. Existing claims stay.
- Changing an amount later does not change discounts already given. New wins use the new amount.
- "How many times" cannot be saved below the number of students who already won that amount.
- An amount that already has winners is kept if its row is removed from the edit form.
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
