<footer class="footer-sm">
    <div class="{{ $container ?? 'container' }}">
        <div class="row justify-content-between g-3">
            <div class="col-auto">
                <div class="footer-copyright">
                    <p class="mb-0">&copy; <span data-year></span>
                        {{ $settings->general->site_name }} - {{ lang('All rights reserved') }}.</p>
                </div>
            </div>
            @if ($footerMenuLinks->count() > 0)
                <div class="col-auto">
                    <div class="footer-links">
                        @foreach ($footerMenuLinks as $footerMenuLink)
                            <div class="link">
                                <a href="{{ $footerMenuLink->link }}">{{ $footerMenuLink->name }}</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</footer>
