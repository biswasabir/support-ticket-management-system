<script>
    "use strict";
    const config = {!! json_encode([
        'lang' => app()->getLocale(),
        'url' => url('/'),
        'colors' => $settings->colors,
        'translates' => [
            'copied' => lang('Copied to clipboard'),
            'actionConfirm' => lang('Are you sure?'),
        ],
    ]) !!};
</script>
