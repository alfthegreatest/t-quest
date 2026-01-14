@props(['mobile' => false])

<h2 class="text-xl font-bold mt-8">Admin menu</h2>

<ul class="menu mb-4" @click="mobile && (open = false)">
    <li>
        <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
            Admin dashboard
        </x-nav-link>
    </li>

    <li>
        <x-nav-link href="{{ route('admin.users') }}" :active="request()->routeIs('admin.users')">
            Users
        </x-nav-link>
    </li>

    <li>
        <x-nav-link href="{{ route('admin.games.index') }}" :active="request()->routeIs('admin.games.index')">
            Games
        </x-nav-link>
    </li>

    <li>
        <x-nav-link href="{{ route('admin.locations.index') }}" :active="request()->routeIs('admin.locations.index')">
            Locations
        </x-nav-link>
    </li>
</ul>
