<div>
    <table class='codes-table mt-4 min-w-full border-collapse'>
        <thead class="bg-gray-100 dark:bg-gray-700">
            <tr>
                <th class='!text-left'>Code</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($codes as $code)
                <tr wire:key="code-{{ $code->id }}" class="hover:bg-gray-700">
                    <td>{{ $code->code }}</td>
                    <td class="text-right">
                        <button 
                            type="button" 
                            wire:click=""
                            class="del-btn"
                        >del</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>