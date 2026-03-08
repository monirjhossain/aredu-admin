# Messaging System Documentation

Facebook-style **private** messaging system for OrangeToolz Admin Panel.

---

## Overview

Header-এ Notification Icon এর পাশে একটি Inbox icon থাকবে। Users একজন আরেকজনের সাথে **private text messaging** করতে পারবে। Facebook Messenger এর মতো UI/UX হবে।

**Design Principles:**
- **Private only** — শুধুমাত্র 1-to-1 messaging, group chat নেই (YAGNI)
- **Simple first** — AJAX polling দিয়ে start, পরে WebSocket upgrade করা যাবে
- **Soft delete** — Messages ও conversations soft delete supported
- **Secure** — শুধুমাত্র conversation participants message দেখতে পারবে

---

## Database Schema

### 1. `conversations` table

| Column          | Type                        | Description                              |
| --------------- | --------------------------- | ---------------------------------------- |
| id              | bigIncrements (PK)          |                                          |
| created_by      | foreignId → users           | কে create করেছে                          |
| last_message_id | unsignedBigInteger, nullable| শেষ message এর reference (fast loading)  |
| created_at      | timestamp                   |                                          |
| updated_at      | timestamp                   |                                          |
| deleted_at      | timestamp, nullable         | Soft delete                              |

> **Note:** `type` ও `name` column নেই — শুধুমাত্র private (1-to-1) conversation support করে।

### 2. `conversation_participants` table

| Column          | Type               | Description                                    |
| --------------- | ------------------ | ---------------------------------------------- |
| id              | bigIncrements (PK) |                                                |
| conversation_id | foreignId → conversations | কোন conversation                        |
| user_id         | foreignId → users  | কোন user                                       |
| last_read_at    | timestamp, nullable| User last কখন read করেছে (unread count এর জন্য)|
| created_at      | timestamp          |                                                |
| updated_at      | timestamp          |                                                |

**Unique constraint:** `(conversation_id, user_id)` — একটি conversation-এ একজন user একবারই থাকবে।

### 3. `messages` table

| Column          | Type                          | Description          |
| --------------- | ----------------------------- | -------------------- |
| id              | bigIncrements (PK)            |                      |
| conversation_id | foreignId → conversations     | কোন conversation-এ   |
| sender_id       | foreignId → users             | কে পাঠাচ্ছে          |
| body            | text                          | Message text         |
| created_at      | timestamp                     |                      |
| updated_at      | timestamp                     |                      |
| deleted_at      | timestamp, nullable           | Soft delete          |

> **Note:** Phase 1-এ শুধু text message। File attachment পরে Phase 2-তে `type` ও `file_path` column add হবে।

### ER Diagram

```
┌─────────┐       ┌──────────────────────────┐       ┌──────────────┐
│  users   │──────<│ conversation_participants │>──────│ conversations│
│          │       │                          │       │              │
│ id       │       │ conversation_id          │       │ id           │
│ name     │       │ user_id                  │       │ created_by   │
│ ...      │       │ last_read_at             │       │ last_msg_id  │
└─────────┘       └──────────────────────────┘       │ deleted_at   │
     │                                                └──────┬───────┘
     │            ┌────────────┐                             │
     └───────────>│  messages   │<───────────────────────────┘
                  │            │
                  │ id         │
                  │ conv_id    │
                  │ sender_id  │
                  │ body       │
                  │ deleted_at │
                  └────────────┘
```

---

## Backend Architecture

### Models

| Model                                        | Location                                        |
| -------------------------------------------- | ----------------------------------------------- |
| `App\Models\Messaging\Conversation`          | `app/Models/Messaging/Conversation.php`         |
| `App\Models\Messaging\ConversationParticipant`| `app/Models/Messaging/ConversationParticipant.php`|
| `App\Models\Messaging\Message`               | `app/Models/Messaging/Message.php`              |

### Model Relationships

```
User
 ├── conversations()  → belongsToMany(Conversation) via conversation_participants
 └── sentMessages()   → hasMany(Message, 'sender_id')

Conversation  (uses SoftDeletes)
 ├── participants()   → belongsToMany(User) via conversation_participants
 ├── messages()       → hasMany(Message)
 ├── lastMessage()    → belongsTo(Message, 'last_message_id')
 ├── creator()        → belongsTo(User, 'created_by')
 ├── otherUser()      → participants except auth user (private chat helper)
 └── unreadCount($u)  → messages count where created_at > participant.last_read_at

ConversationParticipant
 ├── conversation()   → belongsTo(Conversation)
 └── user()           → belongsTo(User)

Message  (uses SoftDeletes)
 ├── conversation()   → belongsTo(Conversation)
 └── sender()         → belongsTo(User, 'sender_id')
```

### Controller

**`App\Http\Controllers\MessageController`**

