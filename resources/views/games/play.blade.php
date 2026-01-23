@extends('layouts.play')

@section('content')
<div id="game-page"
    x-data="mapComponent(@js($locations))"
    x-init="initMap()"
>

    <div 
        x-show="isNear" 
        x-transition.opacity.duration.300ms
        x-cloak
        x-effect="if (isNear && activeLevelId) Livewire.dispatch('level-changed', { levelId: activeLevelId })"
    >
        <livewire:enter-code-field />
    </div>

    <div  
        class="fixed inset-0 w-full h-full" 
    >
        <!-- Loader overlay -->
        <div 
            x-show="loading"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-0 z-[1001] flex items-center justify-center"
            style="background-color: rgba(0, 0, 0, 0.7); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
        >
            <div class="text-center">
                <div class="relative">
                    <div class="w-20 h-20 border-4 border-blue-200 border-t-blue-500 rounded-full animate-spin"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-4 text-white text-lg font-medium">Map is loading...</p>
            </div>
        </div>

        <!-- Map container -->
        <div x-ref="mapContainer" class="h-full"></div>
    </div>
</div>
@endsection

@push('scripts')
    <script type="module">
        import mapComponent from '{{ Vite::asset('resources/js/components/mapComponent.js') }}';
        // register before Alpine initialisation
        window.mapComponent = mapComponent;
    </script>
@endpush