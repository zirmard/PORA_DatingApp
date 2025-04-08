@extends('backend.layouts.main')
{{-- @section('title', CONTACT_REASON_LABEL) --}}
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('backend.contact-reasons.index') }}">Back</a></li>
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
                <div class="card-header">{{ __('Add Contact Reason') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('backend.contact-reasons.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vContactReason">Contact Reason<span
                                        class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="vContactReason"
                                        placeholder="Enter Contact Reason" value="{{ old('vContactReason') }}">
                                    @if ($errors->has('vContactReason'))
                                    <span class="text-danger">{{ $errors->first('vContactReason') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button class="btn btn-primary"> Create </button>
                                    <a href="{{ route('backend.contact-reasons.index') }}" class="btn btn-secondary"> Back
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
