<table class="table align-middle mb-0" data-searchable-table>
    <thead>
        <tr>
            <th>Name</th>
            <th>URL</th>
            <th>Icon</th>
            <th>Sort</th>
            <th>Status</th>
            <th>Date</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse(($socialLinks ?? collect()) as $socialLink)
        <tr data-items='@json($socialLink)'>
            <td>{{ $socialLink->name ?? 'N/A' }}</td>
            <td><a href="{{ $socialLink->url }}" target="_blank">{{ $socialLink->url }}</a></td>
            <td>{{ $socialLink->icon ?? 'N/A' }}</td>
            <td>{{ $socialLink->sort_order ?? 0 }}</td>
            <td>
                @if($socialLink->status)
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </td>
            <td>{{ $socialLink->created_at ? $socialLink->created_at->format('M j, Y') : 'N/A' }}</td>
            <td class="text-end">
                <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil" aria-hidden="true"></i></button>
                <button class="btn btn-danger btn-sm delete-btn" type="button" data-url="{{ route('admin.social-links.destroy', $socialLink->id) }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">No social links found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
<form id="delete-form" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
{{ $socialLinks->links() }}
