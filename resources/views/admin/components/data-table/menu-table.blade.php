<table class="table align-middle mb-0" id="menusTable" data-searchable-table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Location</th>
            <th class="text-end">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($menus ?? [] as $menu)
        <tr data-items="{{ json_encode($menu) }}">
            <td>{{ $menu->name ?? 'N/A' }}</td>
            <td>
                @switch($menu->location)
                    @case('main-navigation')
                        Main Navigation
                        @break
                    @case('footer-menu')
                        Footer Menu
                        @break
                    @case('company-menu')
                        Company Menu
                        @break
                    @default
                        N/A
                @endswitch
            </td>
            <td class="text-end">
                <a class="btn btn-warning btn-sm" type="button" href="{{ route('admin.menus.items.index', $menu->id) }}"><i class="bi bi-eye" aria-hidden="true"></i></a>
                <button class="btn btn-warning btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#editModal"><i class="bi bi-pencil" aria-hidden="true"></i></button>
                <button class="btn btn-danger btn-sm delete-btn" type="button" data-id="{{ $menu->id }}" data-url="{{ route('admin.menus.destroy', $menu->id) }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="3" class="text-center">No menus found.</td>
        </tr>
        @endforelse
    </tbody>
</table>
<form id="delete-form" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>
{{ $menus->links() }}