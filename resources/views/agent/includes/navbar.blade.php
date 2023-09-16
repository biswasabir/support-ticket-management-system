<div class="nav-bar v2">
    <div class="dash-container">
        <div class="nav-bar-container">
            <div class="dash-sidebar-toggle me-3">
                <div class="nav-btn py-3">
                    <i class="fa fa-bars fa-lg"></i>
                </div>
            </div>
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset($settings->media->logo_light) }}" alt="{{ $settings->general->site_name }}" />
            </a>
            <div class="nav-bar-actions ms-auto">
                <div class="drop-down drop-down-lg me-2" data-dropdown data-dropdown-position="top">
                    <div class="drop-down-btn nav-btn noti-btn">
                        <i class="{{ $notifications['unread'] ? 'fa-solid' : 'far' }} fa-bell"></i>
                        @if ($notifications['unread'])
                            <div class="noti-counter">
                                {{ $notifications['unread'] > 9 ? '9+' : $notifications['unread'] }}
                            </div>
                        @endif
                    </div>
                    <div class="drop-down-menu">
                        <div class="noti">
                            <div class="noti-header">
                                <h6 class="mb-0">{{ lang('Notifications', 'notifications') }}
                                    ({{ $notifications['unread'] }})</h6>
                                @if ($notifications['unread'] > 0)
                                    <a href="{{ route('agent.notifications.read.all') }}"
                                        class="ms-auto action-confirm">{{ lang('Mark All as Read', 'notifications') }}</a>
                                @else
                                    <span
                                        class="ms-auto text-muted">{{ lang('Mark All as Read', 'notifications') }}</span>
                                @endif
                            </div>
                            <div class="noti-body" data-simplebar>
                                @if ($notifications['list']->count() > 0)
                                    <div class="d-flex flex-column">
                                        @foreach ($notifications['list'] as $notification)
                                            @if ($notification->link)
                                                <a href="{{ route('agent.notifications.view', $notification->id) }}"
                                                    class="noti-item {{ !$notification->status ? 'unread' : '' }}">
                                                    <div class="noti-item-img">
                                                        <img src="{{ $notification->image }}"
                                                            alt="{{ $notification->title }}">
                                                    </div>
                                                    <div class="noti-item-info">
                                                        <p class="noti-item-text mb-0">{{ $notification->title }}</p>
                                                        <span
                                                            class="noti-item-time">{{ $notification->created_at->diffforhumans() }}</span>
                                                    </div>
                                                </a>
                                            @else
                                                <div class="noti-item {{ !$notification->status ? 'unread' : '' }}">
                                                    <div class="noti-item-img">
                                                        <img src="{{ $notification->image }}"
                                                            alt="{{ $notification->title }}">
                                                    </div>
                                                    <div class="noti-item-info">
                                                        <p class="noti-item-text mb-0">{{ $notification->title }}</p>
                                                        <span
                                                            class="noti-item-time">{{ $notification->created_at->diffforhumans() }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5 w-100">
                                        <small
                                            class="text-muted mb-0">{{ lang('No notifications found', 'notifications') }}</small>
                                    </div>
                                @endif
                            </div>
                            <div class="noti-footer">
                                <a href="{{ route('agent.notifications.index') }}">
                                    {{ lang('View All', 'notifications') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @include('partials.user-menu', ['margin' => 'me-0'])
            </div>
        </div>
    </div>
</div>
