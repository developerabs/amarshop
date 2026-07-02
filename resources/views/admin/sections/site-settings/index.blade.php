@extends('admin.layouts.master')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-gear" aria-hidden="true"></i></span>
        <div>
        <p class="eyebrow mb-1">Workspace</p>
        <h1 class="h3 mb-1">Settings</h1>
        <p class="text-muted mb-0">Customize workspace defaults, security options, and notification preferences.</p>
        </div>
    </div>
    
    </div>

    <section class="row g-3">
    <div class="col-12 col-xl-6">
        <form class="panel needs-validation" novalidate method="POST" action="{{ route('admin.site-settings.update') }}" enctype="multipart/form-data">
        @csrf

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-sliders" aria-hidden="true"></i><span>General Settings</span></h2><p class="text-muted mb-0">Configure workspace identity and defaults.</p></div></div>

        <div class="mb-3"><label class="form-label" for="site_name">Site Name</label><input class="form-control" id="site_name" name="site_name" type="text" value="{{ $siteSetting ? $siteSetting->site_name : old('site_name') }}" required><div class="invalid-feedback">Site name is required.</div></div>

        <div class="mb-3"><label class="form-label" for="site_title">Site Title</label><input class="form-control" id="site_title" name="site_title" type="text" value="{{ $siteSetting ? $siteSetting->site_title : old('site_title') }}" required><div class="invalid-feedback">Site title is required.</div></div>

        <div class="mb-3"><label class="form-label" for="site_description">Site Description</label><textarea class="form-control" id="site_description" name="site_description">{{ $siteSetting ? $siteSetting->site_description : old('site_description') }}</textarea></div>

        <div class="mb-3">
            <label class="form-label" for="site_logo">Site Logo</label>
            <input class="form-control" id="site_logo" name="site_logo" type="file" accept="image/*" onchange="document.getElementById('site_logo_preview').src=window.URL.createObjectURL(this.files[0]); document.getElementById('site_logo_preview').style.display='block';">
            <div class="mt-2">
                @if($siteSetting && $siteSetting->site_logo)
                    <div class="mb-2">Current:</div>
                @endif
                <img id="site_logo_preview" src="{{ $siteSetting && $siteSetting->site_logo ? Storage::url($siteSetting->site_logo) : '' }}" alt="Site Logo Preview" style="max-height:80px; display:{{ $siteSetting && $siteSetting->site_logo ? 'block' : 'none' }};">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label" for="site_favicon">Site Favicon</label>
            <input class="form-control" id="site_favicon" name="site_favicon" type="file" accept="image/*" onchange="document.getElementById('site_favicon_preview').src=window.URL.createObjectURL(this.files[0]); document.getElementById('site_favicon_preview').style.display='block';">
            <div class="mt-2">
                @if($siteSetting && $siteSetting->site_favicon)
                    <div class="mb-2">Current:</div>
                @endif
                <img id="site_favicon_preview" src="{{ $siteSetting && $siteSetting->site_favicon ? Storage::url($siteSetting->site_favicon) : '' }}" alt="Site Favicon Preview" style="max-height:48px; display:{{ $siteSetting && $siteSetting->site_favicon ? 'block' : 'none' }};">
            </div>
        </div>

        <div class="mb-3"><label class="form-label" for="site_email">Site Email</label><input class="form-control" id="site_email" name="site_email" type="email" value="{{ $siteSetting ? $siteSetting->site_email : old('site_email') }}" required></div>

        <div class="mb-3"><label class="form-label" for="site_phone">Site Phone</label><input class="form-control" id="site_phone" name="site_phone" type="text" value="{{ $siteSetting ? $siteSetting->site_phone : old('site_phone') }}" required></div>

        <div class="mb-3"><label class="form-label" for="site_address">Site Address</label><input class="form-control" id="site_address" name="site_address" type="text" value="{{ $siteSetting ? $siteSetting->site_address : old('site_address') }}" required></div>

        <div class="mb-3"><label class="form-label" for="copyright_text">Copyright Text</label><input class="form-control" id="copyright_text" name="copyright_text" type="text" value="{{ $siteSetting ? $siteSetting->copyright_text : old('copyright_text') }}"></div>

        <button class="btn btn-primary" type="submit"><i class="bi bi-check2-circle" aria-hidden="true"></i> Save Settings</button>
        </form>
    </div>
    {{-- <div class="col-12 col-xl-6">
        <div class="panel h-100">
        <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-toggles2" aria-hidden="true"></i><span>Preferences</span></h2><p class="text-muted mb-0">Control notifications and security options.</p></div></div>
        <div class="settings-list">
            <label class="settings-row"><span><strong>Email alerts</strong><small>Receive important account updates.</small></span><input class="form-check-input" type="checkbox" checked></label>
            <label class="settings-row"><span><strong>Weekly reports</strong><small>Send summary reports every Monday.</small></span><input class="form-check-input" type="checkbox" checked></label>
            <label class="settings-row"><span><strong>Two-factor authentication</strong><small>Require extra verification for admins.</small></span><input class="form-check-input" type="checkbox"></label>
        </div>
        </div>
    </div> --}}
    </section>
</div>
@endsection