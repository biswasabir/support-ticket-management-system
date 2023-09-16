<div class="drop-down user-menu ms-3 {{ isset($margin) ? $margin : '' }}" data-dropdown data-dropdown-position="top">
    <div class="drop-down-btn">
        <img src="{{ auth()->user()->getAvatar() }}" alt="{{ auth()->user()->getName() }}" class="user-img">
        <span class="user-name">{{ auth()->user()->getName() }}</span>
        <i class="fa fa-angle-down ms-2"></i>
    </div>
    <div class="drop-down-menu">
        @if (auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="drop-down-item">
                <i class="fa-solid fa-table-columns"></i>
                {{ admin_lang('Admin Panel') }}
            </a>
        @elseif(auth()->user()->isAgent())
            <a href="{{ route('agent.tickets.index') }}" class="drop-down-item">
                <i class="fa-solid fa-inbox"></i>
                {{ lang('Tickets', 'tickets') }}
            </a>
            <a href="{{ route('agent.settings.index') }}" class="drop-down-item">
                <i class="fa fa-cog"></i>
                {{ lang('Settings', 'settings') }}
            </a>
        @else
            <a href="{{ route('user.tickets.index') }}" class="drop-down-item">
                <i class="fa-solid fa-inbox"></i>
                {{ lang('Tickets', 'tickets') }}
            </a>
            <a href="{{ route('user.settings.index') }}" class="drop-down-item">
                <i class="fa fa-cog"></i>
                {{ lang('Settings', 'settings') }}
            </a>
        @endif
        <a href="#" class="drop-down-item text-danger"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa fa-power-off"></i>
            {{ lang('Logout', 'auth') }}
        </a>
    </div>
</div>
<form id="logout-form" class="d-inline" action="{{ route('logout') }}" method="POST">
    @csrf
</form>
