@if (extension('google_analytics')->status)
    <script async
        src="https://www.googletagmanager.com/gtag/js?id={{ extension('google_analytics')->credentials->measurement_id }}">
    </script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag("js", new Date());
        gtag("config", "{{ extension('google_analytics')->credentials->measurement_id }}");
    </script>
@endif
@if (extension('tawk_to')->status)
    <script type='text/javascript'>
        var Tawk_API = Tawk_API || {},
            Tawk_LoadStart = new Date();
        (function() {
            var s1 = document.createElement('script'),
                s0 = document.getElementsByTagName('script')[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/{{ extension('tawk_to')->credentials->api_key }}';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
@endif
