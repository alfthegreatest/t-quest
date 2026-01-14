@props(['href', 'active' => false])

<a 
	href="{{ $href }}" 
	@click="window.innerWidth < 768 && (open = false)" 
	{{ $attributes->class([
		'bg-white text-black rounded' => $active,
	]) }}
>
	{{ $slot }}
</a>