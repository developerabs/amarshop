<table class="table align-middle mb-0" id="usersTable" data-searchable-table>
    <thead>
        <tr>
            <th></th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users ?? [] as $user)
        <tr data-items="{{ json_encode($user) }}">
            <td><img src="{{ getImageUrl($user->image) ?? "" }}" class="img-thumbnail" width="40" height="30"></td>
            <td>{{ $user->name ?? 'N/A' }}</td>
            <td>{{ $user->email ?? 'N/A' }}</td>
            <td>{{ $user->role ?? 'N/A' }}</td>
            <td>
                @if($user->status)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </td>
            <td class="text-end">
                <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil" aria-hidden="true"></i></button>
                <button class="btn btn-danger btn-sm delete-btn" type="button" data-id="{{ $user->id }}" data-url="{{ route('admin.users.destroy', $user->id) }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
                <form id="delete-form" method="POST" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">No users found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
{{ $users->links() }}