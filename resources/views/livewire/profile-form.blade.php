<div class="max-w-md mx-auto p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md">
    <div class="space-y-4">
        <div class="relative w-full">
            <input type="text" wire:model.live.debounce.1000ms="name" placeholder=" "
                class="peer w-full px-3 pt-4 pb-2 rounded border border-gray-400 bg-transparent text-white focus:border-blue-500 focus:outline-none">
            <x-label field="name" fieldLabel="Name" />
            @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="w-full">
            <label class="block text-sm font-medium dark:text-white py-4">Email: {{ $email }}</label>
        </div>

        <div class="relative w-full">
            <input type="text" wire:model.live.debounce.1000ms="contact_telegram" placeholder=" "
                class="peer w-full px-3 pt-4 pb-2 rounded border border-gray-400 bg-transparent text-white focus:border-blue-500 focus:outline-none">
            <x-label field="contact_telegram" fieldLabel="Telegram" />
            @error('contact_telegram') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="relative w-full">
            <input type="text" wire:model.live.debounce.1000ms="contact_whatsapp" placeholder=" "
                class="peer w-full px-3 pt-4 pb-2 rounded border border-gray-400 bg-transparent text-white focus:border-blue-500 focus:outline-none">
            <x-label field="contact_whatsapp" fieldLabel="Whatsapp" />
            @error('contact_whatsapp') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>
</div>