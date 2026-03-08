<div class="conv-panel">
    <div class="conv-panel-header">
        <h5>Chats</h5>
        <input type="text" id="convSearchInput" class="conv-search" placeholder="Search conversations...">
    </div>
    <div class="conv-list">
        @forelse($conversations as $conversation)
            @php
                $other = $conversation->otherUser(Auth::id());
                $lastMsg = $conversation->lastMessage;
                $unread = $conversation->unreadCountFor(Auth::id());
                $isActive = $activeConversation && $activeConversation->id === $conversation->id;
            @endphp
            @if($other)
                <a href="{{ route('messages.show', $conversation->id) }}"
                   class="conv-item {{ $isActive ? 'active' : '' }}"
                   data-id="{{ $conversation->id }}">
                    <div class="conv-avatar">
                        @if($other->profile_pic)
                            <img src="{{ $other->profile_pic }}" alt="">
                        @else
                            {{ strtoupper(substr($other->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="conv-details">
                        <span class="conv-name">{{ $other->name }}</span>
                        <span class="conv-preview">
                            @if($lastMsg)
                                @if($lastMsg->sender_id == Auth::id()) You: @endif
                                {{ Str::limit($lastMsg->body, 30) }}
                            @else
                                No messages yet
                            @endif
                        </span>
                    </div>
                    <div class="conv-meta">
                        @if($lastMsg)
                            <span class="conv-time">{{ $lastMsg->created_at->diffForHumans(null, true, true) }}</span>
                        @endif
                        @if($unread > 0)
                            <span class="conv-unread-dot"></span>
                        @endif
                    </div>
                </a>
            @endif
        @empty
            <div class="text-center text-muted py-10">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#b5b5c3" stroke-width="1.5">
                        <path d="M21 15C21 15.5304 20.7893 16.0391 20.4142 16.4142C20.0391 16.7893 19.5304 17 19 17H7L3 21V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H19C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V15Z"/>
                    </svg>
                </div>
                <p>No conversations yet</p>
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#newConversationModal">Start a conversation</button>
            </div>
        @endforelse
    </div>
</div>
