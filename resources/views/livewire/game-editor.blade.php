<div class="max-w-xl mx-auto space-y-6 text-gray-200">
    <div>
        <label class="label-base">Title</label>
        <input type="text" wire:model.lazy="title" class="input-base">
    </div>

    <div class="flex flex-col sm:flex-row gap-4">
        <div class="w-full sm:flex-1">
            <label class="label-base">Start date</label>
            <input type="datetime-local" wire:model.lazy="start_date" class="input-base">
        </div>

        <div class="w-full sm:flex-1">
            <label class="label-base">Finish date</label>
            <input type="datetime-local" wire:model.lazy="finish_date" class="input-base">
        </div>
    </div>

    <div class="relative">
        <label class="label-base">Image</label>
        <label
            class="flex items-center justify-center w-full h-12 px-4 bg-gray-700 text-gray-300 rounded cursor-pointer hover:bg-gray-600 transition">
            <span>Choose file (max {{$this->getMaxImageSizeMbProperty()}}Mb)</span>
            <input type="file" wire:model="image" class="hidden">
        </label>
        @error('image')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

        @if ($this->imageUrl)
            <img src="{{ $this->imageUrl }}" class="w-full h-full object-cover" alt="Game image">
            <button type="button" wire:click="removeImage"
                class="absolute bottom-0 w-full  bg-gray-500 text-white p-2 shadow-lg transition-all duration-200 opacity-90 hover:cursor-pointer hover:opacity-100">
                remove image
            </button>
        @endif
    </div>

    <div x-data="{ description: @js($description) }">
        <label class="label-base">Description (html allowed)</label>
        <div wire:ignore>
            <textarea 
                x-model="description" 
                wire:model.blur="description" 
                x-init="$el.style.height = $el.scrollHeight + 'px'"
                @input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"
                class="input-base"
                style="overflow:hidden; resize:none; min-height: 6rem;"
            ></textarea>
        </div>
        <div class="preview-box border rounded p-4 bg-gray-900" 
            x-html="description"
        ></div>
    </div>
</div>