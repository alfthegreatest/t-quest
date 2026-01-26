<style>
.overlay-blur {
    background-color: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}
</style>

<div
    x-data="{
        open: $persist(false).as('nav_open'),
        mobile: window.innerWidth < 768,
        update() { this.mobile = window.innerWidth < 768 }
    }"
    x-init="update(); window.addEventListener('resize', update)"
    class="absolute h-screen"
>
    <button 
        @click="open = !open"
        class="fixed top-2 right-2 z-1000 p-2 rounded text-white dark:bg-gray-700 hover:bg-gray-700 cursor-pointer"
    >
        ☰
    </button>

    <!-- Overlay with blur -->
    <div 
        x-cloak
        x-show="open"
        @click="open = false"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 overlay-blur z-998"
    ></div>

    <!-- Menu panel -->
    <div 
        x-cloak
        x-show="open"
        @keydown.escape.window="open = false"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed top-0 right-0 w-[90%] md:w-64 h-full dark:bg-gray-700 shadow-xl p-6 z-999 text-white overflow-y-auto"
    >
        <h2 class="text-xl font-bold">Menu</h2>
        <ul class="menu" @click="if(mobile) open = false">
            <li>
                <x-nav-link href="/" :active="request()->is('/')">
                    Main page
                </x-nav-link>
            </li>
            <li>
                <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                    Dashboard
                </x-nav-link>
            </li>
        </ul>
        <hr>
        @can('admin')
            <x-admin-menu @click="mobile && (open = false)" />
        @endcan
        <ul class="menu-btns flex justify-between space-x-4 pt-4">
            <li><a href="{{ route('logout') }}" class="btn" @click="if(mobile) open = false">Logout</a></li>
        </ul>
    </div>
</div>