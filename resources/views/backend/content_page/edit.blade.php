@extends('backend.layouts.main')
@php ($title = 'Edit: '.$model->vPageName)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('backend.content_page.index') }}">{{ CONTENT_PAGE_LABEL }}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
@stop


<!-- Page content --->
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $title }}</h3>
            </div>
            <div class="card-body">
            <form method="POST" action="{{ route('backend.content_page.update',['content_page' => $model->vPageUuid]) }}" autocomplete="off">
                @method('PUT')
                {{ csrf_field() }}
                <div class="form-group">
                    <label>Slug <span class="text-danger">*</span></label>
                    <input type="text" name="vSlug" class="form-control" placeholder="Slug" value="{{ (!empty(old())) ? old('vSlug') : $model->vSlug }}" <?= ($model->exists) ? 'readonly' : '' ?>>
                    <span class="text-danger">{{ $errors->first('vSlug') }}</span>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Page name <span class="text-danger">*</span> </label>
                            <input type="text" name="vPageName" class="form-control" placeholder="Title" value="{{ (!empty(old())) ? old('vPageName') : $model->vPageName }}">
                            <span class="text-danger">{{ $errors->first('vPageName') }}</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Content <span class="text-danger">*</span> </label>
                            <textarea name="txContent" class="form-control" id="txContent" placeholder="Write some text here.." rows="5">{{ (!empty(old())) ? old('txContent') : $model->txContent }}</textarea>
                            <span class="text-danger">{{ $errors->first('txContent') }}</span>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
            </div>
        </div>
    </div>
</div>
@stop

@push('script')

<script src="{{ url('theme/plugins/ckeditor/ckeditor.js') }}"></script>

<script type="text/javascript">
    CKEDITOR.replace("txContent", {
        removeButtons: "Strike,Subscript,Superscript,Anchor,Styles,Copy,Paste,Cut",
        removePlugins: "about,image,pastefromword,pastetext,scayt,wsc,blockquote",
    });
</script>

@endpush
