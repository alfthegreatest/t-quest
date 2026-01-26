<div class='add-code-component'>
    <button 
        title="add code" 
        class="add-new-btn block" 
        @click="showAddCode = !showAddCode"
    >Add code</button>

    <div x-show="showAddCode" class="px-1">
        @error('code') <span class="error">{{ $message }}</span> @enderror
        <form wire:submit.prevent="save" class="flex gap-1">
            <input 
                type="text" 
                class="input-base"
                wire:model.live.debounce.1000ms="code" 
            />
            <button 
                type="submit" 
                @disabled($errors->any())
                @class([
                    'save-btn',
                    'cursor-not-allowed bg-gray-500' => $errors->any(),
                    'hover:cursor-pointer bg-green-700 hover:bg-green-600' => !$errors->any()
                ])>save</button>
        </form>
    </div>
</div>