| Method               | HTTP     | Route                        | Description                          |
| -------------------- | -------- | ---------------------------- | ------------------------------------ |
| `index()`            | GET      | `/messages`                  | Inbox page - conversation list       |
| `show($conversation)`| GET      | `/messages/{conversation}`   | Load conversation messages           |
| `store($conversation)`| POST    | `/messages/{conversation}`   | Send new message                     |
| `startConversation()`| POST     | `/messages/start`            | Start new conversation (user select) |
| `unreadCount()`      | GET      | `/messages/unread-count`     | Header badge unread count (AJAX)     |
| `markAsRead($conv)`  | POST     | `/messages/{conversation}/read`| Mark conversation as read          |
| `searchUsers()`      | GET      | `/messages/search-users`     | Search users for new conversation    |

### Routes

```php
// routes/web.php — inside auth middleware group
Route::prefix('messages')->group(function () {
    Route::get('/', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/unread-count', [MessageController::class, 'unreadCount'])->name('messages.unreadCount');
    Route::get('/search-users', [MessageController::class, 'searchUsers'])->name('messages.searchUsers');
    Route::post('/start', [MessageController::class, 'startConversation'])->name('messages.start');
    Route::get('/{conversation}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/{conversation}', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/{conversation}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
});
```

---

## Frontend Architecture

### Header Icon (Notification এর পাশে)

```
┌─────────────────────────────────────────────────┐
│  Header                                         │
│                          [✉️ 3] [🔔 5] [👤]     │
│                          inbox  notif  profile  │
└─────────────────────────────────────────────────┘
```

- Notification icon এর **বাম পাশে** একটি envelope/message icon
- Unread message count badge (লাল badge)
- Click করলে dropdown panel open হবে

### Dropdown Panel (Header Click)

```
┌──────────────────────────────────┐
│  Messages                    ✏️  │  ← "New Message" button
├──────────────────────────────────┤
│  [avatar] Rahim Khan            │
│  "Okay, I'll send the doc..."   │
│  2 min ago              ● (dot) │  ← unread = bold + dot
├──────────────────────────────────┤
│  [avatar] Karim Ahmed           │
│  "Thanks for the update"        │
│  1 hour ago                     │
├──────────────────────────────────┤
│  [avatar] Sarah Begum           │
│  "Meeting confirmed for tomorrow│
│  3 hours ago                    │
├──────────────────────────────────┤
│        See All Messages →       │  ← links to /messages
└──────────────────────────────────┘
```

### Full Inbox Page (`/messages`)

```
┌────────────────────────┬───────────────────────────────────────────┐
│   CONVERSATIONS        │   CHAT WINDOW                            │
│                        │                                          │
│  🔍 Search...          │   👤 Rahim Khan                          │
│  ┌──────────────────┐  │   ─────────────────────────────────────  │
│  │ [●] Rahim Khan   │  │                                          │
│  │ "Okay, I'll se.. │←─│─selected  ┌─────────────────────┐       │
│  │ 2 min ago        │  │           │ Hi, how are you?    │       │
│  ├──────────────────┤  │           │              10:30 AM│       │
│  │ Karim Ahmed      │  │           └─────────────────────┘       │
│  │ "Thanks for th.. │  │   ┌─────────────────────┐               │
│  │ 1 hour ago       │  │   │ I'm good, thanks!   │               │
│  ├──────────────────┤  │   │ 10:31 AM             │               │
│  │ Sarah Begum      │  │   └─────────────────────┘               │
│  │ "Meeting confir..│  │           ┌─────────────────────┐       │
│  │ 3 hours ago      │  │           │ Okay, I'll send the │       │
│  └──────────────────┘  │           │ doc tomorrow        │       │
│                        │           │              10:35 AM│       │
│                        │           └─────────────────────┘       │
│                        │                                          │
│                        │   ┌──────────────────────────────┬──┐   │
│                        │   │ Type a message...            │📎│   │
│                        │   └──────────────────────────────┴──┘   │
└────────────────────────┴───────────────────────────────────────────┘
     Left Panel (30%)              Right Panel (70%)
```

**Left Panel Features:**
- Search bar — user name দিয়ে conversation search
- New message button ✏️ — নতুন conversation start
- Conversation list — avatar, name, last message preview, time, unread indicator
- Active conversation highlighted
- Unread conversations bold text + blue dot

**Right Panel Features:**
- Header: User name + avatar + online status
- Message bubbles:
  - Sent messages → **right side, primary color background, white text**
  - Received messages → **left side, grey background, dark text**
- Timestamp per message
- Message input box + Send button
- File/Image attachment button (Phase 2)
- Auto-scroll to latest message

---

## View Files

| File                                                        | Purpose                         |
| ----------------------------------------------------------- | ------------------------------- |
| `resources/views/layouts/base/header.blade.php`             | **Modify** — inbox icon add     |
| `resources/views/layouts/partials/message-dropdown.blade.php`| **Create** — header dropdown    |
| `resources/views/messages/index.blade.php`                  | **Create** — full inbox page    |
| `resources/views/messages/partials/conversation-list.blade.php`| **Create** — left panel       |
| `resources/views/messages/partials/chat-window.blade.php`   | **Create** — right panel chat   |

---

## Key Features

