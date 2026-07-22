@extends('admin.layouts.master')
@push('styles')
<style>
    .menu-builder-wrapper {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        min-height: 600px;
    }
    
    .menu-builder-panel {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        background: #f9f9f9;
    }
    
    .panel-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #007bff;
    }
    
    .menu-source-list {
        max-height: 500px;
        overflow-y: auto;
    }
    
    .menu-source-item {
        display: flex;
        align-items: center;
        padding: 8px;
        margin-bottom: 8px;
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        cursor: grab;
        transition: all 0.2s;
    }
    
    .menu-source-item:hover {
        background: #f5f5f5;
        border-color: #007bff;
    }
    
    .menu-source-item.dragging {
        opacity: 0.5;
        cursor: grabbing;
    }
    
    .menu-source-item input[type="checkbox"] {
        margin-right: 10px;
        cursor: pointer;
    }
    
    .menu-source-item label {
        flex: 1;
        cursor: pointer;
        margin: 0;
    }
    
    .source-category {
        margin-bottom: 15px;
    }
    
    .source-category-title {
        font-weight: 600;
        font-size: 13px;
        color: #666;
        margin-bottom: 8px;
        text-transform: uppercase;
    }
    
    .menu-items-list {
        max-height: 500px;
        overflow-y: auto;
        border: 2px dashed #ddd;
        border-radius: 4px;
        padding: 10px;
        min-height: 400px;
    }
    
    .menu-items-list.drag-over {
        background: #e8f4f8;
        border-color: #007bff;
    }
    
    .menu-item {
        display: flex;
        align-items: center;
        padding: 10px;
        margin-bottom: 8px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: grab;
        transition: all 0.2s;
    }
    
    .menu-item:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border-color: #007bff;
    }
    
    .menu-item.dragging {
        opacity: 0.5;
        cursor: grabbing;
    }
    
    .menu-item-drag-handle {
        display: flex;
        align-items: center;
        margin-right: 10px;
        cursor: grab;
        color: #999;
    }
    
    .menu-item-content {
        flex: 1;
    }
    
    .menu-item-title {
        font-weight: 500;
        margin-bottom: 3px;
    }
    
    .menu-item-type {
        font-size: 12px;
        color: #999;
    }
    
    .menu-item-actions {
        display: flex;
        gap: 5px;
    }
    
    .menu-item-actions button {
        padding: 4px 8px;
        font-size: 12px;
    }
    
    .empty-state {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 200px;
        color: #999;
        text-align: center;
    }
    
    @media (max-width: 768px) {
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
            </div>
        </div>
    </div>

    <section class="panel">
        <div class="menu-builder-wrapper">
            <!-- Left Panel: Pages, Categories, Brands -->
            <div class="menu-builder-panel">
                <div class="panel-title">
                    <i class="bi bi-plus-circle me-2"></i>Available Items
                </div>
                
                <div class="menu-source-list">
                    <!-- Pages -->
                    <div class="source-category">
                        <div class="source-category-title">
                            <i class="bi bi-file-earmark me-2"></i>Pages
                        </div>
                        <div id="pages-list">
                            <div class="menu-source-item" draggable="true" data-type="page" data-id="1">
                                <input type="checkbox" id="page-1">
                                <label for="page-1">About Us</label>
                            </div>
                            <div class="menu-source-item" draggable="true" data-type="page" data-id="2">
                                <input type="checkbox" id="page-2">
                                <label for="page-2">Contact Us</label>
                            </div>
                            <div class="menu-source-item" draggable="true" data-type="page" data-id="3">
                                <input type="checkbox" id="page-3">
                                <label for="page-3">Privacy Policy</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Categories -->
                    <div class="source-category">
                        <div class="source-category-title">
                            <i class="bi bi-tag me-2"></i>Categories
                        </div>
                        <div id="categories-list">
                            <div class="menu-source-item" draggable="true" data-type="category" data-id="1">
                                <input type="checkbox" id="cat-1">
                                <label for="cat-1">Electronics</label>
                            </div>
                            <div class="menu-source-item" draggable="true" data-type="category" data-id="2">
                                <input type="checkbox" id="cat-2">
                                <label for="cat-2">Clothing</label>
                            </div>
                            <div class="menu-source-item" draggable="true" data-type="category" data-id="3">
                                <input type="checkbox" id="cat-3">
                                <label for="cat-3">Home & Garden</label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Brands -->
                    <div class="source-category">
                        <div class="source-category-title">
                            <i class="bi bi-star me-2"></i>Brands
                        </div>
                        <div id="brands-list">
                            <div class="menu-source-item" draggable="true" data-type="brand" data-id="1">
                                <input type="checkbox" id="brand-1">
                                <label for="brand-1">Brand A</label>
                            </div>
                            <div class="menu-source-item" draggable="true" data-type="brand" data-id="2">
                                <input type="checkbox" id="brand-2">
                                <label for="brand-2">Brand B</label>
                            </div>
                            <div class="menu-source-item" draggable="true" data-type="brand" data-id="3">
                                <input type="checkbox" id="brand-3">
                                <label for="brand-3">Brand C</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Panel: Menu Items -->
            <div class="menu-builder-panel">
                <div class="panel-title">
                    <i class="bi bi-list-ul me-2"></i>Menu Items
                </div>
                
                <div class="menu-items-list" id="menu-items-container">
                    <div class="empty-state">
                        <div>
                            <p>Drag items here to add them to the menu</p>
                            <small style="color: #ccc;">or check items from the left panel</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@push('scripts')
<script>
    let menuItems = [];
    let draggedElement = null;

    // Initialize drag and drop for source items
    document.addEventListener('dragstart', (e) => {
        if (e.target.classList.contains('menu-source-item')) {
            draggedElement = e.target;
            e.target.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'copy';
            e.dataTransfer.setData('text/plain', JSON.stringify({
                type: e.target.dataset.type,
                id: e.target.dataset.id,
                title: e.target.querySelector('label').textContent
            }));
        } else if (e.target.closest('.menu-item')) {
            draggedElement = e.target.closest('.menu-item');
            e.target.closest('.menu-item').classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', JSON.stringify({
                isMenuItem: true,
                id: e.target.closest('.menu-item').dataset.id
            }));
        }
    });

    document.addEventListener('dragend', (e) => {
        document.querySelectorAll('.menu-source-item, .menu-item').forEach(el => {
            el.classList.remove('dragging');
        });
        document.getElementById('menu-items-container').classList.remove('drag-over');
    });

    // Menu items container drag and drop
    const container = document.getElementById('menu-items-container');

    container.addEventListener('dragover', (e) => {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'copy';
        container.classList.add('drag-over');
    });

    container.addEventListener('dragleave', (e) => {
        if (e.target === container) {
            container.classList.remove('drag-over');
        }
    });

    container.addEventListener('drop', (e) => {
        e.preventDefault();
        container.classList.remove('drag-over');
        
        try {
            const data = JSON.parse(e.dataTransfer.getData('text/plain'));
            
            if (data.isMenuItem) {
                // Reorder menu item (move within container)
                const draggedItem = document.querySelector(`[data-id="${data.id}"].menu-item`);
                const afterElement = getDragAfterElement(container, e.clientY);
                
                if (afterElement == null) {
                    container.appendChild(draggedItem);
                } else {
                    container.insertBefore(draggedItem, afterElement);
                }
            } else {
                // Add new item from source
                addMenuItemFromSource(data);
            }
        } catch (err) {
            console.error('Drop error:', err);
        }
    });

    // Checkbox handling
    document.addEventListener('change', (e) => {
        if (e.target.type === 'checkbox') {
            const item = e.target.closest('.menu-source-item');
            if (!item) return;
            
            const data = {
                type: item.dataset.type,
                id: item.dataset.id,
                title: item.querySelector('label').textContent
            };
            
            if (e.target.checked) {
                addMenuItemFromSource(data);
            } else {
                removeMenuItemById(data.type, data.id);
            }
        }
    });

    function addMenuItemFromSource(data) {
        // Check if already exists
        if (menuItems.some(item => item.type === data.type && item.id === data.id)) {
            return;
        }

        clearEmptyState();
        
        const menuItem = {
            type: data.type,
            id: data.id,
            title: data.title,
            menuId: 'menu-' + Date.now() + Math.random()
        };
        
        menuItems.push(menuItem);
        renderMenuItems();
    }

    function removeMenuItemById(type, id) {
        menuItems = menuItems.filter(item => !(item.type === type && item.id === id));
        
        // Uncheck checkbox
        const checkbox = document.querySelector(`input[data-type="${type}"][data-id="${id}"]`);
        if (checkbox) {
            checkbox.checked = false;
        }
        
        renderMenuItems();
    }

    function renderMenuItems() {
        const container = document.getElementById('menu-items-container');
        
        if (menuItems.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <div>
                        <p>Drag items here to add them to the menu</p>
                        <small style="color: #ccc;">or check items from the left panel</small>
                    </div>
                </div>
            `;
            return;
        }

        container.innerHTML = menuItems.map((item, index) => `
            <div class="menu-item" draggable="true" data-id="${item.menuId}">
                <div class="menu-item-drag-handle">
                    <i class="bi bi-grip-vertical"></i>
                </div>
                <div class="menu-item-content">
                    <div class="menu-item-title">${item.title}</div>
                    <div class="menu-item-type">
                        ${getTypeLabel(item.type)} #${item.id}
                    </div>
                </div>
                <div class="menu-item-actions">
                    <button class="btn btn-sm btn-outline-primary" onclick="editMenuItem('${item.menuId}')">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="deleteMenuItem('${item.menuId}')">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `).join('');
    }

    function getTypeLabel(type) {
        const labels = {
            'page': 'Page',
            'category': 'Category',
            'brand': 'Brand'
        };
        return labels[type] || type;
    }

    function editMenuItem(menuId) {
        alert('Edit functionality - MenuItem: ' + menuId);
    }

    function deleteMenuItem(menuId) {
        const index = menuItems.findIndex(item => item.menuId === menuId);
        if (index !== -1) {
            const item = menuItems[index];
            
            // Uncheck checkbox
            const checkboxes = document.querySelectorAll(`.menu-source-item[data-type="${item.type}"][data-id="${item.id}"] input`);
            checkboxes.forEach(cb => cb.checked = false);
            
            menuItems.splice(index, 1);
            renderMenuItems();
        }
    }

    function clearEmptyState() {
        const emptyState = document.querySelector('.empty-state');
        if (emptyState) {
            emptyState.remove();
        }
    }

    function getDragAfterElement(container, y) {
        const draggableElements = [...container.querySelectorAll('.menu-item:not(.dragging)')];
        return draggableElements.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            
            if (offset < 0 && offset > closest.offset) {
                return { offset: offset, element: child };
            } else {
                return closest;
            }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }
</script>
@endpush