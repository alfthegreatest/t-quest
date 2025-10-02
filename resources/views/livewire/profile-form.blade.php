<div class="max-w-md mx-auto p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md">
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium dark:text-white py-4">Name</label>
            <input type="text" wire:model.live.debounce.1000ms="name" class="w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white p-2">
            @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            <span
                x-data="{ show: false }"
                x-on:show-success.window="show = true; setTimeout(() => show = false, 2000)"
                x-show="show"
                x-transition.duration.500ms
                class="text-green-600 text-sm"
            >updated</span>
        </div>
    </div>
</div>