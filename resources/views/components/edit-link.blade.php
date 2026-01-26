@props([
    'href' => '',
    'title' => ''
])

<a href="{{ $href }}"
    {{ $attributes->class([
        'z-9 cursor-pointer inline-flex items-center justify-center', 
        'w-10 h-10 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200',
        'dark:hover:bg-gray-700 rounded-full transition-colors group'    
    ]) }}
    title="{{ $title }}"
>
    <svg class="w-5 h-5 text-gray-600 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors"
        fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
    </svg>
</a>
