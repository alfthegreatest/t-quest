@props(['field'])

<span x-data="{ show: false }" x-show="show" x-on:{{ $field }}.window="show = true; setTimeout(() => show = false, 2000)" x-transition.duration.500ms
    class="text-green-600 text-sm">
    updated
</span>