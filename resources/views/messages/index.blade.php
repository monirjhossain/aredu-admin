@extends('layouts.default')

@section('styles')
<style>
    .messenger-container {
        display: flex;
        height: calc(100vh - 180px);
        min-height: 500px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 30px 0 rgba(82, 63, 105, 0.05);
        overflow: hidden;
    }

    /* ── Left Panel: Conversation List ── */
    .conv-panel {
        width: 320px;
        min-width: 320px;
        border-right: 1px solid #ebedf3;
        display: flex;
        flex-direction: column;
        background: #f8f9fc;
    }
    .conv-panel-header {
        padding: 16px 16px 12px;
        border-bottom: 1px solid #ebedf3;
        background: #fff;
    }
    .conv-panel-header h5 {
        margin: 0 0 10px;
        font-weight: 700;
        font-size: 18px;
    }
    .conv-search {
        width: 100%;
        border: 1px solid #e4e6ef;
        border-radius: 20px;
        padding: 8px 16px;
        font-size: 13px;
        outline: none;
        background: #f3f6f9;
    }
    .conv-search:focus {
        border-color: #3699FF;
        background: #fff;
    }
    .conv-list {
        flex: 1;
        overflow-y: auto;
        padding: 8px;
    }
    .conv-item {
        display: flex;
        align-items: center;
        padding: 10px 12px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.15s;
        text-decoration: none;
        color: inherit;
        margin-bottom: 2px;
    }
    .conv-item:hover {
        background: #e1f0ff;
        text-decoration: none;
        color: inherit;
    }
    .conv-item.active {
        background: #3699FF;
        color: #fff;
    }
    .conv-item.active .conv-preview,
    .conv-item.active .conv-time {
        color: rgba(255,255,255,0.8) !important;
    }
    .conv-item.active .conv-unread-dot {
        background: #fff;
    }
    .conv-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #3699FF;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 18px;
        flex-shrink: 0;
    }
    .conv-item.active .conv-avatar {
        background: rgba(255,255,255,0.2);
    }
    .conv-avatar img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
    }
    .conv-details {
        flex: 1;
        min-width: 0;
        margin-left: 12px;
    }
    .conv-name {
        font-weight: 600;
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
    }
    .conv-preview {
        font-size: 12px;
        color: #7e8299;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
        margin-top: 2px;
    }
    .conv-meta {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        margin-left: 8px;
        flex-shrink: 0;
    }
    .conv-time {
        font-size: 11px;
        color: #b5b5c3;
        white-space: nowrap;
    }
    .conv-unread-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #3699FF;
        margin-top: 4px;
    }

    /* ── Right Panel: Chat Window ── */
    .chat-panel {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #fff;
    }
    .chat-header {
        padding: 14px 20px;
        border-bottom: 1px solid #ebedf3;
        display: flex;
        align-items: center;
        background: #fff;
    }
    .chat-header .conv-avatar {
        width: 38px;
        height: 38px;
        font-size: 16px;
    }
    .chat-header .conv-avatar img {
        width: 38px;
        height: 38px;
    }
    .chat-header-name {
        font-weight: 600;
        font-size: 15px;
        margin-left: 12px;
    }
    .chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
    }
    .chat-empty {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #b5b5c3;
        font-size: 15px;
    }
    .msg-bubble {
        max-width: 65%;
        padding: 10px 14px;
        border-radius: 16px;
        margin-bottom: 6px;
        font-size: 14px;
        line-height: 1.5;
        word-wrap: break-word;
        position: relative;
    }
    .msg-bubble.sent {
        background: #3699FF;
        color: #fff;
        align-self: flex-end;
        border-bottom-right-radius: 4px;
    }
    .msg-bubble.received {
        background: #f3f6f9;
        color: #3f4254;
        align-self: flex-start;
        border-bottom-left-radius: 4px;
    }
    .msg-bubble .msg-bubble-time {
        font-size: 10px;
        opacity: 0.7;
        margin-top: 4px;
        display: block;
    }
    .msg-bubble.sent .msg-bubble-time {
        text-align: right;
        color: rgba(255,255,255,0.7);
    }
    .msg-bubble.received .msg-bubble-time {
        color: #b5b5c3;
    }
    .msg-date-divider {
        text-align: center;
        color: #b5b5c3;
        font-size: 12px;
        margin: 16px 0;
        position: relative;
    }
    .msg-date-divider::before,
    .msg-date-divider::after {
        content: '';
        position: absolute;
        top: 50%;
        width: 30%;
        height: 1px;
        background: #ebedf3;
    }
    .msg-date-divider::before { left: 5%; }
    .msg-date-divider::after { right: 5%; }

    /* ── Chat Input ── */
    .chat-input-area {
        padding: 12px 20px;
        border-top: 1px solid #ebedf3;
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fff;
    }
    .chat-input {
        flex: 1;
        border: 1px solid #e4e6ef;
        border-radius: 20px;
        padding: 10px 18px;
        font-size: 14px;
        outline: none;
        resize: none;
        max-height: 100px;
        line-height: 1.4;
    }
    .chat-input:focus {
        border-color: #3699FF;
    }
    .chat-send-btn {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #3699FF;
        border: none;
        color: #fff;
        font-size: 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
        flex-shrink: 0;
    }
    .chat-send-btn:hover {
        background: #187DE4;
    }
    .chat-send-btn:disabled {
        background: #d1d3e0;
        cursor: not-allowed;
    }

    /* ── New Conversation Modal ── */
    .user-search-item {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.15s;
    }
    .user-search-item:hover {
        background: #e1f0ff;
    }
    .user-search-item .conv-avatar {
        width: 36px;
        height: 36px;
        font-size: 14px;
    }
    .user-search-item .conv-avatar img {
        width: 36px;
        height: 36px;
    }

    /* ── Responsive ── */
    @media (max-width: 768px) {
        .conv-panel {
            width: 100%;
            min-width: 100%;
        }
        .chat-panel {
            display: none;
        }
        .messenger-container.chat-open .conv-panel {
            display: none;
        }
        .messenger-container.chat-open .chat-panel {
            display: flex;
        }
        .chat-back-btn {
            display: inline-flex !important;
        }
    }
