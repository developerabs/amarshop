@extends('admin.layouts.master')
@section('title', 'General Settings')
@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-gear" aria-hidden="true"></i></span>
        <div>
        <p class="eyebrow mb-1">Workspace</p>
        <h1 class="h3 mb-1">General Settings</h1>
        <p class="text-muted mb-0">Customize workspace defaults, security options, and notification preferences.</p>
        </div>
    </div>
    
    </div>

    <form novalidate method="POST" action="{{ route('admin.settings.general-settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <section class="row g-3">
            <div class="col-12">
            <div class="panel needs-validation">
            <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-sliders" aria-hidden="true"></i><span>General Settings</span></h2><p class="text-muted mb-0">Configure workspace identity and defaults.</p></div></div>
            <div class="row">
                <div class="col-3 mb-3"><label class="form-label" for="site_name">Site Name*</label><input class="form-control" id="site_name" name="site_name" type="text" value="{{ $generalSetting['site_name'] ?? old('site_name') }}" required><div class="invalid-feedback">Site name is required.</div></div>

                <div class="col-3 mb-3"><label class="form-label" for="site_title">Site Title*</label><input class="form-control" id="site_title" name="site_title" type="text" value="{{ $generalSetting['site_title'] ?? old('site_title') }}" required><div class="invalid-feedback">Site title is required.</div></div>


                <div class="col-3 mb-3">
                    <label class="form-label" for="site_logo">Site Logo</label>
                    <input class="form-control" id="site_logo" name="site_logo" type="file" accept="image/*" onchange="document.getElementById('site_logo_preview').src=window.URL.createObjectURL(this.files[0]); document.getElementById('site_logo_preview').style.display='block';">
                    <div class="mt-2">
                        <img id="site_logo_preview" src="{{ !empty($generalSetting['site_logo']) ? Storage::url($generalSetting['site_logo']) : '' }}" alt="Site Logo Preview" style="max-height:40px; display:{{ !empty($generalSetting['site_logo']) ? 'block' : 'none' }};">
                    </div>
                </div>

                <div class="col-3 mb-3">
                    <label class="form-label" for="site_favicon">Site Favicon</label>
                    <input class="form-control" id="site_favicon" name="site_favicon" type="file" accept="image/*" onchange="document.getElementById('site_favicon_preview').src=window.URL.createObjectURL(this.files[0]); document.getElementById('site_favicon_preview').style.display='block';">
                    <div class="mt-2">
                        <img id="site_favicon_preview" src="{{ !empty($generalSetting['site_favicon']) ? Storage::url($generalSetting['site_favicon']) : '' }}" alt="Site Favicon Preview" style="max-height:40px; display:{{ !empty($generalSetting['site_favicon']) ? 'block' : 'none' }};">
                    </div>
                </div>
                
                <div class="col-3 mb-3"><label class="form-label" for="site_email">Site Email</label><input class="form-control" id="site_email" name="site_email" type="email" value="{{ $generalSetting['site_email'] ?? old('site_email') }}"></div>

                <div class="col-3 mb-3"><label class="form-label" for="site_phone">Site Phone</label><input class="form-control" id="site_phone" name="site_phone" type="text" value="{{ $generalSetting['site_phone'] ?? old('site_phone') }}"></div>

                <div class="col-3 mb-3"><label class="form-label" for="site_address">Site Address</label><input class="form-control" id="site_address" name="site_address" type="text" value="{{ $generalSetting['site_address'] ?? old('site_address') }}"></div>

                <div class="col-3 mb-3">
                    <label class="form-label" for="primary_color">Primary Color</label>
                    <div class="d-flex align-items-center gap-2">
                        <input class="form-control form-control-color" id="primary_color" type="color" value="{{ $generalSetting['primary_color'] ?? old('primary_color', '#0d6efd') }}" title="Choose primary color" data-sync-target="primary_color_value">
                        <input class="form-control" id="primary_color_value" name="primary_color" type="text" value="{{ $generalSetting['primary_color'] ?? old('primary_color', '#0d6efd') }}" placeholder="#0d6efd">
                    </div>
                </div>

                <div class="col-3 mb-3">
                    <label class="form-label" for="secondary_color">Secondary Color</label>
                    <div class="d-flex align-items-center gap-2">
                        <input class="form-control form-control-color" id="secondary_color" type="color" value="{{ $generalSetting['secondary_color'] ?? old('secondary_color', '#6c757d') }}" title="Choose secondary color" data-sync-target="secondary_color_value">
                        <input class="form-control" id="secondary_color_value" name="secondary_color" type="text" value="{{ $generalSetting['secondary_color'] ?? old('secondary_color', '#6c757d') }}" placeholder="#6c757d">
                    </div>
                </div>
                
                <div class="col-3 mb-3"><label class="form-label" for="copyright_text">Copyright Text</label><input class="form-control" id="copyright_text" name="copyright_text" type="text" value="{{ $generalSetting['copyright_text'] ?? old('copyright_text') }}"></div>
                
                <div class="col-6 mb-3"><label class="form-label" for="site_description">Site Description</label><textarea class="form-control" id="site_description" name="site_description" rows="4">{{ $generalSetting['site_description'] ?? old('site_description') }}</textarea></div>
    
            </div>
        </div>
        </div>
        </section>
        <div class="d-flex justify-content-end mt-4">
            <button class="btn btn-primary" type="submit">Save Changes</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const colorPickers = document.querySelectorAll('input[type="color"][data-sync-target]');

        colorPickers.forEach(function (picker) {
            const targetId = picker.getAttribute('data-sync-target');
            const textInput = document.getElementById(targetId);
            if (!textInput) {
                return;
            }

            picker.addEventListener('input', function () {
                textInput.value = picker.value;
            });

            textInput.addEventListener('input', function () {
                const value = textInput.value.trim();
                if (/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(value)) {
                    picker.value = value;
                }
            });
        });
    });
</script>
@endpush