### Unread Count Logic

```
unread count = messages.count WHERE
    message.conversation_id = participant.conversation_id
    AND message.sender_id != current_user_id
    AND message.created_at > participant.last_read_at (or last_read_at is NULL)
```

- Header badge → total unread across all conversations
- Per conversation → unread count for that specific conversation
- `markAsRead()` → updates `conversation_participants.last_read_at = now()`

### New Message Flow

```
1. User clicks ✏️ "New Message"
2. User search modal opens → type name → AJAX search users
3. Select user → check if private conversation already exists between these 2 users
   - YES → redirect to existing conversation
   - NO  → create new conversation + add both users as participants
4. Redirect to conversation chat window
```

> **Important:** দুইজন user এর মধ্যে সর্বদা **একটিই** conversation থাকবে। Duplicate conversation create হবে না।

### Message Send Flow

```
1. User types message → clicks Send (or Enter key)
2. POST /messages/{conversation} → { body: "message text" }
3. Server validates:
   - User is participant of this conversation
   - Body is not empty
4. Create message record
5. Update conversation.last_message_id
6. Return new message JSON
7. Frontend appends message bubble to chat
```

### Real-time Updates (AJAX Polling)

```
- Every 10-15 seconds:
  GET /messages/unread-count → update header badge
  
- When chat window is open:
  GET /messages/{conversation}?after={lastMessageId} → fetch new messages
  Append new messages to chat
  Auto-scroll to bottom
```

---

## Implementation Phases

### Phase 1: Database + Models
- [ ] Create `conversations` migration (with soft deletes)
- [ ] Create `conversation_participants` migration
- [ ] Create `messages` migration (with soft deletes)
- [ ] Run migrations
- [ ] Create `Conversation` model with SoftDeletes + relationships
- [ ] Create `ConversationParticipant` model
- [ ] Create `Message` model with SoftDeletes + relationships
- [ ] Add `conversations()` relationship to `User` model

### Phase 2: Controller + Routes
- [ ] Create `MessageController`
- [ ] Add routes in `web.php`
- [ ] Implement `index()` — conversation list
- [ ] Implement `show()` — load messages
- [ ] Implement `store()` — send message
- [ ] Implement `startConversation()` — duplicate check + new private conversation
- [ ] Implement `unreadCount()` — AJAX count
- [ ] Implement `markAsRead()` — mark read
- [ ] Implement `searchUsers()` — user search

### Phase 3: Header Icon + Dropdown
- [ ] Add inbox icon in `header.blade.php` (beside notification)
- [ ] Create `message-dropdown.blade.php` partial
- [ ] Style dropdown (recent conversations, unread indicators)
- [ ] AJAX unread count badge update
- [ ] "See All Messages" link

### Phase 4: Full Inbox Page UI
- [ ] Create `messages/index.blade.php` layout
- [ ] Left panel — conversation list with search
- [ ] Right panel — chat window
- [ ] Message bubbles styling (sent/received)
- [ ] Message input box + Send button
- [ ] Auto-scroll to latest message
- [ ] Load conversation on click (AJAX)

### Phase 5: Polish + Real-time
- [ ] AJAX polling for new messages (10-15 sec)
- [ ] Enter key to send message
- [ ] Empty state designs (no conversations, no messages)
- [ ] Mobile responsive layout

### Phase 6: File Attachments (Optional)
- [ ] Add `type` enum('text','image','file') column to messages
- [ ] Add `file_path` varchar column to messages
- [ ] Image/file upload in message input
- [ ] Image preview in chat
- [ ] File download link in chat

---

## Security Considerations

- **Authorization:** User can only access conversations they are a participant of — every request-এ participant check mandatory
- **Input Validation:** Message body sanitized via `e()` / Blade `{{ }}` (XSS prevention)
- **Rate Limiting:** Throttle middleware — max 60 messages per minute per user
- **CSRF:** All POST requests include CSRF token
- **Duplicate Prevention:** দুইজন user-এর মধ্যে একটিই conversation থাকবে
- **Soft Delete:** Data permanently delete হবে না, recover করা যাবে
- **File Upload (Phase 6):** Validate file types, max size limit, store in non-public path

---

## Tech Stack

| Component     | Technology                        |
| ------------- | --------------------------------- |
| Backend       | Laravel (existing)                |
| Database      | MySQL (existing)                  |
| Frontend      | Blade templates + jQuery/JS       |
| UI Framework  | Metronic (existing theme)         |
| Real-time     | AJAX Polling (10-15 sec interval) |
| File Storage  | Laravel Storage (Phase 6)         |

---

## Future Upgrades (Not in current scope)

| Feature | Description |
|---|---|
| **WebSocket** | Laravel Reverb / Pusher দিয়ে instant message delivery |
| **Group Chat** | `type` column add করে group conversation support |
| **Seen/Delivered** | Message read receipt indicator |
| **Typing Indicator** | "User is typing..." real-time status |
| **Message Search** | Full-text search on message body |
| **Message Reactions** | Emoji reactions on messages |
