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

    <div>
        <label class="label-base">Description (html allowed)</label>
        <textarea wire:model.lazy="description" rows="4" class="input-base"></textarea>
    </div>
</div>