<!--- Main layout ---->
@extends('backend.layouts.main')

<!--- Breadcrumb ---->
@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">{{ CHANGE_PASSWORD_LABEL }}</li>
@stop

<!--- Page content ---->
@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ CHANGE_PASSWORD_LABEL }}</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('backend.change_password') }}" autocomplete="off">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="vCurrentPassword">Current Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="vCurrentPassword" name="vCurrentPassword" placeholder="Enter Current Password" value="{{ old('vCurrentPassword') }}">
                        <span toggle="#vCurrentPassword" class="fa fa-fw fa-eye-slash admin-field-icon1 toggle-password" data-id="vCurrentPassword"></span>
                        <span class="text-danger">{{ $errors->first('vCurrentPassword') }}</span>
                    </div>

                    <div class="form-group">
                        <label for="vNewPassword">New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="vNewPassword" name="vNewPassword" placeholder="Enter New Password" value="{{ old('vNewPassword') }}">
                        <span toggle="#vNewPassword" class="fa fa-fw fa-eye-slash admin-field-icon1 toggle-password" data-id="vNewPassword"></span>
                        <span class="text-danger">{{ $errors->first('vNewPassword') }}</span>
                    </div>

                    <div class="form-group">
                        <label for="vConfirmPassword">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="vConfirmPassword" name="vConfirmPassword" placeholder="Confirm Password" value="{{ old('vConfirmPassword') }}">
                        <span toggle="#vConfirmPassword" class="fa fa-fw fa-eye-slash admin-field-icon1 toggle-password" data-id="vConfirmPassword"></span>
                        <span class="text-danger">{{ $errors->first('vConfirmPassword') }}</span>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Change Password">
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@push('script')
    <script src="{{ asset('admin_assets/js/toggle-password-script.js') }}"></script>
@endpush


