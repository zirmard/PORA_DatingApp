@extends('backend.layouts.main')
@php ($title = 'Reported Users')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('backend.report-reasons.index') }}">{{ REPORTED_USERS }}</a></li>
<li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
@stop

<!-- Page content --->
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $title }}</h3>
                <div class="text-right">
                    <a  href="javascript:void(0)" data-href="{{ route('backend.reported-users.destroy', $model->vUserReportUuid) }}" class="btn btn-danger btn-sm delete-record delete-report-reason">De Activate</a>
                </div>
            </div>
            <div class="card-body">
                <div class="dt-responsive">
                    <table id="datatable" class="table table-striped table-bordered detail-view">
                        <tr>
                            <th style="width: 300px;">Name</th>
                            <td>{{ $model->vFullname }}</td>
                        </tr>
                        <tr>
                            <th style="width: 300px;">Reported By</th>
                            <td>{{ $model->vReportedFullname }}</td>
                        </tr>
                        <tr>
                            <th style="width: 300px;">Reason</th>
                            <td>{{ $model->vReportReason }}</td>
                        </tr>
                        <tr>
                            <th style="width: 300px;">Description</th>
                            <td>{{ $model->txDetails }}</td>
                        </tr>
                        <tr>
                            <th style="width: 300px;">Date & Time</th>
                            <td>{{ $model->tsCreatedAt->format('Y-m-d')}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body p-4 py-5 text-center">
                <p class="text-center">
                    <img src="{{ asset('admin_assets/images/modal-delete.svg')  }}">
                </p>
                <h4 class="bold mt-3">Deactivate Reported User</h4>
                <p class="mb-3">Are you sure you want to deactivate this User?</p>
                <div class="row">
                    <div class="col-md-6 mx-auto">
                        <form action="" method="post" id="form_delete">
                        <a href="javascript:void(0)" class="btn btn-outline-secondary" data-dismiss="modal" aria-label="Close">{{ __('Close') }}</a>
                            <input class="btn btn-danger" type="submit" value="DeActivate" />
                            @method('delete')
                            @csrf
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('script')
<script type="text/javascript">
    $(document).on('click', '.delete-report-reason', function () {
        $('#delete-modal').find('#form_delete').attr('action', $(this).data('href'));
        $('#delete-modal').modal('show');
    });

    $('#delete-modal').on('hidden.bs.modal', function () {
        $(this).find('#btnDelete').attr('href', '#');
    });
</script>
@endpush
