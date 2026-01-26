@props(['field', 'timeout' => 2000])

<span
    x-cloak
    x-data="{
        show: false,
        trigger() {
            this.show = true
            setTimeout(() => this.show = false, {{ $timeout }})
        }
    }"
    x-show="show"
    x-on:{{ $field }}.window="trigger()"
    x-transition.duration.500ms
    class="text-green-600 text-sm"
>
    updated
</span>
