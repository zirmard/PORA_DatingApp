@extends('backend.layouts.guest')

@php ($title = 'Reset Password Form')
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

        <form action="{{ route('backend.reset_password') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="iTimezoneOffset" id="iTimezoneOffset">
            <input type="hidden" name="token" value="{{ $token }}" />

            <div class="input-group">
                <input type="password" class="form-control" placeholder="Password" name="vPassword" id="vPassword" value="{{ old('vPassword') }}">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span toggle="#vPassword" class="fa fa-fw field-icon toggle-password fa-eye-slash"
                            data-id="vPassword"></span>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <span class="text-danger">{{ $errors->first('vPassword') }}</span>
                @if ($message = Session::get('error'))
                <span class="text-danger">{{ $message }} </span>
                @endif
            </div>

            <div class="input-group">
                <input type="password" class="form-control" placeholder="Confirm Password" name="vConfirmPassword" id="vConfirmPassword" value="{{ old('vConfirmPassword') }}">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span toggle="#vConfirmPassword" class="fa fa-fw field-icon toggle-password fa-eye-slash"
                            data-id="vConfirmPassword"></span>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <span class="text-danger">{{ $errors->first('vConfirmPassword') }}</span>
                @if ($message = Session::get('error'))
                <span class="text-danger">{{ $message }} </span>
                @endif
            </div>

            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
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
