<div id="kt_header" class="header header-fixed">
    <div class="container-fluid d-flex align-items-stretch justify-content-between">
        <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
        </div>
        <div class="topbar">
            <div class="topbar-item">
            </div>
            {{-- Message Inbox Icon --}}
            <div class="topbar-item dropdown d-flex align-items-center" id="messageDropdownWrapper">
                @php
                    $msgUnreadCount = \App\Models\Messaging\ConversationParticipant::where('user_id', Auth::id())
                        ->get()
                        ->sum(function ($p) {
                            $q = \App\Models\Messaging\Message::where('conversation_id', $p->conversation_id)
                                ->where('sender_id', '!=', Auth::id());
                            if ($p->last_read_at) $q->where('created_at', '>', $p->last_read_at);
                            return $q->count();
                        });
                @endphp
                <div class="btn btn-icon btn-clean btn-dropdown btn-lg mr-1 position-relative" data-toggle="dropdown" data-offset="10px,0px" aria-haspopup="true" aria-expanded="false">
                    <span class="svg-icon svg-icon-xl svg-icon-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path d="M21 15C21 15.5304 20.7893 16.0391 20.4142 16.4142C20.0391 16.7893 19.5304 17 19 17H7L3 21V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H19C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V15Z" stroke="#3699FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span id="message-badge" class="badge badge-primary position-absolute" style="top:0; right:0; font-size:10px;">{{ $msgUnreadCount > 0 ? $msgUnreadCount : '' }}</span>
                </div>
                @include('layouts.partials.message-dropdown')
            </div>
            {{-- Notification Icon --}}
            <div class="topbar-item dropdown d-flex align-items-center" id="notificationDropdownWrapper">
                @php
                    $unreadCount = Auth::user()->unreadNotifications->count();
                @endphp
                <div class="btn btn-icon btn-clean btn-dropdown btn-lg mr-1 pulse pulse-primary position-relative" data-toggle="dropdown" data-offset="10px,0px" aria-haspopup="true" aria-expanded="false">
                    <span class="svg-icon svg-icon-xl svg-icon-primary">
                        <!--begin::Svg Icon | path:media/svg/icons/Code/Compiling.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3" />
                                <path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000" />
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>
                    <span class="pulse-ring"></span>
                    <span id="notification-badge" class="badge badge-danger position-absolute" style="top:0; right:0;">{{ $unreadCount > 0 ? $unreadCount : '' }}</span>
                </div>
                @include('layouts.partials.notifications')

            </div>
            <div class="topbar-item">
                <div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
                    <span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Hi,</span>
                    <span class="font-weight-bolder font-size-base font-size-h4 d-none d-md-inline mr-3 text-dark-100">{{ Auth::user()->name }}</span>
                    <span class="label label-lg label-success label-pill label-inline">{{ Auth::user()->role->user_type ?? 'Admin' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
