<div x-data="{ code: @entangle('code') }">
    <div class='fixed top-2 left-2 z-800
        flex items-center bg-white shadow-md rounded-lg overflow-hidden'
    >
        <input
            wire:model="code"
            @keydown.enter="if(code && code.trim()) $wire.enterCode()"
            class="px-3 py-2 text-sm w-40 focus:outline-none"
            placeholder="enter code"
        />
        
        <button
            class="px-4 py-2 bg-green-600 text-white text-sm
                hover:bg-green-700 cursor-pointer transition"
            :disabled="!code || !code.trim()"
            @click="if(code && code.trim()) $wire.enterCode()"
        >✓</button>
    </div>
</div>
