<div class="chat-panel">
    @if($activeConversation)
        @php
            $chatUser = $activeConversation->otherUser(Auth::id());
        @endphp
        <div class="chat-header" id="chatHeader">
            <button class="btn btn-sm btn-icon mr-2 chat-back-btn d-none d-md-none">
                <i class="flaticon2-back"></i>
            </button>
            <div class="conv-avatar">
                @if($chatUser && $chatUser->profile_pic)
                    <img src="{{ $chatUser->profile_pic }}" alt="">
                @else
                    {{ $chatUser ? strtoupper(substr($chatUser->name, 0, 1)) : '?' }}
                @endif
            </div>
            <span class="chat-header-name">{{ $chatUser ? $chatUser->name : 'Unknown' }}</span>
        </div>

        <div class="chat-messages" id="chatMessages">
            {{-- Messages loaded via JS --}}
        </div>

        <div class="chat-input-area" id="chatInputArea">
            <textarea id="chatInput" class="chat-input" placeholder="Type a message..." rows="1"></textarea>
            <button id="sendMessageBtn" class="chat-send-btn" title="Send">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </button>
        </div>
    @else
        <div class="chat-empty" id="chatHeader" style="display:none;"></div>
        <div class="chat-messages chat-empty" id="chatMessages">
            <div style="text-align:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#d1d3e0" stroke-width="1.5">
                    <path d="M21 15C21 15.5304 20.7893 16.0391 20.4142 16.4142C20.0391 16.7893 19.5304 17 19 17H7L3 21V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H19C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V15Z"/>
                </svg>
                <p class="mt-4" style="color:#b5b5c3; font-size:15px;">Select a conversation or start a new one</p>
            </div>
        </div>
        <div class="chat-input-area" id="chatInputArea" style="display:none;">
            <textarea id="chatInput" class="chat-input" placeholder="Type a message..." rows="1"></textarea>
            <button id="sendMessageBtn" class="chat-send-btn" title="Send">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </button>
        </div>
    @endif
</div>
