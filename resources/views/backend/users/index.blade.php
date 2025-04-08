@extends('backend.layouts.main')
@push('css')
    <style>
        .dataTables_filter,
        .dataTables_info,
        #DataTables_Table_0_length
         {
            display: none;
        }

        .scrollit {
            overflow: scroll;
        }

    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ MANAGE_USER_LABEL }}</li>
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mt-2">{{ MANAGE_USER_LABEL }}</h3>
                        <button class="btn btn-primary float-right" id="approveAllUser"  >Approve All</button>
                    </div><!-- /.row -->
                    <!-- /.card-header -->
                    <div class="card-body">
                            <table class="table table-bordered table-striped user-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Gender</th>
                                        <th>Premium User</th>
                                        <th>Status</th>
                                        <th>Approved</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot style="display: table-header-group">
                                    <td></td>
                                    <td><input type="text" class="form-control searchFirstname"></td>
                                    <td><input type="text" class="form-control searchLastname"></td>
                                    <td><input type="text" class="form-control searchEmail"></td>
                                    <td><select class="form-control searchGender">
                                        <option value='' ></option>
                                        <option value='1' {{ ($request_data['gender'] != '' && $request_data['gender']==1) ? 'selected' :'' }} >male</option>
                                        <option value='2' {{ ($request_data['gender'] != '' && $request_data['gender']==2) ? 'selected' :'' }}>female</option>
                                    </select></td>
                                    <td><select class="form-control searchPremium">
                                        <option value=''></option>
                                        <option value='1' {{ ($request_data['premiumUsers'] != '' && $request_data['premiumUsers']==1) ? 'selected' :'' }}>Yes</option>
                                        <option value='0' {{ ($request_data['basicUsers'] != '' && $request_data['basicUsers']==0) ? 'selected' :'' }}>No</option>
                                    </select></td>
                                    <td><select class="form-control searchStatus">
                                        <option value=''></option>
                                        <option value='1' {{ (($request_data['activeUsers'] != '' && $request_data['activeUsers']==1) || ($request_data['activeUsers'] == '' && $request_data['unapprovedUsers'] != '' && $request_data['unapprovedUsers'] ==0) ) ? 'selected' : '' }}>Active</option>
                                        <option value='0' {{ ($request_data['inactiveUsers'] != '' && $request_data['inactiveUsers']==0) ? 'selected' : '' }}>Inactive</option>
                                    </select></td>
                                    <td><select class="form-control searchIsApproved">
                                        <option value=''></option>
                                        <option value='1'>Approved</option>
                                        <option value='0' {{ ($request_data['unapprovedUsers'] != '' && $request_data['unapprovedUsers'] ==0) ? 'selected' :'' }}>Unapproved</option>
                                    </select></td>
                                    <td></td>
                                </tfoot>
                                <tbody>

                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
<script>
    $(document).on('click','#btnStatus',function(e){
    e.preventDefault();
    var btn = $(this);
    var isActive = btn.attr('isActive');
    var uuid = btn.attr('vUserUuid');
    $.ajax({
        type:"POST",
        dataType:"json",
        url:"{{ route('backend.checkStatus') }}",
        data:{
            'vUserUuid':uuid,
            'tiIsActive':isActive,
            '_token':'{{ csrf_token() }}'
        },
        success:function(res) {
            if(res.status) {
                btn.attr('isActive',res.new_status);
                if(res.new_status) {
                    btn.removeClass('btn-danger');
                    btn.addClass('btn-success');
                    btn.html("Active");
                } else {
                    btn.removeClass('btn-success');
                    btn.addClass('btn-danger');
                    btn.html("Inactive");
                }
                $.toast({
                    heading:'Success',
                    text:res.msg,
                    position:String('top-center'),
                    icon:'success',
                    loaderBg: '#f96868',
                    hideAfter: false,
                    hideAfter:3000
                });
            } else {
                $.toast({
                    heading:'Error',
                    text:res.msg,
                    position:String('top-center'),
                    icon:'danger',
                    hideAfter: false,
                    hideAfter:3000
                });
            }
        },
        error:function(res) {
            alert('error');
        }
    });
});
    $(document).on('click','#approveAllUser',function(e){
        e.preventDefault();
        $.ajax({
            type:'GET',
            url:'{{ route("backend.approveAllUsers") }}',
            dataType:'json',
            success:function(e){
                window.location.reload();
            }
        });
    });
    $(document).on('click','.userIsApproved',function(e){
    e.preventDefault();

    var btn = $(this);
    var isApproved = btn.attr("isApproved");
    if(isApproved==1) {
        $.toast({
            heading:'Success',
            text:'User already approved.',
            position:String('top-center'),
            icon: 'success',
            loaderBg: '#f96868',
            hideAfter: false,
            hideAfter:3000
        });
        return flase;
    }
    var vUserUuid = btn.attr("vUserUuid");
    // alert(vUserUuid+'::::::'+isApproved);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "{{ route('backend.adminApproved') }}",
        data: {
            'vUserUuid': vUserUuid,
            'tiIsAdminApproved': isApproved,
            '_token': '{{ csrf_token() }}'
        },
        success: function(res) {
            $.toast().reset('all');
            var html = '';
            if(res.status == true) {
                if(isApproved == 0) {
                    // html = '<button type="button" vUserUuid="'+vUserUuid+'" isApproved="1" class="userIsApproved btn btn-block btn-success btn-sm w-50" style="margin-top:0px !important;width:80% !important;">Approved</button>';
                    btn.attr('isApproved',1);
                    btn.removeClass('btn-danger');
                    btn.addClass('btn-success');
                    btn.html("Approved");
                }
                $.toast({
                    heading: 'Success',
                    text: 'User approved successfully',
                    position: String('top-center'),
                    icon: 'success',
                    loaderBg: '#f96868',
                    hideAfter: false,
                    hideAfter: 5000
                });
            } else {

            }
        },
        error:function(){
        }
    });

});

