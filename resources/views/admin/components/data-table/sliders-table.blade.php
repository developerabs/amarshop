<table class="table align-middle mb-0" id="slidersTable" data-searchable-table>
    <thead>
        <tr>
            <th>Image</th>
            <th>Title</th>
            <th>Description</th>
            <th>Date</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sliders ?? [] as $slider)
        <tr data-items="{{ json_encode($slider) }}">
            <td><img src="{{ getImageUrl($slider->image) ?? '' }}" class="img-thumbnail" width="40" height="30" alt=""></td>
            <td>{{ $slider->title ?? 'N/A' }}</td>
            <td>{{ $slider->description ?? 'N/A' }}</td>
            <td>{{ $slider->created_at ? $slider->created_at->format('M j, Y') : 'N/A' }}</td>
            <td class="text-end">
                <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil" aria-hidden="true"></i></button>
                <button class="btn btn-danger btn-sm delete-btn" type="button" data-id="{{ $slider->id }}" data-url="{{ route('admin.sliders.destroy', $slider->id) }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
                <form id="delete-form" method="POST" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">No sliders found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
{{ $sliders->links() }}