# Laravel Notification System Plan for Status Change

## Objective
When either Student Status or University Status is changed:
- Show a notification in the header (using Laravel Notification system)
- Send an email to the relevant user(s)
- Clicking the notification will redirect to the relevant details page

---

## Steps

### 1. Migration
- Run: `php artisan notifications:table && php artisan migrate`

### 2. Create Notification Class
- Run: `php artisan make:notification StatusChangedNotification`
- In the notification class:
  - Use both `mail` and `database` channels in `via()`
  - `toMail()` for email content
  - `toDatabase()` for notification data (include message, url, status info)

### 3. Trigger Notification
- After Student Status or University Status is updated, trigger:
  ```php
  $user->notify(new StatusChangedNotification($model, $oldStatus, $newStatus, $type));
  ```
- `$type` can be 'student' or 'university' to distinguish

### 4. Show Notification in Header
- In `header.blade.php`:
  - Show unread notification count: `Auth::user()->unreadNotifications->count()`
  - List notifications with message and link: `@foreach(Auth::user()->unreadNotifications as $notification)`

### 5. Notification Click Redirect
- Store a `url` in notification data
- In Blade: `<a href="{{ $notification->data['url'] }}">{{ $notification->data['message'] }}</a>`

### 6. Mark as Read
- When notification is viewed, mark as read: `$notification->markAsRead();`

### 7. Email
- Email will be sent automatically by Notification class (toMail)

---

## Example Notification Data Structure
```php
return [
    'message' => 'Student status changed from Pending to Approved',
    'url' => route('students.show', $student->id),
    'type' => 'student',
    'old_status' => 'Pending',
    'new_status' => 'Approved',
];
```

---

## Implementation Progress (as of 2026-03-01)

### 1. Migration
- [x] Notifications table migration created and migrated.

### 2. Notification Class
- [x] StatusChangedNotification class created.
- Accepts model, old/new status, type, and generates message + url for both mail and database.

### 3. Trigger Notification
- [x] Student Status: StudentController@update method-এ status change হলে actor (যিনি change করলেন) এবং student user—দুজনকেই notification ও email পাঠানো হচ্ছে।
- [x] University Status: StudentApplicationController@update method-এ university status change হলেও একইভাবে দুজনকেই notification ও email যাচ্ছে।

### 4. Show Notification in Header
- [ ] Next step: Blade view-তে notification দেখানো হবে (unread count, message, url সহ)।

### 5. Notification Click Redirect
- [x] Notification data-তে url যুক্ত করা হয়েছে, যাতে click করলে details page-এ নিয়ে যায়।

### 6. Email
- [x] Notification class-এর toMail() method-এ email content ও action button (details page link) যুক্ত।

---

## Example Notification Trigger
```php
$notification = new StatusChangedNotification($model, $oldStatus, $newStatus, $type);
$actor->notify($notification);
$studentUser->notify($notification);
```

---

## Next Steps
- Blade view-তে notification দেখানো ও click করলে redirect
- Mark as read functionality
- (Optional) Notification settings/customization

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
