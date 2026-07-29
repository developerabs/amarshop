@extends('admin.layouts.master')
@section('title', 'Profile')
@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-person-badge" aria-hidden="true"></i></span>
        <div>
        <p class="eyebrow mb-1">Account</p>
        <h1 class="h3 mb-1">Profile</h1>
        <p class="text-muted mb-0">Manage your personal details, bio, and contact preferences.</p>
        </div>
    </div>
    
    </div>

    <section class="row g-3">
    <div class="col-6">
        <form class="panel needs-validation" novalidate method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-person-gear" aria-hidden="true"></i><span>Profile Settings</span></h2><p class="text-muted mb-0">Update your account profile and contact details.</p></div></div>
        <div class="row g-3">
            <div class="col-md-12">
                <img class="avatar-img avatar-md sidebar-user-avatar" src="{{ getImageUrl(auth()->user()->profile_image) }}" alt="{{ auth()->user()->name }}">
                <input class="form-control" id="profileImage" type="file" name="profile_image" accept="image/*">
            </div>
            <div class="col-md-6">
                <label class="form-label" for="profileName">Name</label>
                <input class="form-control" id="profileName" type="text" name="name" value="{{ auth()->user()->name }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="profileEmail">Email</label>
                <input class="form-control" id="profileEmail" type="email" name="email" value="{{ auth()->user()->email }}" disabled>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="profilePhone">Phone</label>
                <input class="form-control" id="profilePhone" type="text" name="phone" value="{{ auth()->user()->phone }}" required>
            </div>
            <div class="col-6">
                <label class="form-label" for="profileAddress">Address</label>
                <input class="form-control" id="profileAddress" type="text" name="address" value="{{ auth()->user()->address }}">
            </div>
        </div>
        <div class="d-flex justify-content-end mt-4"><button class="btn btn-primary" type="submit"><i class="bi bi-check2-circle" aria-hidden="true"></i> Save Profile</button></div>
        </form>
    </div>
    <div class="col-6">
        <div class="panel">
        <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-shield-lock" aria-hidden="true"></i><span>Change Password</span></h2><p class="text-muted mb-0">Update your account password and security settings.</p></div></div>
        <form class="panel-body needs-validation" novalidate method="POST" action="{{ route('admin.profile.update-password') }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label" for="currentPassword">Current Password</label>
                    <input class="form-control" id="currentPassword" type="password" name="current_password" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="newPassword">New Password</label>
                    <input class="form-control" id="newPassword" type="password" name="new_password" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="confirmNewPassword">Confirm New Password</label>
                    <input class="form-control" id="confirmNewPassword" type="password" name="new_password_confirmation" required>
                </div>
            </div>
            <div class="d-flex justify-content-end mt-4"><button class="btn btn-primary" type="submit"><i class="bi bi-check2-circle" aria-hidden="true"></i> Change Password</button></div>
        </form>
        </div>
    </div>
    </section>
</div>
@endsection