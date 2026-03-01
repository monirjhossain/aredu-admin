# Notification System Documentation (Updated)

## Overview
This system notifies users (students and admins) when a student or university application status changes. Notifications are shown in the header dropdown and sent via email using customizable templates.

## Features
- Notification on status change (Student/University)
- Notification message shows actor name and new status name
- Notification shown in header dropdown (unread/read separation)
- Notification sent via email using SMS template from settings
- Email and notification message support placeholders
- AJAX-based notification read and badge update
- Minimal, user-friendly UI

## How It Works
1. **Status Change Trigger**
   - When a student or university application status is updated, a notification is triggered.
   - The notification includes: actor name (who changed), new status name, and student name.

2. **Notification Message**
   - Message format: "[Type] status changed by [Actor Name] to [Status Name]."
   - For email, a template named `[Status Change]` is used from settings (SmsTemplate model).
   - Placeholders supported: `[Student Name]`, `[Status Name]`, `[Actor Name]`.
   - If template not found, a default message is used.

3. **Notification Storage**
   - Each status change creates a new notification (does not overwrite previous).
   - Notifications are stored in the database and shown in the header dropdown.

4. **Header Dropdown**
   - Shows unread notifications first, then read notifications.
   - Clicking a notification marks it as read and redirects to the relevant page.
   - Badge count updates via AJAX.

5. **Email Notification**
   - Sent to the student using the custom template.
   - Uses the same placeholder logic as the header notification.

6. **Best Practice**
   - Always shows the latest (updated) status in the notification message.
   - Status name is fetched after saving the update to ensure accuracy.

## Key Files
- `app/Notifications/StatusChangedNotification.php` (notification logic)
- `app/Http/Controllers/Admin/StudentController.php` (student status change trigger)
- `app/Http/Controllers/Admin/StudentApplicationController.php` (university status change trigger)
- `resources/views/layouts/partials/notifications.blade.php` (header dropdown UI)
- `app/Mail/MailSystem.php` & `resources/views/admin/mail/mailTemplate.blade.php` (email template)
- `app/Models/SmsTemplate.php` (template storage)

## How to Customize
- Edit notification email template in settings (add/edit `[Status Change]` template)
- Use placeholders in template: `[Student Name]`, `[Status Name]`, `[Actor Name]`
- UI can be customized in `notifications.blade.php`

---
_Last updated: March 1, 2026_
