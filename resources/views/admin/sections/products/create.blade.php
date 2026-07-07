@extends('admin.layouts.master')
@push('styles')
    <style>
        .panel {
            background: #ffffff;
            border: 1px solid #e9edf3;
            border-radius: .75rem;
            padding: 1.5rem;
            box-shadow: 0 1px 2px rgba(15, 15, 15, 0.04);
        }
        .panel-header {
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .section-title {
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.2;
        }
        .product-variation-select {
            display: none;
            background: #f8f9fb;
            border: 1px solid #dfe3e8;
            border-radius: .75rem;
            padding: 1rem;
        }
        .variation-container table {
            background: #fff;
            border-radius: .5rem;
        }
        .variation-container th,
        .variation-container td {
            vertical-align: middle;
        }
        .form-text-muted {
            display: block;
            margin-top: .35rem;
            font-size: .875rem;
            color: #6c757d;
        }
    </style>
@endpush
@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading mb-4">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i></span>
            <div>
                <h1 class="h3 mb-1">Add New Product</h1>
                <p class="text-muted mb-0">Create products faster with clear controls and organized sections.</p>
            </div>
        </div>
    </div>
    <form class="needs-validation" novalidate method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        <section class="row g-4">
            <div class="col-xl-8">
                <div class="panel">
                    <div class="panel-header">
                        <div>
                            <h2 class="h5 mb-1 section-title"><i class="bi bi-box-seam me-2" aria-hidden="true"></i>Product Details</h2>
                            <p class="text-muted mb-0">Add basic product info and pricing.</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="productName">Product Name*</label>
                            <input class="form-control" id="productName" type="text" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="productCode">Product Code*</label>
                            <div class="input-group">
                                <input class="form-control" id="productCode" type="text" name="code" value="{{ old('code') }}" required>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="generateCodeBtn"><i class="bi bi-arrow-clockwise"></i></button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="productModel">Product Model</label>
                            <input class="form-control" id="productModel" type="text" name="model" value="{{ old('model') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="productCategory">Category*</label>
                            <select class="form-select" id="productCategory" name="category" required>
                                <option value="">Choose category</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @if($category->children)
                                        @foreach($category->children as $child)
                                            <option value="{{ $child->id }}">-- {{ $child->name }}</option>
                                            @if($child->children)
                                                @foreach($child->children as $grandchild)
                                                    <option value="{{ $grandchild->id }}">---- {{ $grandchild->name }}</option>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="productBrand">Brand*</label>
                            <select class="form-select" id="productBrand" name="brand" required>
                                <option value="">Choose brand</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="productCost">Product Cost*</label>
                            <input class="form-control" id="productCost" type="number" min="1" name="cost" value="{{ old('cost') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="productPrice">Product Price*</label>
                            <input class="form-control" id="productPrice" type="number" min="1" name="price" value="{{ old('price') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="productSalePrice">Sale Price*</label>
                            <input class="form-control" id="productSalePrice" type="number" min="1" name="sale_price" value="{{ old('sale_price') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="productAlert">Alert Quantity*</label>
                            <input class="form-control" id="productAlert" type="number" min="1" name="alert_quantity" value="{{ old('alert_quantity') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="productStock">Stock*</label>
                            <input class="form-control" id="productStock" type="number" min="1" name="total_stock" value="{{ old('total_stock') }}" required>
                        </div>
                    </div>
                </div>
                <div class="panel mt-4">
                    <div class="panel-header d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="h5 mb-1 section-title"><i class="bi bi-sliders me-2" aria-hidden="true"></i>Product Variations</h2>
                            <p class="text-muted mb-0">Configure variants only when needed.</p>
                        </div>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="hasVariationCheck" name="has_variation" value="1" {{ old('has_variation') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="hasVariationCheck">Has Variation</label>
                            </div>
                            <span class="form-text-muted">Enable product variation options.</span>
                        </div>
                    </div>
                    <div class="product-variation-select">
                        <p class="mb-3">Choose product variation options to generate variant combinations.</p>
                        <div class="product-variant-items">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="variationOptionInput">Option*</label>
                                    <input type="text" class="form-control" id="variationOptionInput" name="variation_options[]" value="" placeholder="Color, Size, Material, etc.">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="variationValueSelect">Value*</label>
                                    <select class="form-select variation-value-select" id="variationValueSelect" name="variation_values[]" multiple></select>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm mt-3 add-variation-btn"><i class="bi bi-plus" aria-hidden="true"></i> Add Variation</button>
                        <div class="variation-container mt-4">
                            <table class="table align-middle mb-0" id="variationTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Additional Cost</th>
                                        <th>Additional Price</th>
                                        <th>Stock</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel mt-4">
                    <div class="panel-header">
                        <div>
                            <h2 class="h5 mb-1 section-title"><i class="bi bi-image me-2" aria-hidden="true"></i>Media & Descriptions</h2>
                            <p class="text-muted mb-0">Upload images and add descriptive text for the product.</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="productThumbnail">Thumbnail*</label>
                            <input class="form-control" id="productThumbnail" type="file" name="thumbnail" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="productImages">Images*</label>
                            <input class="form-control" id="productImages" type="file" name="image[]" required multiple>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="shortDescription">Short Description</label>
                            <textarea class="form-control" id="shortDescription" name="short_description" required rows="4">{{ old('short_description') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="longDescription">Description</label>
                            <textarea class="form-control" id="longDescription" name="description" required rows="8">{{ old('description') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="metaTitle">Meta Title</label>
                            <input class="form-control" id="metaTitle" type="text" name="meta_title" value="{{ old('meta_title') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="metaDescription">Meta Description</label>
                            <textarea class="form-control" id="metaDescription" name="meta_description" rows="4">{{ old('meta_description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="panel h-100">
                    <div class="panel-header">
                        <div>
                            <h2 class="h5 mb-1 section-title"><i class="bi bi-gear me-2" aria-hidden="true"></i>Product Settings</h2>
                            <p class="text-muted mb-0">Toggle product visibility and promotions.</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="flashDealCheck" name="flash_deal" value="1" {{ old('flash_deal') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="flashDealCheck">Flash Deal</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="trendingCheck" name="trending" value="1" {{ old('trending') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="trendingCheck">Trending Now</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="dailyOfferCheck" name="daily_offer" value="1" {{ old('daily_offer') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="dailyOfferCheck">Daily Offer</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="bestDealsCheck" name="best_deals" value="1" {{ old('best_deals') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="bestDealsCheck">Top Sale</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="newArrivalsCheck" name="new_arrivals" value="1" {{ old('new_arrivals') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="newArrivalsCheck">New Arrivals</label>
                            </div>
                        </div>
                        <div class="col-12 mt-4">
                            <label class="form-label" for="discountAmount">Discount</label>
                            <div class="input-group">
                                <input class="form-control" id="discountAmount" type="number" min="0" name="discount_amount" value="{{ old('discount_amount') }}" placeholder="Amount">
                                <select class="form-select" id="discountType" name="discount_type">
                                    <option value="">Type</option>
                                    <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                    <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="taxAmount">Tax</label>
                            <div class="input-group">
                                <input class="form-control" id="taxAmount" type="number" min="0" name="tax_amount" value="{{ old('tax_amount') }}" placeholder="Tax Amount">
                                <select class="form-select" id="taxType" name="tax_type">
                                    <option value="">Type</option>
                                    <option value="inclusive" {{ old('tax_type') == 'inclusive' ? 'selected' : '' }}>Inclusive</option>
                                    <option value="exclusive" {{ old('tax_type') == 'exclusive' ? 'selected' : '' }}>Exclusive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="statusCheck" name="status" value="1" {{ old('status') == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="statusCheck">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-end">
                <button class="btn btn-primary px-4" type="submit">
                    <i class="bi bi-send me-2" aria-hidden="true"></i> Submit Form
                </button>
            </div>
        </section>
    </form>
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hasVariationCheckbox = document.querySelector('input[name="has_variation"]');
        const variationSelect = document.querySelector('.product-variation-select');
        const variationTableBody = document.querySelector('#variationTable tbody');

        if (!hasVariationCheckbox || !variationSelect) {
            return;
        }

        function toggleVariationSection() {
            if (hasVariationCheckbox.checked) {
                variationSelect.style.display = 'block';
            } else {
                variationSelect.style.display = 'none';
                variationTableBody.innerHTML = '';
            }
        }

        function getVariationOptions() {
            const options = [];
            document.querySelectorAll('input[name="variation_options[]"]').forEach(input => {
                const value = input.value.trim();
                if (value) {
                    options.push(value);
                }
            });
            return options;
        }

        function getVariationGroups() {
            const groups = [];
            document.querySelectorAll('.variation-value-select').forEach(select => {
                const values = $(select).val();
                if (values && values.length) {
                    groups.push(values);
                }
            });
            return groups;
        }

        function buildCombinations(groups) {
            return groups.reduce((acc, values) => {
                if (!acc.length) {
                    return values.map(value => [value]);
                }
                return acc.flatMap(previous => values.map(value => [...previous, value]));
            }, []);
        }

        function renderVariationTable() {
            const groups = getVariationGroups();
            const options = getVariationOptions();
            variationTableBody.innerHTML = '';

            if (!groups.length || !options.length) {
                return;
            }

            const combinations = buildCombinations(groups);
            let html = '';
            combinations.forEach(combo => {
                const variantName = combo.join('/');
                const variantAttributes = {};
                options.forEach((option, index) => {
                    if (combo[index] !== undefined) {
                        variantAttributes[option] = combo[index];
                    }
                });
                const jsonAttributes = JSON.stringify(variantAttributes);
                html += `
                    <tr>
                        <td>
                            ${variantName}
                            <input type="hidden" name="variant_name[]" value="${variantName}">
                            <input type="hidden" name="variant_attributes[]" value='${jsonAttributes}'>
                        </td>
                        <td><input type="number" class="form-control" name="additional_cost[]" value="0" min="0"></td>
                        <td><input type="number" class="form-control" name="additional_price[]" value="0" min="0"></td>
                        <td><input type="number" class="form-control" name="stock[]" value="0" min="0"></td>
                    </tr>
                `;
            });
            variationTableBody.innerHTML = html;
        }

        function initSelect2(element) {
            $(element).select2({
                tags: true,
                tokenSeparators: [','],
                placeholder: 'Enter values',
                width: '100%'
            });
        }

        document.querySelectorAll('.variation-value-select').forEach(initSelect2);

        // When values change
        $(document).on('change', '.variation-value-select', function () {
            renderVariationTable();
        });
        

        document.querySelector('.add-variation-btn').addEventListener('click', function() {
            const variationItems = variationSelect.querySelectorAll('.product-variant-items');
            const newVariationSection = document.createElement('div');
            newVariationSection.className = 'product-variant-items mt-3';
            newVariationSection.innerHTML = `
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Option*</label>
                        <input type="text" class="form-control" name="variation_options[]" placeholder="Color, Size, Unit, Storage">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Value*</label>
                        <select class="form-select variation-value-select" name="variation_values[]" multiple></select>
                    </div>
                </div>
            `;
            variationItems[variationItems.length - 1].after(newVariationSection);
            initSelect2(newVariationSection.querySelector('.variation-value-select'));
        });

        hasVariationCheckbox.addEventListener('change', toggleVariationSection);
        toggleVariationSection();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function(){
        function randCode(){
            var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var out = '';
            for(var i=0;i<8;i++) out += chars.charAt(Math.floor(Math.random()*chars.length));
            return out;
        }
        var btn = document.getElementById('generateCodeBtn');
        if(btn){
            btn.addEventListener('click', function(){
                var input = document.getElementById('productCode');
                if(input) input.value = randCode();
            });
        }
    });
</script>
@endpush