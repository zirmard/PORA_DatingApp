@extends('backend.layouts.main')
{{-- @section('title', REPORT_REASON_LABEL) --}}
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('backend.report-reasons.index') }}">Back</a></li>
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
                <div class="card-header">{{ __('Add Report Reason') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('backend.report-reasons.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vReportReason">Report Reason<span
                                        class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="vReportReason"
                                        placeholder="Enter Report Reason" value="{{ old('vReportReason') }}">
                                    @if ($errors->has('vReportReason'))
                                    <span class="text-danger">{{ $errors->first('vReportReason') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <button class="btn btn-primary"> Create </button>
                                    <a href="{{ route('backend.report-reasons.index') }}" class="btn btn-secondary"> Back
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
