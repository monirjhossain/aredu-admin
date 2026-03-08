<?php

namespace App\Http\Controllers;

use App\Models\Messaging\Conversation;
use App\Models\Messaging\ConversationParticipant;
use App\Models\Messaging\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Inbox page — conversation list with optional active conversation.
     */
    public function index(Request $request)
    {
        $conversations = $this->getConversationsForUser(Auth::id());
        $activeConversation = null;
        $messages = collect();

        // If a conversation is selected via query param
        if ($request->has('conversation')) {
            $activeConversation = Conversation::find($request->conversation);
            if ($activeConversation && $this->isParticipant($activeConversation, Auth::id())) {
                $messages = $activeConversation->messages()
                    ->with('sender')
                    ->orderBy('created_at', 'asc')
                    ->paginate(50);

                // Mark as read
                $this->markConversationAsRead($activeConversation, Auth::id());
            } else {
                $activeConversation = null;
            }
        }

        return view('messages.index', compact('conversations', 'activeConversation', 'messages'));
    }

    /**
     * Load conversation messages (AJAX).
     */
    public function show(Conversation $conversation)
    {
        if (!$this->isParticipant($conversation, Auth::id())) {
            abort(403);
        }

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        $this->markConversationAsRead($conversation, Auth::id());

        $otherUser = $conversation->otherUser(Auth::id());

        if (request()->ajax()) {
            return response()->json([
                'messages' => $messages->items(),
                'otherUser' => $otherUser,
                'hasMore' => $messages->hasMorePages(),
            ]);
        }

        $conversations = $this->getConversationsForUser(Auth::id());
        $activeConversation = $conversation;

        return view('messages.index', compact('conversations', 'activeConversation', 'messages'));
    }

    /**
     * Send a new message in an existing conversation.
     */
    public function store(Request $request, Conversation $conversation)
    {
        if (!$this->isParticipant($conversation, Auth::id())) {
            abort(403);
        }

        $request->validate([
            'body' => 'required|string|max:5000',
        ]);

        $message = DB::transaction(function () use ($request, $conversation) {
            $message = $conversation->messages()->create([
                'sender_id' => Auth::id(),
                'body' => $request->body,
            ]);

            $conversation->update(['last_message_id' => $message->id]);

            return $message;
        });

        $message->load('sender');

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Start a new conversation with a user (or return existing one).
     */
    public function startConversation(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $otherUserId = $request->user_id;
        $authId = Auth::id();

        // Can't message yourself
        if ($otherUserId == $authId) {
            return response()->json(['error' => 'You cannot message yourself.'], 422);
        }

        // Check if conversation already exists between these two users
        $existing = Conversation::findBetween($authId, $otherUserId);

        if ($existing) {
            return response()->json([
                'conversation_id' => $existing->id,
                'redirect' => route('messages.show', $existing->id),
            ]);
        }

        // Create new conversation
        $conversation = DB::transaction(function () use ($authId, $otherUserId) {
            $conversation = Conversation::create([
                'created_by' => $authId,
            ]);

            $conversation->participants()->attach([$authId, $otherUserId]);

            return $conversation;
        });

        return response()->json([
            'conversation_id' => $conversation->id,
            'redirect' => route('messages.show', $conversation->id),
        ]);
    }

    /**
     * Get total unread message count for header badge (AJAX).
     */
    public function unreadCount()
    {
        $userId = Auth::id();

        $count = ConversationParticipant::where('user_id', $userId)
            ->get()
            ->sum(function ($participant) use ($userId) {
                $query = Message::where('conversation_id', $participant->conversation_id)
                    ->where('sender_id', '!=', $userId);

                if ($participant->last_read_at) {
                    $query->where('created_at', '>', $participant->last_read_at);
                }

                return $query->count();
            });

        return response()->json(['count' => $count]);
    }

    /**
     * Mark a conversation as read (AJAX).
     */
    public function markAsRead(Conversation $conversation)
    {
        if (!$this->isParticipant($conversation, Auth::id())) {
            abort(403);
        }

        $this->markConversationAsRead($conversation, Auth::id());

        return response()->json(['success' => true]);
    }

    /**
     * Search users for starting a new conversation (AJAX).
     */
    public function searchUsers(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:1|max:100',
        ]);

        $users = User::where('id', '!=', Auth::id())
            ->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('email', 'like', '%' . $request->q . '%');
            })
            ->select('id', 'name', 'email', 'profile_pic')
            ->limit(10)
            ->get();

        return response()->json($users);
    }

    /**
     * Get new messages after a specific message ID (AJAX polling).
     */
    public function poll(Conversation $conversation, Request $request)
    {
        if (!$this->isParticipant($conversation, Auth::id())) {
            abort(403);
        }

        $request->validate([
            'after' => 'required|integer',
        ]);

        $messages = $conversation->messages()
            ->with('sender')
            ->where('id', '>', $request->after)
            ->orderBy('created_at', 'asc')
            ->get();

        if ($messages->isNotEmpty()) {
            $this->markConversationAsRead($conversation, Auth::id());
        }

        return response()->json(['messages' => $messages]);
    }

    /**
     * Get recent conversations for header dropdown (AJAX).
     */
    public function recent()
    {
        $conversations = $this->getConversationsForUser(Auth::id(), 5);

        $data = $conversations->map(function ($conversation) {
            $otherUser = $conversation->otherUser(Auth::id());
            return [
                'id' => $conversation->id,
                'other_user' => $otherUser ? [
                    'id' => $otherUser->id,
                    'name' => $otherUser->name,
                    'profile_pic' => $otherUser->profile_pic,
                ] : null,
                'last_message' => $conversation->lastMessage ? [
                    'body' => \Illuminate\Support\Str::limit($conversation->lastMessage->body, 40),
                    'created_at' => $conversation->lastMessage->created_at->diffForHumans(),
                    'is_mine' => $conversation->lastMessage->sender_id == Auth::id(),
                ] : null,
                'unread_count' => $conversation->unreadCountFor(Auth::id()),
            ];
        });

        return response()->json($data);
    }

    // ── Private Helpers ───────────────────────────────────────

    private function isParticipant(Conversation $conversation, $userId): bool
    {
        return $conversation->participants()->where('users.id', $userId)->exists();
    }

    private function markConversationAsRead(Conversation $conversation, $userId): void
    {
        ConversationParticipant::where('conversation_id', $conversation->id)
            ->where('user_id', $userId)
            ->update(['last_read_at' => now()]);
    }

    private function getConversationsForUser($userId, $limit = null)
    {
        $query = Conversation::whereHas('participants', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        })
            ->with(['lastMessage.sender', 'participants'])
            ->orderByDesc(
                Message::select('created_at')
                    ->whereColumn('conversation_id', 'conversations.id')
                    ->latest()
                    ->limit(1)
            );

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }
}
