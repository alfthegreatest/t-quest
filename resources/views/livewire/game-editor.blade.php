<div class="max-w-xl mx-auto space-y-6 text-gray-200">
    <div>
        <label class="block mb-1 text-sm text-gray-400">Title</label>
        <input type="text" wire:model.lazy="title"
            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded focus:ring focus:ring-indigo-600/30">
    </div>

    <div>
        <label class="block mb-1 text-sm text-gray-400">Description</label>
        <textarea wire:model.lazy="description" rows="4"
            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded focus:ring focus:ring-indigo-600/30"></textarea>
    </div>

    <div>
        <label class="block mb-1 text-sm text-gray-400">Start date</label>
        <input type="datetime-local" wire:model.lazy="start_date"
            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded focus:ring focus:ring-indigo-600/30">
    </div>

    <div>
        <label class="block mb-1 text-sm text-gray-400">Finish date</label>
        <input type="datetime-local" wire:model.lazy="finish_date"
            class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded focus:ring focus:ring-indigo-600/30">
    </div>
</div>