<div x-data x-init="
    $wire.user_timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    $wire.call('timezoneDetected');">
    <button wire:click="$set('showAddGameModal', true)"
        class="add-new-btn"
    >Add new</button>

    @if($showAddGameModal)
        <div wire:click="$set('showAddGameModal', false)"
            class="overlay">
            <div wire:click.stop class="popup">
                <h2 class="text-xl font-bold mb-4">Add a new game</h2>
                <form wire:submit.prevent="save" class="space-y-4">
                    @error('title') <span class="text-red-500">{{ $message }}</span> @enderror
                    <input 
                        type="text" 
                        wire:model.live="title" 
                        placeholder="Title" 
                        class="input-text @error('title') border-red-500 ring-red-500 @enderror">

                    <div class="w-full sm:flex-1">
                        <label class="label-base">Location </label>
                        <select wire:model.lazy="location_id" class="input-base">
                            <option value=''>not chosen</option>
                            @foreach($locations as $loc)
                            <option value="{{ $loc->id }}">{{ $loc->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full">
                        <label class="label-base">Start ({{ $user_timezone }})</label>
                        <input type="datetime-local" wire:model.lazy="start_date" class="input-base">
                        @error('start_date') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="w-full">
                        <label class="label-base">Finish ({{ $user_timezone }})</label>
                        <input type="datetime-local" wire:model.lazy="finish_date" class="input-base">
                        @error('finish_date') <span class="text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <textarea 
                        wire:model.live="description" 
                        placeholder="Description"
                        class="input-text"
                    ></textarea>

                    <div class="w-full">
                        <label
                            class="flex items-center justify-center w-full h-12 px-4 bg-gray-700 text-gray-300 rounded cursor-pointer hover:bg-gray-600 transition">
                            <span>Choose file (max {{$this->getMaxImageSizeMbProperty()}}Mb)</span>
                            <input type="file" wire:model="image" class="hidden">
                        </label>
                        @error('image')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror

                        @if ($image && $this->canPreview($image))
                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-full max-h-32 mt-2 object-cover rounded">
                        @endif
                    </div>

                    <div class="btn-group">
                        <button type="button" wire:click="$set('showAddGameModal', false)"
                            class="cancel-btn">
                            Cancel
                        </button>
                        <button type="submit"
                            class="save-btn {{ $errors->any() ? 'cursor-not-allowed bg-gray-500' : 'hover:cursor-pointer bg-green-700 hover:bg-green-600'}}"
                            {{ $errors->any() ? 'disabled' : '' }}>
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>