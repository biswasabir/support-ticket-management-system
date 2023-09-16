<x-mail::message>
{!! str_replace(array_keys($short_codes), array_values($short_codes), $body) !!}
</x-mail::message>
