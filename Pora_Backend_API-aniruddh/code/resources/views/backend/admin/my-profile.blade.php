@extends('backend.layouts.main')
<!--- Breadcrumb ---->
@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">{{ 'My Profile' }}</li>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">My Profile</h3>
            </div>
            <div class="card-body">
                <div class="user-master-update">
                    <div class="user-master-form">
                        <section class="content container-fluid">
                            <form action="{{ route('backend.profile') }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="box box-info">
                                            <div class="box-body">
                                                <div class="new-profile-body">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-6">
                                                            <div class="form-group">
                                                                <div class="profile-img-new">
                                                                    <div class="circle">
                                                                        <!-- User Profile Image -->
                                                                        <img class="profile-pic"
                                                                            src="{{ !empty($model->vImage) ? url('storage/uploads/'.$model->vImage) : url('theme/dist/img/user_placeholder.png')  }}"
                                                                            alt="">
                                                                    </div>
                                                                    <div class="p-image">
                                                                        <i class="fa fa-camera upload-button"></i>
                                                                        <input class="file-upload" type="file"
                                                                            name="vImage" />
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <span
                                                                            class="text-danger">{{ $errors->first('vImage') }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group field-username">
                                                                <label class="control-label"
                                                                    for="adminmaster-username">Name <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Name" name="vName"
                                                                    value="{{ old('vName',$model->vName) }}">
                                                                <div class="mb-3">
                                                                    <span
                                                                        class="text-danger">{{ $errors->first('vName') }}</span>
                                                                </div>

                                                            </div>
                                                            <div class="form-group field-vEmail ">
                                                                <label class="control-label"
                                                                    for="adminmaster-vEmail">Email <span class="text-danger">*</span></label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Email" name="vEmail"
                                                                    value="{{ old('vEmail',$model->vEmail) }}">
                                                                    <strong> *This Email will be used to receieve emails* </strong>
                                                                <div class="mb-3">
                                                                    <span
                                                                        class="text-danger">{{ $errors->first('vEmail') }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <button type="submit"
                                                                    class="btn btn-info">Update Profile</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>
@stop

@push('script')
    <script src="{{ asset('admin_assets/js/my-profile.js') }}"></script>
@endpush
