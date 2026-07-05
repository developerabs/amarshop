@extends('admin.layouts.master')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
          <div class="page-heading">
            <div class="page-heading-copy">
              <span class="page-icon"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i></span>
              <div>
                <h1 class="h3 mb-1">Add New Product</h1>
              </div>
            </div>
            
          </div>
          
        <form class="needs-validation" novalidate method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf
          <section class="row g-3">
            <div class="col-8 col-xl-8">
              <div class="panel needs-validation" novalidate>
                <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-ui-checks-grid" aria-hidden="true"></i><span>Add New Product</span></h2></div></div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="formName">Product Name*</label>
                        <input class="form-control" id="formName" type="text" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="formEmail">Product Code*</label>
                        <input class="form-control" id="formEmail" type="text" name="code" value="{{ old('code') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="formPlan">Category*</label>
                        <select class="form-select" id="formPlan" name="category" required>
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
                        <label class="form-label" for="formPlan">Brand*</label>
                        <select class="form-select" id="formPlan" name="brand" required>
                            <option value="">Choose brand</option>
                            @foreach($brands ?? [] as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="formBudget">Product Cost*</label>
                        <input class="form-control" id="formBudget" type="number" min="1" name="cost" value="{{ old('cost') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="formBudget">Product Price*</label>
                        <input class="form-control" id="formBudget" type="number" min="1" name="price" value="{{ old('price') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="formBudget">Alert Quantity*</label>
                        <input class="form-control" id="formBudget" type="number" min="1" name="alert_quantity" value="{{ old('alert_quantity') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="formBudget">Stock*</label>
                        <input class="form-control" id="formBudget" type="number" min="1" name="total_stock" value="{{ old('total_stock') }}" required>
                    </div>
                    <div class="col-md-12">
                        <div class="product-variation-select">
                            <p>Choose Product Variations</p>
                            <div class="product-variant-items">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label" for="formBudget">Option*</label>
                                        <input type="text" class="form-control" name="variation_options[]" value="" placeholder="Color, Size, Material, etc." required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="formBudget">Value*</label>
                                        <select class="form-control variation-value-select" id="variationValueSelect" name="variation_values[]" multiple></select>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-secondary btn-sm mt-2 add-variation-btn"><i class="bi bi-plus" aria-hidden="true"></i> Add Variation</button>
                            <div class="variation-container">
                                <table class="table align-middle mb-0 mt-3" id="variationTable">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Additional Cost</th>
                                            <th>Additional Price</th>
                                            <th>Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Variation rows will be added here dynamically -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="formBudget">Thumbnail*</label>
                        <input class="form-control" id="formBudget" type="file" name="thumbnail" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="formBudget">Image</label>
                        <input class="form-control" id="formBudget" type="file" name="image" multiple>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="formBudget">Short Description</label>
                        <textarea class="form-control" id="formBudget" name="short_description" required rows="4">{{ old('short_description') }}</textarea>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="formBudget">Long Description</label>
                        <textarea class="form-control" id="formBudget" name="long_description" required rows="8">{{ old('long_description') }}</textarea>
                    </div>
                </div>
              </div>
            </div>
            <div class="col-4 col-xl-4">
                <div class="panel h-100">
                    <h2 class="h5 mb-3 section-title">
                        <i class="bi bi-input-cursor-text" aria-hidden="true"></i><span>Input States</span>
                    </h2>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label" for="formState">Tax</label>
                            <input class="form-control" id="formState" type="number" min="0" name="tax" value="{{ old('tax') }}" >
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="formState">Tax Type*</label>
                            <select class="form-select" id="formState" name="tax_type" required>
                                <option value="">Choose tax type</option>
                                <option value="fixed" {{ old('tax_type') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                <option value="percentage" {{ old('tax_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sampleCheck" name="has_variation">
                                <label class="form-check-label" for="sampleCheck">Has Variation</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sampleCheck" name="add_to_flash_deal">
                                <label class="form-check-label" for="sampleCheck">Add To Flash Deal</label>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="sampleCheck" name="status" checked>
                                <label class="form-check-label" for="sampleCheck">Active</label>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-send" aria-hidden="true"></i> Submit Form
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

        hasVariationCheckbox.addEventListener('change', function() {
            if (this.checked) {
                variationSelect.style.display = 'block';
            } else {
                variationSelect.style.display = 'none';
            }
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

    const addVariationBtn = document.querySelector('.add-variation-btn');
    const variationSelect = document.querySelector('.product-variation-select');
    const variationTableBody = document.querySelector('#variationTable tbody');

    function getVariationOptions() {

        const options = [];

        $('input[name="variation_options[]"]').each(function () {

            const value = $(this).val().trim();

            if (value) {
                options.push(value);
            }

        });

        return options;
    }
    // Generate combinations
    function buildCombinations(groups) {
        return groups.reduce((acc, values) => {

            if (!acc.length) {
                return values.map(value => [value]);
            }

            return acc.flatMap(previous =>
                values.map(value => [...previous, value])
            );

        }, []);
    }

    // Collect all selected values
    function getVariationGroups() {

        const groups = [];

        $('.variation-value-select').each(function () {

            const values = $(this).val();

            if (values && values.length) {
                groups.push(values);
            }

        });

        return groups;
    }

    // Render variant table
    function renderVariationTable() {

        const groups = getVariationGroups();
        const options = getVariationOptions();

        variationTableBody.innerHTML = '';

        if (!groups.length) {
            return;
        }

        const combinations = buildCombinations(groups);

        let html = '';

        combinations.forEach((combo) => {

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

                        <input type="hidden"
                            name="variant_name[]"
                            value="${variantName}">

                        <input type="hidden"
                            name="variant_attributes[]"
                            value='${jsonAttributes}'>
                    </td>

                    <td>
                        <input type="number"
                            class="form-control"
                            name="additional_cost[]"
                            value="0"
                            min="0">
                    </td>

                    <td>
                        <input type="number"
                            class="form-control"
                            name="additional_price[]"
                            value="0"
                            min="0">
                    </td>

                    <td>
                        <input type="number"
                            class="form-control"
                            name="stock[]"
                            value="0"
                            min="0">
                    </td>
                </tr>
            `;
        });

        variationTableBody.innerHTML = html;
    }

    // Add new variation field
    addVariationBtn.addEventListener('click', function () {

        const variationItems = variationSelect.querySelectorAll('.product-variant-items');

        const newVariationSection = document.createElement('div');

        newVariationSection.className = 'product-variant-items mt-3';

        newVariationSection.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Option*</label>
                    <input type="text"
                           class="form-control"
                           name="variation_options[]"
                           placeholder="Color, Size, Unit, Storage"
                           required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Value*</label>

                    <select class="form-control variation-value-select"
                            name="variation_values[]"
                            multiple>
                    </select>
                </div>
            </div>
        `;

        const lastVariationItem = variationItems[variationItems.length - 1];

        lastVariationItem.after(newVariationSection);

        $(newVariationSection)
            .find('.variation-value-select')
            .select2({
                tags: true,
                tokenSeparators: [','],
                placeholder: 'Enter values'
            });
    });

    // Existing select2 init
    $('.variation-value-select').select2({
        tags: true,
        tokenSeparators: [','],
        placeholder: 'Enter values'
    });

    // When values change
    $(document).on('change', '.variation-value-select', function () {
        renderVariationTable();
    });

});
</script>
<script>
    $('.variation-value-select').select2({
        tags: true,
        tokenSeparators: [','],
        placeholder: 'Enter values'
    });
</script>
@endpush