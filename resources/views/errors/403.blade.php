@extends('errors.error_layout')
@section('content')
    <h2 class="display-1">403</h2>
    <h5><i class="fa fa-warning text-yellow"></i> This action is unauthorized.</h5>
    <span><br></span>
    <a href="javascript:history.back()" class="btn btn-warning btn-sm"> <i class="fas fa-arrow-left"></i> Go Back</a>
@stop