<div x-data="{ show: false, message: '', timer: null }"
    x-on:toast.window="
        clearTimeout(timer);
        message = $event.detail; 
        show = true; 
        timer = setTimeout(() => show = false, 2500)"
    class="fixed top-4 left-4 z-50">
    <div 
        x-show="show" 
        x-transition 
        class="px-4 py-2 rounded-xl shadow-md 
            text-white text-sm font-medium 
            bg-gray-700/80 backdrop-blur-md"
    >
        <span x-text="message"></span>
    </div>
</div>