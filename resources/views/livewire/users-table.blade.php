<div class="mx-auto overflow-x-auto shadow rounded-lg dark:bg-gray-800 max-w-[800px]">
    <div class="p-4">
        {{ $users->links() }}
    </div>

    <table class="users-table min-w-full border-collapse">
        <thead class="bg-gray-100 dark:bg-gray-700">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="p-4">
        {{ $users->links() }}
    </div>
</div>