</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.24/datatables.min.js"></script>
    <script type="text/javascript">
        $(function() {

            var gen = "{{ $request_data['gender'] }}";
            var pendding = "{{ $request_data['unapprovedUsers'] }}";
            var active = "{{ $request_data['activeUsers'] }}";
            var inactive = "{{ $request_data['inactiveUsers'] }}";
            var premium = "{{ $request_data['premiumUsers'] }}";
            var basic = "{{ $request_data['basicUsers'] }}";
            var table = $('.user-table').DataTable({
                dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
                autoWidth: false,
                processing: true,
                serverSide: true,
                pageLength: 10,
                ajax: {
                    url: "{{ route('backend.manage-users.index') }}",
                    data: function(keyword) {
                        keyword.firstname = $('.searchFirstname').val();
                        keyword.lastname = $('.searchLastname').val();
                        keyword.email = $('.searchEmail').val();
                        keyword.gender = $('searchGender').val();
                        keyword.status = $('searchStatus').val();
                        keyword.premium = $('searchPremium').val();
                        keyword.isApproved = $('.searchIsApproved').val();
                        keyword.gender = (gen == '') ? $('.searchGender').val() : gen;
                        keyword.status = (active == '' && inactive == '') ? $('.searchStatus').val() : ((active != '') ? active : ((inactive != '') ? inactive : ''));
                        keyword.premium = (premium == '' && basic == '') ? $('.searchPremium').val() : ((premium != '') ? premium : ((basic != '') ? basic : ''));
                        keyword.isApproved = (pendding == '') ? $('.searchIsApproved').val() : pendding;
                        gen=pendding=active=inactive=premium=basic='';
                    }
                },
                stateSave: false,
                autoWidth: false,

                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex',orderable: false, searchable: false},
                    { data: 'vFirstName', name: 'vFirstName' },
                    { data: 'vLastName', name: 'vLastName' },
                    { data: 'vEmailId', name: 'vEmailId' },
                    { data: 'tiGender', data: 'tiGender' },
                    { data: 'tiIsPremiumUser', name: 'tiIsPremiumUser' },
                    { data: 'status', name: 'status' },
                    { data: 'tiIsAdminApproved', name: 'tiIsAdminApproved' },
                    { data: 'action', name: 'action', orderable: true, searchable: false }
                ]
            });
            $('.searchFirstname').keyup(function() {
                table.draw();
            });
            $('.searchLastname').keyup(function() {
                table.draw();
            });
            $('.searchEmail').keyup(function() {
                table.draw();
            });
            $('.searchGender').change(function() {
                table.draw();
            });
            $('.searchStatus').change(function() {
                table.draw();
            });
            $('.searchPremium').change(function(){
                table.draw();
            })
            $('.searchIsApproved').change(function() {
                table.draw();
            });
        });
    </script>

@endpush
