<div class="max-w-md mx-auto p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md">
    <div class="space-y-4">
        <div class="relative w-full">
            <input 
                type="text"
                wire:model.live.debounce.1000ms="name"
                placeholder=" " 
                class="peer w-full px-3 pt-4 pb-2 rounded border border-gray-400 bg-transparent text-white focus:border-blue-500 focus:outline-none"
            >
            <label 
                for="name" 
                class="absolute left-3 top-1 text-gray-400 text-sm transition-all 
                    peer-placeholder-shown:top-5 peer-placeholder-shown:text-gray-500 
                    peer-placeholder-shown:text-base 
                    peer-focus:top-0 peer-focus:text-sm peer-focus:text-blue-400"
            >
                Name
            </label>
            @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            <span
                x-data="{ show: false }"
                x-show="show"
                x-on:name.window="show = true; setTimeout(() => show = false, 2000)"
                x-transition.duration.500ms
                class="text-green-600 text-sm"
            >updated</span>
        </div>

        <div class="w-full">
            <label class="block text-sm font-medium dark:text-white py-4">Email: {{ $email }}</label>
        </div>

        <div class="relative w-full">
            <input 
                type="text"
                wire:model.live.debounce.1000ms="contact_telegram"
                placeholder=" " 
                class="peer w-full px-3 pt-4 pb-2 rounded border border-gray-400 bg-transparent text-white focus:border-blue-500 focus:outline-none"
            >
            <label 
                for="contact_telegram" 
                class="absolute left-3 top-1 text-gray-400 text-sm transition-all 
                    peer-placeholder-shown:top-5 peer-placeholder-shown:text-gray-500 
                    peer-placeholder-shown:text-base 
                    peer-focus:top-0 peer-focus:text-sm peer-focus:text-blue-400"
            >
                Telegram
            </label>
            @error('contact_telegram') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            <span
                x-data="{ telegram_show: false }"
                x-show="telegram_show"
                x-on:contact_telegram.window="telegram_show = true; setTimeout(() => telegram_show = false, 2000)"
                x-transition.duration.500ms
                class="text-green-600 text-sm"
            >updated</span>
        </div>

        <div class="relative w-full">
            <input 
                type="text"
                wire:model.live.debounce.1000ms="contact_whatsapp"
                placeholder=" " 
                class="peer w-full px-3 pt-4 pb-2 rounded border border-gray-400 bg-transparent text-white focus:border-blue-500 focus:outline-none"
            >
            <label 
                for="contact_whatsapp" 
                class="absolute left-3 top-1 text-gray-400 text-sm transition-all 
                    peer-placeholder-shown:top-5 peer-placeholder-shown:text-gray-500 
                    peer-placeholder-shown:text-base 
                    peer-focus:top-0 peer-focus:text-sm peer-focus:text-blue-400"
            >
                Whatsapp
            </label>
            @error('contact_whatsapp') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            <span
                x-data="{ whatsapp_show: false }"
                x-show="whatsapp_show"
                x-on:contact_whatsapp.window="whatsapp_show = true; setTimeout(() => whatsapp_show = false, 2000)"
                x-transition.duration.500ms
                class="text-green-600 text-sm"
            >updated</span>
        </div>
    </div>
</div>