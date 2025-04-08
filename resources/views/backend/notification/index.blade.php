@extends('backend.layouts.main')
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ SEND_NOTIFICATION }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="pl-4 mt-3">Create notification</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('backend.send_to_user_notification') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Title<i class="text-danger">*</i></label>
                                    <input type="text" name="vTitle" id="title" class="form-control" value="{{ old('vTitle') }}">
                                    @if ($errors->has('vTitle'))
                                        <span class="text-danger">{{ $errors->first('vTitle') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vMessage">Message<i class="text-danger">*</i></label>
                                    <textarea name="vMessage" id="vMessage" class="form-control ckeditor" rows="3">{{ old('vMessage') }}</textarea>
                                    @if ($errors->has('vMessage'))
                                        <span class="text-danger">{{ $errors->first('vMessage') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="type">Notification Type</label><br>
                            <input type="radio" name="iType" id="type1" value="1" class="form-input pl-5" checked ><label for="type1" class="pl-3">send a notification to all users</label><br>
                            <input type="radio" name="iType" id="type2" value="2" class="form-input pl-5"><label for="type2" class="pl-3">send a notification to basic pack users about upgrading to the premium pack</label><br>
                        </div>
                            <input type="submit" class="btn btn-primary" value="Send">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
