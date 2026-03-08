<div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg" id="message-dropdown">
    <div class="d-flex flex-column pt-8 pb-4 bgi-size-cover bgi-no-repeat rounded-top" style="background-image: url({{ asset('media/misc/bg-1.jpg') }})">
        <h4 class="d-flex flex-center">
            <span class="text-white">Messages</span>
            <span id="msg-dropdown-badge" class="btn btn-text btn-primary btn-sm font-weight-bold btn-font-md ml-2">{{ $msgUnreadCount }} new</span>
        </h4>
    </div>
    <div class="p-4">
        <div id="message-dropdown-list" class="scroll" data-scroll="true" data-height="300" data-mobile-height="200">
            <div class="text-center text-muted py-5">Loading...</div>
        </div>
        <div class="text-center pt-3 border-top mt-3">
            <a href="{{ route('messages.index') }}" class="font-weight-bold text-primary font-size-sm">See All Messages</a>
        </div>
    </div>
</div>

<style>
.msg-item {
    display: flex;
    align-items: center;
    padding: 8px 10px;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.2s;
    text-decoration: none;
    color: inherit;
}
.msg-item:hover {
    background: #f3f6f9;
    text-decoration: none;
    color: inherit;
}
.msg-item.msg-unread {
    background: #e1f0ff;
}
.msg-item .msg-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #3699FF;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 16px;
    flex-shrink: 0;
}
.msg-item .msg-avatar img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}
.msg-item .msg-info {
    flex: 1;
    min-width: 0;
    margin-left: 10px;
}
.msg-item .msg-name {
    font-weight: 600;
    font-size: 13px;
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.msg-item .msg-preview {
    font-size: 12px;
    color: #7e8299;
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.msg-item .msg-time {
    font-size: 11px;
    color: #b5b5c3;
    white-space: nowrap;
    margin-left: 8px;
}
.msg-unread-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #3699FF;
    margin-left: 6px;
    flex-shrink: 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load recent messages when message dropdown opens
    $('#messageDropdownWrapper').on('show.bs.dropdown', function() {
        loadRecentMessages();
    });

    function loadRecentMessages() {
        fetch("{{ route('messages.recent') }}", {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(r => r.json())
        .then(data => {
            var container = document.getElementById('message-dropdown-list');
            if (!data.length) {
                container.innerHTML = '<div class="text-center text-muted py-5">No messages yet</div>';
                return;
            }
            var html = '';
            data.forEach(function(conv) {
                if (!conv.other_user) return;
                var initial = conv.other_user.name.charAt(0).toUpperCase();
                var avatarHtml = conv.other_user.profile_pic
                    ? '<img src="' + conv.other_user.profile_pic + '" alt="">'
                    : initial;
                var unreadClass = conv.unread_count > 0 ? ' msg-unread' : '';
                var preview = conv.last_message ? conv.last_message.body : 'No messages yet';
                var time = conv.last_message ? conv.last_message.created_at : '';
                var dotHtml = conv.unread_count > 0 ? '<span class="msg-unread-dot"></span>' : '';

                html += '<a href="{{ url("messages") }}/' + conv.id + '" class="msg-item' + unreadClass + '">'
                    + '<div class="msg-avatar">' + avatarHtml + '</div>'
                    + '<div class="msg-info">'
                    + '<span class="msg-name">' + escapeHtml(conv.other_user.name) + '</span>'
                    + '<span class="msg-preview">' + escapeHtml(preview) + '</span>'
                    + '</div>'
                    + '<span class="msg-time">' + escapeHtml(time) + '</span>'
                    + dotHtml
                    + '</a>';
            });
            container.innerHTML = html;
        })
        .catch(function() {
            document.getElementById('message-dropdown-list').innerHTML = '<div class="text-center text-muted py-5">Failed to load</div>';
        });
    }

    function escapeHtml(str) {
        if (!str) return '';
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    // Poll unread count every 15 seconds
    setInterval(function() {
        fetch("{{ route('messages.unreadCount') }}", {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            var badge = document.getElementById('message-badge');
            var dropdownBadge = document.getElementById('msg-dropdown-badge');
            if (badge) badge.innerText = data.count > 0 ? data.count : '';
            if (dropdownBadge) dropdownBadge.innerText = data.count + ' new';
        })
        .catch(function() {});
    }, 15000);
});
</script>
