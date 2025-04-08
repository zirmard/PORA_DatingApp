@extends('backend.layouts.main')
@php ($title = 'Contact Reason Details')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('backend.contact-reasons.index') }}">{{ CONTACT_REASON_LABEL }}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
@stop


<!-- Page content --->
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $title }}</h3>
            </div>
            <div class="card-body">
                <div class="dt-responsive">
                    <table id="datatable" class="table table-striped table-bordered detail-view">
                        <tr>
                            <th style="width: 300px;">Contact Reason</th>
                            <td>{{ $model->vContactReason }}</td>
                        </tr>
                        <tr>
                            <th style="width: 300px;">Created at</th>
                            <td>{{ $model->tsCreatedAt->format('Y-m-d')}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
