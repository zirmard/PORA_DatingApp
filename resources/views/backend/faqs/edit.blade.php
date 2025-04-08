<?php
use Illuminate\Support\Facades\Input;
?>

@extends('backend.layouts.main')
@section('title', FAQ_LABEL)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('backend.faqs.index') }}">Back</a></li>
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
                <div class="card-header">{{ __('Edit FAQ') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('backend.faqs.update', $model->vFaqUuid) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vQuestionCategory">Question Category<i
                                        class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="vQuestionCategory"
                                        placeholder="Enter Question Category" value="{{ old('vQuestionCategory', $model->vQuestionCategory) }}">
                                    @if ($errors->has('vQuestionCategory'))
                                    <span class="text-danger">{{ $errors->first('vQuestionCategory') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vQuestion">Question<i
                                        class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="vQuestion"
                                        placeholder="Enter Question" value="{{ old('vQuestion', $model->vQuestion) }}">
                                    @if ($errors->has('vQuestion'))
                                    <span class="text-danger">{{ $errors->first('vQuestion') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="txAnswer">Answer<i
                                        class="text-danger">*</i></label>
                                    <textarea name="txAnswer" id="txAnswer" rows="3"
                                        class="form-control ckeditor">{{ old('txAnswer', $model->txAnswer) }}</textarea>

                                    @if ($errors->has('txAnswer'))
                                        <span class="text-danger">{{ $errors->first('txAnswer') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button class="btn btn-primary"> Update </button>
                                    <a href="{{ route('backend.faqs.index') }}" class="btn btn-secondary"> Back
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
    CKEDITOR.replace("txAnswer", {
        removeButtons: "Strike,Subscript,Superscript,Anchor,Styles,Copy,Paste,Cut",
        removePlugins: "about,image,pastefromword,pastetext,scayt,wsc,blockquote",
    });
</script>

@endpush
