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
