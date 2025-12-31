@props(['href', 'active' => false])

<a href="{{ $href }}" @click="window.innerWidth < 768 && (open = false)" {{ $attributes->merge(['class' => ($active ? 'bg-white text-black rounded' : '')]) }}>
	{{ $slot }}
</a>