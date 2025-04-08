<?php
use Illuminate\Support\Facades\Input;
?>
@extends('backend.layouts.main')
{{-- @section('title', CONTENT_PAGE_LABEL) --}}
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('backend.content_page.index') }}">Back</a></li>
@stop
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <ol class="breadcrumb float-sm-right">

            </ol>
        </div>
    </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">{{ __('Register New CMS Page') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('backend.content_page.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">CMS Page Name<span
                                        class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="vPageName"
                                        placeholder="Enter Page Title" value="{{ old('vPageName') }}">
                                        @if ($errors->has('vPageName'))
                                        <span class="text-danger">{{ $errors->first('vPageName') }}</span>
                                        @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vSlug">Slug<span
                                        class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="vSlug" placeholder="Enter Slug"
                                        value="{{ old('vSlug') }}">
                                    @if ($errors->has('vSlug'))
                                    <span class="text-danger">{{ $errors->first('vSlug') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="txContent">Description<span
                                        class="text-danger">*</span></label>
                                    <textarea name="txContent" id="txContent" rows="3"
                                        class="form-control ckeditor">{{ old('txContent') }}</textarea>

                                    @if ($errors->has('txContent'))
                                    <span class="text-danger">{{ $errors->first('txContent') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button class="btn btn-primary"> Create </button>
                                    <a href="{{ route('backend.content_page.index') }}" class="btn btn-secondary"> Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('script')

<script src="{{ url('theme/plugins/ckeditor/ckeditor.js') }}"></script>

<script type="text/javascript">
    CKEDITOR.replace("txContent", {
        removeButtons: "Strike,Subscript,Superscript,Anchor,Styles,Copy,Paste,Cut",
        removePlugins: "about,image,pastefromword,pastetext,scayt,wsc,blockquote",
    });
</script>

@endpush
