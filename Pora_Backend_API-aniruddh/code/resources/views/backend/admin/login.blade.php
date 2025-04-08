@extends('backend.layouts.guest')

@php ($title = 'Login')
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

        <p class="login-box-msg">Sign in to start your session</p>
        <form action="{{ route('backend.dologin') }}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="iTimezoneOffset" id="iTimezoneOffset">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Email" name="vEmail"
                    value="{{ old('vEmail',$model->vEmail) }}">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <span class="text-danger">{{ $errors->first('vEmail') }}</span>
            </div>

            <div class="input-group">
                <input type="password" class="form-control" placeholder="Password" name="vPassword" id="password">
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span toggle="#password" class="fa fa-fw field-icon toggle-password fa-eye-slash"
                            data-id="password"></span>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <span class="text-danger">{{ $errors->first('vPassword') }}</span>
                @if ($message = Session::get('error'))
                <span class="text-danger">{{ $message }} </span>
                @endif
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="icheck-primary">
                        <input type="checkbox" id="remember" name="remember_me">
                        <label for="remember">
                            Remember Me
                        </label>
                    </div>

                </div>
                <div class="col-6" style="margin-top:5px;left: 20px;">
                    <a href="{{ route('backend.forgot_password_form') }}">
                        <span>Forgot Password?</span>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
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
