@extends('backend.layouts.main')
@php ($title = 'FAQ Details')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('backend.faqs.index') }}">{{ FAQ_LABEL }}</a></li>
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
                            <th style="width: 300px;">Question Category</th>
                            <td>{{ $model->vQuestionCategory }}</td>
                        </tr>
                        <tr>
                            <th style="width: 300px;">Question</th>
                            <td>{{ $model->vQuestion }}</td>
                        </tr>
                        <tr>
                            <th>Answer</th>
                            <td>{!! $model->txAnswer !!}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
