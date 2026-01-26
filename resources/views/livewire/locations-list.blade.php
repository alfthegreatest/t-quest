<div>
    <div class="mx-auto overflow-x-auto shadow rounded-lg dark:bg-gray-800 max-w-[800px]">
        <livewire:create-location />

        <div class="p-4 navigation">
            {{ $locations->links() }}
        </div>

        <table class="users-table min-w-full border-collapse">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="w-[50px]">ID</th>
                    <th>Title</th>
                    <th class="w-[50px]"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($locations as $item)
                    <tr wire:key="location-{{ $item->id }}" class="hover:bg-gray-700">
                        <td class="text-center w-[50px]">{{ $item->id }}</td>
                        <td>{{ $item->title }}</td>
                        <td class="text-left w-[50px]">
                            <button 
                                type="button" 
                                wire:click="confirmDelete({{ $item->id }}, '{{ $item->title }}')"
                                class="del-btn"
                            >del</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4 navigation">
            {{ $locations->links() }}
        </div>

        @if($showModal)
            <div wire:click="$set('showModal', false)"
                class="overlay">
                <div wire:click.stop class="popup">
                    <h2 class="text-xl font-bold mb-4">Do you want to delete {{ $locationTitle }}?</h2>
                    <form class="space-y-4">
                        <div class="btn-group">
                            <button type="button" wire:click="delete"
                                class="yes-btn">Yes</button>
                            <button type="button" wire:click="$set('showModal', false)"
                                class="no-btn">No</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>