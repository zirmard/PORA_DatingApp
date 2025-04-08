@extends('backend.layouts.guest')

@php ($title = 'Forgot Password')
@section('content')

<div class="card">
    <div class="card-body login-card-body">
        @if(Session::has('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ Session::get('success') }}</strong>
        </div>
        @endif
        @if(Session::has('password_success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ Session::get('password_success') }}</strong>
        </div>
        @endif

        <form action="{{ route('backend.forgot_password') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="iTimezoneOffset" id="iTimezoneOffset">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Email" name="vEmail"
                    value="{{ old('vEmail') }}">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <span class="text-danger">{{ $errors->first('vEmail') }}</span>
            </div>

            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">Send</button>
                </div>
            </div>
        </form>

    </div>
</div>
@stop

@push('script')
<script type="application/javascript">
    var time = new Date();
    var timezone_offset = -(time.getTimezoneOffset() * 60);
    $("#iTimezoneOffset").val(timezone_offset);
</script>
@endpush
