@extends('backend.layouts.main')
@php ($title = $model->vPageName)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('backend.content_page.index') }}">{{ CONTENT_PAGE_LABEL }}</a></li>
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
                            <th style="width: 300px;">Slug</th>
                            <td>{{ $model->vSlug }}</td>
                        </tr>
                        <tr>
                            <th>Title</th>
                            <td>{{ $model->vPageName }}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{!! $model->txContent !!}</td>
                        </tr>
                        <tr>
                            <th style="width: 300px;">Updated at</th>
                            <td>{{ date('Y-m-d h:i A', $model->iUpdatedAt) }}</td>
                        </tr>
                        <tr>
                            <th style="width: 300px;">Created at</th>
                            <td>{{ date('Y-m-d h:i A', $model->iCreatedAt) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
