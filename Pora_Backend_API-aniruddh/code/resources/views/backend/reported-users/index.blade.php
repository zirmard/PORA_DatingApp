@extends('backend.layouts.main')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">{{ REPORT_REASON_LABEL }} List</li>
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="col-sm-6">
                        <h3 class="card-title">{{ REPORTED_USERS }}</h3>
                    </div>
                </div><!-- /.row -->
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="js-reported-users-table"
                            class="table table-bordered table-striped table-hover reported-users-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Reported By</th>
                                    <th>Reason</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@push('script')
<script>
    function initializeTable(elementSelector) {
        let domHtml = "<'row'<'col-sm-12 col-md-6'l>";
        domHtml += "<'col-sm-12 col-md-6 text-right'B>>";
        domHtml += "<'row'<'col-sm-12'tr>>";
        domHtml += "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>";

        $(elementSelector).on('preXhr.dt', function (e, settings, data) {
            if (settings.jqXHR) {
                settings.jqXHR.abort(); // cancel previous ajax request
            }
        }).DataTable({
            dom: domHtml,
            serverSide: true,
            "bLengthChange": false,
            "pageLength": 10,

            language: {
                search: '<span>Filter:</span> _INPUT_',
                searchPlaceholder: 'Search Attributes...',
                paginate: {
                    next: '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
                    previous: '<i class="fa fa-angle-double-left" aria-hidden="true"></i>',
                },
                processing: '<i class="fa fa-spinner fa-spin"></i> Loading...',
                emptyTable: "No record found.",
                zeroRecords: "No record found.",
            },
            ajax: {
                url: "{{ route('backend.reported-users.index') }}",
                beforeSend: function () {
                    $('#js-reported-users-table tbody').html(
                        `<tr class="text-center">
                      <td colspan="${$(elementSelector).find('th').length}">
                          <i class="fa fa-spinner fa-spin"></i> Loading...
                      </td>
                  </tr>`
                    );
                },
                data: function (d) {
                    d.search = $('input[type="search"]').val()
                },
                complete: function () {
                    //initializeActivateFeature.init();
                }
            },
            order: [
                // [1, "desc"]
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    'orderable': false,
                    'searchable': false
                },
                {
                    data: 'vFullname'
                },
                {
                    data: 'vReportedFullname'
                },
                {
                    data: 'vReportReason'
                },
                {
                    data: 'action',
                    'orderable': false,
                    'searchable': false
                },
            ],
            deferRender: true,
            initComplete: function () {
                var r = $('#js-reported-users-table tfoot tr');
                $('#js-reported-users-table thead').append(r);

                var cnt = 0;
                this.api().columns().every(function () {
                    if ($.inArray(cnt, [1,2,3]) !== -1) {
                        var column = this;
                        var input = document.createElement("input")
                        input.className = 'form-control form-control-sm';
                        $(input).appendTo($(column.footer()).empty())
                            .on({
                                'input': function () {
                                    column.search($(this).val(), false, false, true).draw();
                                },
                                'click': function (e) {
                                    e.stopPropagation()
                                }
                            });
                    }
                    cnt++;
                });

            },
            drawCallback: function () {
                $('.paginate_button').addClass('page-item')
                $('.paginate_button > i').addClass('page-link')
                $('.dataTables_filter').addClass('d-flex justify-content-end')
                $('.dataTables_info').addClass('d-flex justify-content-start')
                $('.dataTables_paginate').addClass('d-flex justify-content-end')
                $('#js-reported-users-table_paginate span').addClass('page-item').css('display', 'flex')
                $('#js-reported-users-table_paginate span > a').addClass('page-link')
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initializeTable('#js-reported-users-table');
    });
</script>
@endpush

