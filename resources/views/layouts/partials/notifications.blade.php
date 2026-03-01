@php
    $unreadNotifications = Auth::user()->unreadNotifications->take(10);
    $readNotifications = Auth::user()->readNotifications->take(10);
    $unreadCount = $unreadNotifications->count();
@endphp

<script>
function readNotification(id, el) {
    fetch("{{ url('notifications') }}/" + id + "/ajax-read", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        }
    })
    .then(response => response.json())
    .then(data => {
        var badge = document.getElementById('notification-badge');
        if (badge) {
            badge.innerText = data.unreadCount > 0 ? data.unreadCount : '';
        }
        if (el) {
            el.closest('.notification-item').classList.remove('notification-unread');
            el.closest('.notification-item').classList.add('notification-read');
        }
        window.location.href = data.url;
    });
}
</script>

<style>
.notification-read {
    background-color: #f6f6f6 !important;
    border-radius: 4px;
}
.notification-unread {
    background-color: #fffbe6 !important;
    border-radius: 4px;
    font-weight: 500;
}
.notification-item {
    padding: 10px 12px;
    margin-bottom: 6px;
    transition: background 0.2s;
}
.notification-item .svg-icon {
    opacity: 0.7;
}
</style>

<div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
    <form>
        <div class="d-flex flex-column pt-12 bgi-size-cover bgi-no-repeat rounded-top" style="background-image: url({{ asset('media/misc/bg-1.jpg') }})">
            <h4 class="d-flex flex-center rounded-top">
                <span class="text-white">User Notifications</span>
                <span class="btn btn-text btn-success btn-sm font-weight-bold btn-font-md ml-2">{{ $unreadCount }} new</span>
            </h4>
        </div>
        <div class="tab-content">
            <div class="tab-pane active show p-8" id="topbar_notifications_notifications" role="tabpanel">
                <div class="scroll pr-7 mr-n7" data-scroll="true" data-height="300" data-mobile-height="200">
                    {{-- Unread notifications first --}}
                    @forelse($unreadNotifications as $notification)
                        <div class="d-flex align-items-center notification-item notification-unread">
                            <div class="d-flex flex-column font-weight-bold flex-grow-1">
                                <a href="javascript:void(0);" onclick="readNotification('{{ $notification->id }}', this)" class="text-dark text-hover-primary mb-1 font-size-md" style="text-decoration:none;">
                                    {{ $notification->data['message'] ?? 'Notification' }}
                                </a>
                                <span class="text-muted small">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted">No new notifications</div>
                    @endforelse
                    {{-- Read notifications below --}}
                    @foreach($readNotifications as $notification)
                        <div class="d-flex align-items-center notification-item notification-read">
                            <div class="d-flex flex-column font-weight-bold flex-grow-1">
                                <span class="text-dark mb-1 font-size-md" style="text-decoration:none;">
                                    {{ $notification->data['message'] ?? 'Notification' }}
                                </span>
                                <span class="text-muted small">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </form>
</div>
