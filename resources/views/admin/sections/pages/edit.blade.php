@extends('admin.layouts.master')

@push('styles')
<style>
    .page-create-grid {
        display: grid;
        grid-template-columns: 1.4fr 1fr;
        gap: 16px;
    }

    .panel-subtitle {
        font-size: 13px;
        color: #6c7a89;
        margin: 0;
    }

    .banner-preview {
        border: 1px solid #dbe3f1;
        border-radius: 8px;
        padding: 8px;
        background: #f9fbff;
    }

    .banner-preview img {
        width: 100%;
        max-height: 180px;
        object-fit: cover;
        border-radius: 6px;
    }

    @media (max-width: 992px) {
        .page-create-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
        <div class="page-heading-copy">
            <span class="page-icon"><i class="bi bi-pencil-square" aria-hidden="true"></i></span>
            <div>
                <h1 class="h3 mb-1">Edit Page</h1>
                <p class="text-muted mb-0">Update content, SEO, and publication status.</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.pages.update', $page->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" value="0">

        <div class="page-create-grid">
            <section class="panel">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title"><i class="bi bi-file-earmark-text" aria-hidden="true"></i><span>Page Content</span></h2>
                        <p class="panel-subtitle">Update body content and primary page details.</p>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label" for="name">Name*</label>
                        <input class="form-control" id="name" name="name" type="text" value="{{ old('name', $page->name) }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="permalink">Permalink*</label>
                        <div class="input-group">
                            <span class="input-group-text">{{ rtrim(config('app.url'), '/') }}/</span>
                            <input class="form-control" id="permalink" name="permalink" type="text" value="{{ old('permalink', $page->permalink) }}" required>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="title">Title*</label>
                        <input class="form-control" id="title" name="title" type="text" value="{{ old('title', $page->title) }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="banner">Banner</label>
                        <input class="form-control" id="banner" name="banner" type="file" accept="image/*">
                    </div>

                    @if(!empty($page->banner))
                    <div class="col-12">
                        <div class="banner-preview">
                            <img src="{{ getImageUrl($page->banner) }}" alt="Current banner">
                        </div>
                    </div>
                    @endif

                    <div class="col-12">
                        <label class="form-label" for="content">Content</label>
                        <textarea class="form-control rich-editor" id="content" name="content" rows="14">{{ old('content', $page->content) }}</textarea>
                    </div>
                </div>
            </section>

            <section class="panel">
                <div class="panel-header">
                    <div>
                        <h2 class="h5 mb-1 section-title"><i class="bi bi-search" aria-hidden="true"></i><span>SEO & Publish</span></h2>
                        <p class="panel-subtitle">Fine-tune search metadata and visibility.</p>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label" for="meta_title">Meta Title</label>
                        <input class="form-control" id="meta_title" name="meta_title" type="text" value="{{ old('meta_title', $page->meta_title) }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="meta_description">Meta Description</label>
                        <textarea class="form-control" id="meta_description" name="meta_description" rows="4">{{ old('meta_description', $page->meta_description) }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label" for="meta_keywords">Meta Keywords</label>
                        <input class="form-control" id="meta_keywords" name="meta_keywords" type="text" value="{{ old('meta_keywords', $page->meta_keywords) }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label d-block" for="status">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" id="status" name="status" type="checkbox" value="1" {{ old('status', $page->status) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Publish now</label>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <button class="btn btn-primary" type="submit">
                <i class="bi bi-check2-circle" aria-hidden="true"></i> Update Page
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        tinymce.init({
            selector: 'textarea.rich-editor',
            height: 420,
            menubar: false,
            plugins: 'lists link table code fullscreen preview',
            toolbar: 'undo redo | blocks | bold italic underline | bullist numlist | link table | alignleft aligncenter alignright | code preview fullscreen',
            branding: false,
            promotion: false,
            setup: function(editor) {
                editor.on('change keyup', function() {
                    editor.save();
                });
            }
        });
    });
</script>
@endpush
