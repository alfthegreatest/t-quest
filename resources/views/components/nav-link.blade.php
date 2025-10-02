@props(['href' => '#'])

<a
	href="{{ $href }}"
	{{ $attributes->merge(['class' => 'px-5 py-1.5 border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal']) }}
>
	{{ $slot }}
</a>