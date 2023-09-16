<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <?php $segments = ''; ?>
        @foreach (request()->segments() as $segment)
            <?php $segments .= '/' . $segment; ?>
            <li class="breadcrumb-item capitalize @if (request()->segment(count(request()->segments())) == $segment) active @endif">
                @if (request()->segment(count(request()->segments())) != $segment)
                    <a href="{{ url($segments) }}">{{ ucfirst($segment) }}</a>
                @else
                    {{ ucfirst($segment) }}
                @endif
            </li>
        @endforeach
    </ol>
</nav>
