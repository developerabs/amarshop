@extends('admin.layouts.master')
@push('styles')
<style>
    .menu-builder-wrapper {
        display: grid;
        grid-template-columns: 1.08fr 1fr;
        gap: 24px;
        min-height: 620px;
    }

    .menu-builder-panel {
        border: 1px solid #d7deea;
        border-radius: 12px;
        padding: 20px;
        background: linear-gradient(180deg, #ffffff 0%, #f5f8fc 100%);
    }

    .panel-title {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 14px;
        padding-bottom: 10px;
        border-bottom: 2px solid #0b69d1;
        color: #14345d;
    }

    .menu-source-list {
        /* max-height: 560px; */
        overflow-y: auto;
        padding-right: 4px;
    }

    .source-category {
        margin-bottom: 14px;
        border: 1px solid #cfd8e7;
        border-radius: 10px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 8px 22px rgba(11, 105, 209, 0.07);
    }

    .source-category-title {
        font-weight: 700;
        font-size: 14px;
        color: #fff;
        padding: 13px 14px;
        background: linear-gradient(120deg, #0b69d1 0%, #004fa1 100%);
        cursor: pointer;
        user-select: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: background 0.2s ease;
    }

    .source-category-title:hover {
        background: linear-gradient(120deg, #0058b2 0%, #003d7d 100%);
    }

    .source-category-toggle {
        transition: transform 0.25s ease;
    }

    .source-category.collapsed .source-category-toggle {
        transform: rotate(-90deg);
    }

    .source-category-items {
        padding: 12px;
        max-height: 500px;
        overflow-y: auto;
        transition: max-height 0.3s ease, padding 0.3s ease;
        background: #f8fbff;
    }

    .source-category.collapsed .source-category-items {
        max-height: 0;
        padding: 0;
        overflow: hidden;
    }

    .menu-source-item {
        display: flex;
        align-items: center;
        padding: 10px;
        margin-bottom: 8px;
        background: #fff;
        border: 1px solid #dbe3f1;
        border-radius: 8px;
        cursor: grab;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .menu-source-item:hover {
        border-color: #0b69d1;
        box-shadow: 0 4px 16px rgba(11, 105, 209, 0.12);
    }

    .menu-source-item.dragging {
        opacity: 0.55;
        cursor: grabbing;
    }

    .menu-source-item input[type="checkbox"] {
        margin-right: 10px;
        cursor: pointer;
    }

    .menu-source-item label {
        flex: 1;
        margin: 0;
        cursor: pointer;
        color: #27466f;
        font-weight: 500;
    }

    .empty-list {
        text-align: center;
        color: #7a8fae;
        font-size: 13px;
        padding: 12px 8px;
        border: 1px dashed #cfd8e7;
        border-radius: 8px;
        background: #fff;
    }

    .menu-items-list {
        max-height: 560px;
        overflow-y: auto;
        border: 2px dashed #bfd2ea;
        border-radius: 8px;
        padding: 10px;
        min-height: 440px;
        background: #fcfdff;
    }

    .menu-items-list.drag-over {
        background: #edf5ff;
        border-color: #0b69d1;
    }

    .menu-item {
        display: flex;
        align-items: center;
        padding: 10px;
        margin-bottom: 8px;
        background: #fff;
        border: 1px solid #d7deea;
        border-radius: 8px;
        cursor: grab;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .menu-item:hover {
        border-color: #0b69d1;
        box-shadow: 0 8px 18px rgba(14, 69, 132, 0.12);
    }

    .menu-item.dragging {
        opacity: 0.45;
    }

    .menu-item-drag-handle {
        margin-right: 10px;
        color: #8aa0bf;
        cursor: grab;
    }

    .menu-item-content {
        flex: 1;
        min-width: 0;
    }

    .menu-item-title {
        font-weight: 600;
        color: #183e6b;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .menu-item-type {
        font-size: 12px;
        color: #6f86a8;
    }

    .menu-item-actions {
        display: flex;
        gap: 6px;
    }

    .empty-state {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 210px;
        color: #7a8fae;
        text-align: center;
    }

    .menu-meta {
        margin-bottom: 12px;
        color: #3f5f8e;
        font-size: 13px;
    }

    @media (max-width: 992px) {
        .menu-builder-wrapper {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush
@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-list-check" aria-hidden="true"></i></span>
            <div>
                <h1 class="h3 mb-1">Menu Items Builder</h1>
                <p class="mb-0 text-muted">Menu: {{ $menu->name }}</p>
            </div>
        </div>
    </div>

    <section class="panel">
        <div class="menu-builder-wrapper">
            <div class="menu-builder-panel">
                <div class="panel-title"><i class="bi bi-layout-text-sidebar-reverse me-2"></i>Available Items</div>
                <div class="menu-source-list">
                    <div class="source-category" data-category="pages">
                        <div class="source-category-title" data-toggle="category">
                            <div><i class="bi bi-file-earmark-text me-2"></i>Pages</div>
                            <i class="bi bi-chevron-down source-category-toggle"></i>
                        </div>
                        <div class="source-category-items">
                            @forelse($pages as $page)
                                <div class="menu-source-item" draggable="true" data-type="page" data-id="{{ $page->id }}" data-title="{{ $page->name }}">
                                    <input type="checkbox" id="page-{{ $page->id }}" data-type="page" data-id="{{ $page->id }}">
                                    <label for="page-{{ $page->id }}">{{ $page->name }}</label>
                                </div>
                            @empty
                                <div class="empty-list">No pages available.</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="source-category collapsed" data-category="categories">
                        <div class="source-category-title" data-toggle="category">
                            <div><i class="bi bi-tags me-2"></i>Categories</div>
                            <i class="bi bi-chevron-down source-category-toggle"></i>
                        </div>
                        <div class="source-category-items">
                            @forelse($categories as $category)
                                <div class="menu-source-item" draggable="true" data-type="category" data-id="{{ $category->id }}" data-title="{{ $category->name }}">
                                    <input type="checkbox" id="category-{{ $category->id }}" data-type="category" data-id="{{ $category->id }}">
                                    <label for="category-{{ $category->id }}">{{ $category->name }}</label>
                                </div>
                            @empty
                                <div class="empty-list">No categories available.</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="source-category collapsed" data-category="brands">
                        <div class="source-category-title" data-toggle="category">
                            <div><i class="bi bi-award me-2"></i>Brands</div>
                            <i class="bi bi-chevron-down source-category-toggle"></i>
                        </div>
                        <div class="source-category-items">
                            @forelse($brands as $brand)
                                <div class="menu-source-item" draggable="true" data-type="brand" data-id="{{ $brand->id }}" data-title="{{ $brand->name }}">
                                    <input type="checkbox" id="brand-{{ $brand->id }}" data-type="brand" data-id="{{ $brand->id }}">
                                    <label for="brand-{{ $brand->id }}">{{ $brand->name }}</label>
                                </div>
                            @empty
                                <div class="empty-list">No brands available.</div>
                            @endforelse
                        </div>
                    </div>
                    <div class="source-category collapsed" data-category="custom">
                        <div class="source-category-title" data-toggle="category">
                            <div><i class="bi bi-pencil-square me-2"></i>Custom Links</div>
                            <i class="bi bi-chevron-down source-category-toggle"></i>
                        </div>
                        <div class="source-category-items">
                            <div class="menu-source-item d-block" data-type="custom">
                                <input type="text" class="form-control form-control-sm" placeholder="Custom Link Title" id="custom-link-title">
                                <input type="text" class="form-control form-control-sm mt-2" placeholder="Custom Link URL" id="custom-link-url">
                                <button class="btn btn-sm btn-outline-primary mt-2" type="button" id="custom-link-add-btn"><i class="bi bi-plus"></i> Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="menu-builder-panel">
                <div class="panel-title"><i class="bi bi-list-ul me-2"></i>Current Menu Items</div>
                <div class="menu-meta">Drag from left or use checkbox to add, then edit or delete instantly.</div>
                <div class="menu-items-list" id="menu-items-container"></div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    const menuId = {{ $menu->id }};
    const csrfToken = "{{ csrf_token() }}";
    const storeUrl = "{{ route('admin.menus.items.store', $menu->id) }}";
    const reorderUrl = "{{ route('admin.menus.items.reorder', $menu->id) }}";
    const baseUpdateUrl = "{{ route('admin.menus.items.update', [$menu->id, '__ITEM__']) }}";
    const baseDeleteUrl = "{{ route('admin.menus.items.destroy', [$menu->id, '__ITEM__']) }}";

    let menuItems = @json($menuItemsData);
    let draggedMenuItemId = null;

    function updateUrl(id) {
        return baseUpdateUrl.replace('__ITEM__', id);
    }

    function deleteUrl(id) {
        return baseDeleteUrl.replace('__ITEM__', id);
    }

    function getTypeLabel(type) {
        const labels = {
            page: 'Page',
            category: 'Category',
            brand: 'Brand',
            custom: 'Custom'
        };

        return labels[type] || type;
    }

    function normalizeReferenceId(value) {
        if (value === null || value === undefined || value === '') {
            return null;
        }
        const parsed = Number(value);
        return Number.isNaN(parsed) ? null : parsed;
    }

    function syncCheckboxes() {
        document.querySelectorAll('.menu-source-item input[type="checkbox"]').forEach((checkbox) => {
            const type = checkbox.dataset.type;
            const refId = normalizeReferenceId(checkbox.dataset.id);
            checkbox.checked = menuItems.some((item) => item.type === type && normalizeReferenceId(item.reference_id) === refId);
        });
    }

    function escapeHtml(text) {
        return String(text || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/\"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function renderMenuItems() {
        const container = document.getElementById('menu-items-container');

        if (!menuItems.length) {
            container.innerHTML = `
                <div class="empty-state">
                    <div>
                        <p class="mb-1">No menu item added yet.</p>
                        <small>Choose from left blocks: Pages, Categories, Brands.</small>
                    </div>
                </div>
            `;
            return;
        }

        container.innerHTML = menuItems.map((item) => {
            const refInfo = item.reference_id ? `#${item.reference_id}` : 'Custom';
            return `
                <div class="menu-item" draggable="true" data-item-id="${item.id}">
                    <div class="menu-item-drag-handle"><i class="bi bi-grip-vertical"></i></div>
                    <div class="menu-item-content">
                        <div class="menu-item-title">${escapeHtml(item.title)}</div>
                        <div class="menu-item-type">${getTypeLabel(item.type)} ${refInfo}</div>
                    </div>
                    <div class="menu-item-actions">
                        <button class="btn btn-sm btn-outline-primary" type="button" onclick="editMenuItem(${item.id})"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger" type="button" onclick="deleteMenuItem(${item.id})"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
            `;
        }).join('');
    }

    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.menu-item:not(.dragging)')];

        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;

            if (offset < 0 && offset > closest.offset) {
                return { offset, element: child };
            }

            return closest;
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }

    function syncArrayWithDomOrder() {
        const orderedIds = [...document.querySelectorAll('#menu-items-container .menu-item')]
            .map((node) => Number(node.dataset.itemId));

        menuItems = orderedIds
            .map((id) => menuItems.find((item) => Number(item.id) === id))
            .filter((item) => item);

        return orderedIds;
    }

    async function persistMenuOrder(itemIds) {
        const response = await fetch(reorderUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ item_ids: itemIds }),
        });

        if (!response.ok) {
            throw new Error('Failed to persist order');
        }
    }

    async function addMenuItemFromSource(data) {
        const type = data.type;
        const referenceId = normalizeReferenceId(data.id);
        const payloadTitle = (data.title || '').trim();
        const payloadUrl = (data.url || '').trim();

        const shouldPreventDuplicate = type !== 'custom';
        const alreadyExists = shouldPreventDuplicate && menuItems.some((item) => item.type === type && normalizeReferenceId(item.reference_id) === referenceId);
        if (alreadyExists) {
            return;
        }

        if (type === 'custom' && !payloadTitle) {
            Swal.fire('Validation', 'Custom link title is required.', 'warning');
            return;
        }

        try {
            const response = await fetch(storeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    type: type,
                    reference_id: referenceId,
                    title: payloadTitle || data.title,
                    url: payloadUrl || null,
                }),
            });

            if (!response.ok) {
                throw new Error('Failed to add menu item');
            }

            const result = await response.json();
            menuItems.push(result.item);
            renderMenuItems();
            syncCheckboxes();

            if (type === 'custom') {
                document.getElementById('custom-link-title').value = '';
                document.getElementById('custom-link-url').value = '';
                document.getElementById('custom-link-title').focus();
            }
        } catch (error) {
            Swal.fire('Error', 'Could not add menu item. Please try again.', 'error');
        }
    }

    function addCustomLinkItem() {
        const titleInput = document.getElementById('custom-link-title');
        const urlInput = document.getElementById('custom-link-url');

        addMenuItemFromSource({
            type: 'custom',
            id: null,
            title: titleInput.value,
            url: urlInput.value,
        });
    }

    async function deleteMenuItem(itemId) {
        const confirm = await Swal.fire({
            title: 'Delete this item?',
            text: 'This menu item will be removed.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete',
        });

        if (!confirm.isConfirmed) {
            return;
        }

        try {
            const response = await fetch(deleteUrl(itemId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error('Failed to delete menu item');
            }

            menuItems = menuItems.filter((item) => Number(item.id) !== Number(itemId));
            renderMenuItems();
            syncCheckboxes();
        } catch (error) {
            Swal.fire('Error', 'Could not delete menu item. Please try again.', 'error');
        }
    }

    async function editMenuItem(itemId) {
        const item = menuItems.find((entry) => Number(entry.id) === Number(itemId));
        if (!item) {
            return;
        }

        const dialog = await Swal.fire({
            title: 'Edit menu item',
            html: `
                <div class="text-start">
                    <label class="form-label mb-1">Title</label>
                    <input id="swal-item-title" class="form-control" value="${escapeHtml(item.title)}">
                    <label class="form-label mb-1 mt-2">URL</label>
                    <input id="swal-item-url" class="form-control" value="${escapeHtml(item.url || '')}">
                </div>
            `,
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Update',
            preConfirm: () => {
                const title = document.getElementById('swal-item-title').value.trim();
                const url = document.getElementById('swal-item-url').value.trim();

                if (!title) {
                    Swal.showValidationMessage('Title is required');
                    return false;
                }

                return { title, url };
            },
        });

        if (!dialog.isConfirmed) {
            return;
        }

        try {
            const response = await fetch(updateUrl(itemId), {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    title: dialog.value.title,
                    url: dialog.value.url,
                }),
            });

            if (!response.ok) {
                throw new Error('Failed to update menu item');
            }

            const result = await response.json();
            menuItems = menuItems.map((entry) => Number(entry.id) === Number(itemId) ? result.item : entry);
            renderMenuItems();
            syncCheckboxes();
        } catch (error) {
            Swal.fire('Error', 'Could not update menu item. Please try again.', 'error');
        }
    }

    function toggleCategory(element) {
        const category = element.closest('.source-category');
        category.classList.toggle('collapsed');
    }

    document.addEventListener('click', (event) => {
        const title = event.target.closest('[data-toggle="category"]');
        if (title) {
            toggleCategory(title);
        }
    });

    document.addEventListener('change', async (event) => {
        if (event.target.type !== 'checkbox') {
            return;
        }

        const source = event.target.closest('.menu-source-item');
        if (!source) {
            return;
        }

        const data = {
            type: source.dataset.type,
            id: source.dataset.id,
            title: source.dataset.title || source.querySelector('label').textContent,
        };

        if (event.target.checked) {
            await addMenuItemFromSource(data);
            return;
        }

        const matched = menuItems.find((item) => item.type === data.type && normalizeReferenceId(item.reference_id) === normalizeReferenceId(data.id));
        if (matched) {
            await deleteMenuItem(matched.id);
        }
    });

    document.addEventListener('dragstart', (event) => {
        const sourceItem = event.target.closest('.menu-source-item');
        if (sourceItem) {
            if (sourceItem.dataset.type === 'custom') {
                event.preventDefault();
                return;
            }

            sourceItem.classList.add('dragging');
            event.dataTransfer.effectAllowed = 'copy';
            event.dataTransfer.setData('text/plain', JSON.stringify({
                type: sourceItem.dataset.type,
                id: sourceItem.dataset.id,
                title: sourceItem.dataset.title || sourceItem.querySelector('label').textContent,
            }));
            return;
        }

        const menuItem = event.target.closest('.menu-item');
        if (menuItem) {
            menuItem.classList.add('dragging');
            draggedMenuItemId = Number(menuItem.dataset.itemId);
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', JSON.stringify({
                kind: 'menu-item',
                item_id: draggedMenuItemId,
            }));
        }
    });

    document.addEventListener('dragend', () => {
        document.querySelectorAll('.menu-source-item, .menu-item').forEach((el) => el.classList.remove('dragging'));
        const container = document.getElementById('menu-items-container');
        container.classList.remove('drag-over');
        draggedMenuItemId = null;
    });

    const dropContainer = document.getElementById('menu-items-container');

    dropContainer.addEventListener('dragover', (event) => {
        event.preventDefault();
        dropContainer.classList.add('drag-over');
    });

    dropContainer.addEventListener('dragleave', (event) => {
        if (event.target === dropContainer) {
            dropContainer.classList.remove('drag-over');
        }
    });

    dropContainer.addEventListener('drop', async (event) => {
        event.preventDefault();
        dropContainer.classList.remove('drag-over');

        try {
            const data = JSON.parse(event.dataTransfer.getData('text/plain'));

            if (data.kind === 'menu-item' && draggedMenuItemId) {
                const draggedElement = dropContainer.querySelector(`.menu-item[data-item-id="${draggedMenuItemId}"]`);
                if (!draggedElement) {
                    return;
                }

                const afterElement = getDragAfterElement(dropContainer, event.clientY);

                if (!afterElement) {
                    dropContainer.appendChild(draggedElement);
                } else {
                    dropContainer.insertBefore(draggedElement, afterElement);
                }

                const orderedIds = syncArrayWithDomOrder();
                await persistMenuOrder(orderedIds);
                return;
            }

            await addMenuItemFromSource(data);
        } catch (error) {
            renderMenuItems();
            Swal.fire('Error', 'Could not update menu item order. Please try again.', 'error');
        }
    });

    document.getElementById('custom-link-add-btn').addEventListener('click', addCustomLinkItem);

    document.getElementById('custom-link-url').addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            addCustomLinkItem();
        }
    });

    renderMenuItems();
    syncCheckboxes();
</script>
@endpush
