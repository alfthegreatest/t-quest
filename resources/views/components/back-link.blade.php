@props(['href', 'text'])

<a href="{{ $href }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition mb-4">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
    </svg>
    {{ $text }}
</a>
