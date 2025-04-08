@extends('backend.layouts.main')
@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">{{ VERSION_LABEL }}</li>
@endsection
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3>Update Version </h3>
            </div>
            <div class="card-body">
                <form action="{{ route("backend.saveNewVersion") }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-5">
                            <label for="updateVesion">Current version : </label>
                            <input type="number" name="fVersion" step='0.1' id="updateVesion" class="form-control" value="{{ $version }}">
                        </div>
                        <div class="col-5">
                            <label for="vMsg">Message : </label>
                            <input type="text" name="vMessage" id="vMsg" class="form-control">
                        </div>
                        <div class="col-2">
                            <input type="submit"  class='form-control mt-4 btn btn-success' name="update" value="Update">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
