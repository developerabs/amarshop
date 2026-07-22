@extends('admin.layouts.master')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="page-heading">
    <div class="page-heading-copy">
        <span class="page-icon"><i class="bi bi-gear" aria-hidden="true"></i></span>
        <div>
        <h1 class="h3 mb-1">Add New Page</h1>
        </div>
    </div>
    </div>

    <form novalidate method="POST" action="{{ route('admin.pages.store') }}" enctype="multipart/form-data">
        @csrf
        <section class="row g-3">
            <div class="col-8">
                <div class="panel needs-validation">
                    <div class="panel-header"><div><h2 class="h5 mb-1 section-title"><i class="bi bi-sliders" aria-hidden="true"></i><span>Add New Page</span></h2><p class="text-muted mb-0">Configure workspace identity and defaults.</p></div></div>
                    <div class="row">
                        <div class="col-12 mb-3"><label class="form-label" for="name">Name*</label><input class="form-control" id="name" name="name" type="text" value="" required></div>
                        
                        <div class="col-12 mb-3"><label class="form-label" for="permalink">Permalink*</label><div class="input-group"><span class="input-group-text">{{ config('app.url') }}</span><input class="form-control" id="permalink" name="permalink" type="text" value="" required></div></div>
                        
                        <div class="col-12 mb-3"><label class="form-label" for="title">Title*</label><input class="form-control" id="title" name="title" type="text" value="" required></div>
                        
                        <div class="col-12 mb-3"><label class="form-label" for="banner">Banner</label><input class="form-control" id="banner" name="banner" type="file"></div>
                        
                        <div class="col-12 mb-3"><label class="form-label" for="content">Content</label>
                            <div id="textEditor" style="height: 200px; border: 1px solid #ccc; padding: 10px;">
                                
                            </div>
                            <textarea class="form-control" id="content" name="content" rows="6" style="display:none;"></textarea>
                        </div>
                        
                        <div class="col-12 mb-3"><label class="form-label" for="meta_title">Meta Title</label><input class="form-control" id="meta_title" name="meta_title" type="text" value=""></div>
                        
                        <div class="col-12 mb-3"><label class="form-label" for="meta_description">Meta Description</label><textarea class="form-control" id="meta_description" name="meta_description" rows="3"></textarea></div>
                        
                        <div class="col-12 mb-3"><label class="form-label" for="meta_keywords">Meta Keywords</label><input class="form-control" id="meta_keywords" name="meta_keywords" type="text" value=""></div>
                        
                        <div class="col-12 mb-3"><label class="form-label" for="status">Status</label><div class="form-check form-switch"><input class="form-check-input" id="status" name="status" type="checkbox" value="1" checked></div></div>
            
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-4">
                    <button class="btn btn-primary" type="submit">Save Changes</button>
                </div>
            </div>
        </section>
    </form>
</div>
@endsection
@push('scripts')
<script>
    var editor = new Quill('#textEditor', {
        theme: 'snow'
    });
    editor.on('text-change', function() {
        document.getElementById('content').value = editor.root.innerHTML;
    });
</script>
@endpush