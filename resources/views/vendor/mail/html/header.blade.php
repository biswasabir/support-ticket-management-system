@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{ asset($settings->media->logo_dark) }}" class="logo" alt="{{ $settings->general->site_name }}">
</a>
</td>
</tr>