</style>
@endsection

@section('content')
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <h3 class="card-label font-weight-bolder text-dark">Messages</h3>
        </div>
        <div class="card-toolbar">
            <button class="btn btn-primary btn-sm font-weight-bold" data-toggle="modal" data-target="#newConversationModal">
                <i class="flaticon2-pen"></i> New Message
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="messenger-container" id="messengerContainer">
            {{-- Left Panel --}}
            @include('messages.partials.conversation-list')

            {{-- Right Panel --}}
            @include('messages.partials.chat-window')
        </div>
    </div>
</div>

{{-- New Conversation Modal --}}
<div class="modal fade" id="newConversationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Message</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="text" id="userSearchInput" class="form-control" placeholder="Search user by name or email..." autocomplete="off">
                <div id="userSearchResults" class="mt-3" style="max-height: 250px; overflow-y: auto;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    var activeConversationId = {{ $activeConversation ? $activeConversation->id : 'null' }};
    var authUserId = {{ Auth::id() }};
    var pollTimer = null;
    var lastMessageId = 0;

    // ── Load messages for active conversation ──
    if (activeConversationId) {
        loadMessages(activeConversationId);
        startPolling();
    }

    // ── Click conversation item ──
    $(document).on('click', '.conv-item', function(e) {
        e.preventDefault();
        var convId = $(this).data('id');
        if (convId === activeConversationId) return;

        activeConversationId = convId;
        $('.conv-item').removeClass('active');
        $(this).addClass('active');
        $(this).find('.conv-unread-dot').remove();

        // Mobile: show chat panel
        $('#messengerContainer').addClass('chat-open');

        loadMessages(convId);
        markAsRead(convId);
        startPolling();

        // Update URL without reload
        window.history.pushState({}, '', '{{ url("messages") }}/' + convId);
    });

    // ── Back button (mobile) ──
    $(document).on('click', '.chat-back-btn', function() {
        $('#messengerContainer').removeClass('chat-open');
        activeConversationId = null;
        stopPolling();
    });

    // ── Send message ──
    $(document).on('click', '#sendMessageBtn', function() {
        sendMessage();
    });

    $(document).on('keydown', '#chatInput', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    function sendMessage() {
        var input = $('#chatInput');
        var body = input.val().trim();
        if (!body || !activeConversationId) return;

        $('#sendMessageBtn').prop('disabled', true);
        input.val('');

        $.ajax({
            url: '{{ url("messages") }}/' + activeConversationId,
            method: 'POST',
            data: { body: body },
            success: function(data) {
                appendMessage(data.message);
                scrollToBottom();
                lastMessageId = data.message.id;
                updateConversationPreview(activeConversationId, body);
                $('#sendMessageBtn').prop('disabled', false);
                input.focus();
            },
            error: function() {
                toastr.error('Failed to send message');
                input.val(body);
                $('#sendMessageBtn').prop('disabled', false);
            }
        });
    }

    function loadMessages(convId) {
        $('#chatMessages').html('<div class="chat-empty"><div class="spinner-border text-primary" role="status"></div></div>');

        $.get('{{ url("messages") }}/' + convId, function(data) {
            var chatHeader = $('#chatHeader');
            var chatMessages = $('#chatMessages');
            var chatInputArea = $('#chatInputArea');

            if (data.otherUser) {
                var initial = data.otherUser.name.charAt(0).toUpperCase();
                var avatarHtml = data.otherUser.profile_pic
                    ? '<img src="' + data.otherUser.profile_pic + '" alt="">'
                    : initial;

                chatHeader.html(
                    '<button class="btn btn-sm btn-icon mr-2 chat-back-btn d-none d-md-none"><i class="flaticon2-back"></i></button>' +
                    '<div class="conv-avatar">' + avatarHtml + '</div>' +
                    '<span class="chat-header-name">' + escapeHtml(data.otherUser.name) + '</span>'
                );
                chatHeader.show();
                chatInputArea.show();
            }

            chatMessages.html('');
            if (data.messages && data.messages.length) {
                var lastDate = '';
                data.messages.forEach(function(msg) {
                    var msgDate = new Date(msg.created_at).toLocaleDateString();
                    if (msgDate !== lastDate) {
                        chatMessages.append('<div class="msg-date-divider">' + msgDate + '</div>');
                        lastDate = msgDate;
                    }
                    appendMessage(msg);
                });
                lastMessageId = data.messages[data.messages.length - 1].id;
            } else {
                chatMessages.html('<div class="chat-empty">No messages yet. Start the conversation!</div>');
                lastMessageId = 0;
            }
            scrollToBottom();
        });
    }

    function appendMessage(msg) {
        var isSent = msg.sender_id == authUserId;
        var cls = isSent ? 'sent' : 'received';
        var time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

        var html = '<div class="msg-bubble ' + cls + '" data-id="' + msg.id + '">'
            + escapeHtml(msg.body)
            + '<span class="msg-bubble-time">' + time + '</span>'
            + '</div>';

        var chatMessages = $('#chatMessages');
        // Remove empty state if present
        chatMessages.find('.chat-empty').remove();
        chatMessages.append(html);
    }

    function scrollToBottom() {
        var el = document.getElementById('chatMessages');
        if (el) el.scrollTop = el.scrollHeight;
    }

    function markAsRead(convId) {
        $.post('{{ url("messages") }}/' + convId + '/read');
    }

    function updateConversationPreview(convId, text) {
        var item = $('.conv-item[data-id="' + convId + '"]');
        item.find('.conv-preview').text(text);
        item.find('.conv-time').text('Just now');
        // Move to top
        item.parent().prepend(item);
    }

    // ── Polling ──
    function startPolling() {
        stopPolling();
        pollTimer = setInterval(function() {
            if (!activeConversationId || !lastMessageId) return;
            $.get('{{ url("messages") }}/' + activeConversationId + '/poll', { after: lastMessageId }, function(data) {
                if (data.messages && data.messages.length) {
                    data.messages.forEach(function(msg) {
                        appendMessage(msg);
                    });
                    lastMessageId = data.messages[data.messages.length - 1].id;
                    scrollToBottom();
                    markAsRead(activeConversationId);
                }
            });
        }, 10000);
    }

    function stopPolling() {
        if (pollTimer) {
            clearInterval(pollTimer);
            pollTimer = null;
        }
    }

    // ── User Search (New Conversation) ──
    var searchTimeout;
    $('#userSearchInput').on('input', function() {
        var q = $(this).val().trim();
        clearTimeout(searchTimeout);
        if (q.length < 1) {
            $('#userSearchResults').html('');
            return;
        }
        searchTimeout = setTimeout(function() {
            $.get('{{ route("messages.searchUsers") }}', { q: q }, function(users) {
                var html = '';
                if (!users.length) {
                    html = '<div class="text-muted text-center py-3">No users found</div>';
                } else {
                    users.forEach(function(user) {
                        var initial = user.name.charAt(0).toUpperCase();
                        var avatarHtml = user.profile_pic
                            ? '<img src="' + user.profile_pic + '" alt="">'
                            : initial;

                        html += '<div class="user-search-item" data-user-id="' + user.id + '">'
                            + '<div class="conv-avatar">' + avatarHtml + '</div>'
                            + '<div class="ml-3">'
                            + '<div class="font-weight-bold">' + escapeHtml(user.name) + '</div>'
                            + '<div class="text-muted font-size-sm">' + escapeHtml(user.email) + '</div>'
                            + '</div>'
                            + '</div>';
                    });
                }
                $('#userSearchResults').html(html);
            });
        }, 300);
    });

    $(document).on('click', '.user-search-item', function() {
        var userId = $(this).data('user-id');
        $.ajax({
            url: '{{ route("messages.start") }}',
            method: 'POST',
            data: { user_id: userId },
            success: function(data) {
                $('#newConversationModal').modal('hide');
                window.location.href = data.redirect;
            },
            error: function(xhr) {
                var msg = xhr.responseJSON ? xhr.responseJSON.error : 'Something went wrong';
                toastr.error(msg);
            }
        });
    });

    // Clear search on modal close
    $('#newConversationModal').on('hidden.bs.modal', function() {
        $('#userSearchInput').val('');
        $('#userSearchResults').html('');
    });

    // ── Conversation Search ──
    $('#convSearchInput').on('input', function() {
        var q = $(this).val().toLowerCase();
        $('.conv-item').each(function() {
            var name = $(this).find('.conv-name').text().toLowerCase();
            $(this).toggle(name.indexOf(q) !== -1);
        });
    });

    function escapeHtml(str) {
        if (!str) return '';
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }
});
</script>
@endsection
