<div x-data="{ open: $persist(false).as('nav_open') }"
    x-init="$watch('open', value => localStorage.setItem('nav_open', value))" class="absolute h-screen">
    <button @click="open = !open"
        class="fixed top-4 right-4 z-50 p-2 rounded text-white hover:bg-gray-700 cursor-pointer">
        ☰
    </button>

    <div x-show="open" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed top-0 right-0 w-full md:w-64 h-full dark:bg-gray-700 shadow-xl p-6 z-40 text-white">
        <h2 class="text-xl font-bold">Menu</h2>
        <ul class="menu">
            <li><a href="/">Main page</a></li>
            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
        </ul>
        <hr>
        @can('admin')
            <h2 class="text-xl font-bold mt-8">Admin menu</h2>
            <ul class="menu mb-4">
                <li><a href="{{ route('dashboard') }}">Admin dashboard</a></li>
                <li><a href="{{ route('admin.users') }}">Users</a></li>
                <li><a href="{{ route('admin.games.index') }}">Games</a></li>
            </ul>
        @endcan
        <ul class="menu-btns flex justify-between space-x-4 pt-4">
            <li><a href="{{ route('logout') }}" class="btn ">Logout</a></li>
        </ul>
    </div>
</div>
