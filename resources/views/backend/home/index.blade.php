@extends('backend.layouts.main')
<!-- Page content --->
@section('content')
<div class="row">
    <div class="col-lg-3 col-xs-6" id="totalUsers">
        <div class="small-box bg-info">
            <div class="inner text-left">
                <h3><?= $data['totalUsers']; ?></h3>
                <p>Total Users</p>
            </div>
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
            {{-- <a href="{{ route('backend.users.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> --}}
        </div>
    </div>
    <div class="col-lg-3 col-xs-6" id="totalMans">
        <div class="small-box bg-info">
            <div class="inner text-left">
                <h3><?= $data['totalMans']; ?></h3>
                <p>Total Mans</p>
            </div>
            <div class="icon">
                <i class="fa fa-male"></i>
            </div>
            {{-- <a href="{{ route('backend.users.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> --}}
        </div>
    </div>
    <div class="col-lg-3 col-xs-6" id="totalWomans">
        <div class="small-box bg-info">
            <div class="inner text-left">
                <h3><?= $data['totalWomans']; ?></h3>
                <p>Total Womans</p>
            </div>
            <div class="icon">
                <i class="fa fa-female"></i>
            </div>
            {{-- <a href="{{ route('backend.users.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> --}}
        </div>
    </div>
    <div class="col-lg-3 col-xs-6" id="totalPenddingUsers">
        <div class="small-box bg-info">
            <div class="inner text-left">
                <h3><?= $data['totalPendingUsers']; ?></h3>
                <p>Pendding User</p>
            </div>
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
            {{-- <a href="{{ route('backend.users.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> --}}
        </div>
    </div>
    <div class="col-lg-3 col-xs-6" id="totalActiveUsers">
        <div class="small-box bg-info">
            <div class="inner text-left">
                <h3><?= $data['totalActiveUsers']; ?></h3>
                <p>Active Users</p>
            </div>
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
            {{-- <a href="{{ route('backend.users.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> --}}
        </div>
    </div>
    <div class="col-lg-3 col-xs-6" id="totalInactiveUsers">
        <div class="small-box bg-info">
            <div class="inner text-left">
                <h3><?= $data['totalInactiveUsers']; ?></h3>
                <p>Inactive users</p>
            </div>
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
            {{-- <a href="{{ route('backend.users.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> --}}
        </div>
    </div>
    <div class="col-lg-3 col-xs-6" id="totalPremiumUsers">
        <div class="small-box bg-info">
            <div class="inner text-left">
                <h3><?= $data['totalPremiumUsers']; ?></h3>
                <p>Total Premium Users</p>
            </div>
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
            {{-- <a href="{{ route('backend.users.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> --}}
        </div>
    </div>
    <div class="col-lg-3 col-xs-6" id="totalBasicUsers">
        <div class="small-box bg-info">
            <div class="inner text-left">
                <h3><?= $data['totalBasicUsers']; ?></h3>
                <p>Total Basic Users</p>
            </div>
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
            {{-- <a href="{{ route('backend.users.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> --}}
        </div>
    </div>

    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-gradient-danger">
            <div class="inner text-left">
                <h3><?= $data['totalReportedUsers']; ?></h3>
                <p>Total Reported Users</p>
            </div>
            <div class="icon">
                <i class="fa fa-user"></i>
            </div>
            {{-- <a href="{{ route('backend.reported-users.index') }}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> --}}
        </div>
    </div>

</div>
@stop
@push('script')
<script>
    $('#totalUsers').click(function(){
        window.location="{{ route('backend.manage-users.index') }}";
    });
    $('#totalMans').click(function(){
        window.location="{{ route('backend.manage-users.index',['dashboardThroughGender'=>1]) }}";
    });
    $('#totalWomans').click(function(){
        window.location="{{ route('backend.manage-users.index',['dashboardThroughGender'=>2]) }}";
    });
    $('#totalPenddingUsers').click(function(){
        window.location="{{ route('backend.manage-users.index',['unapprovedUsers'=>0]) }}";
    });
    $('#totalActiveUsers').click(function(){
        window.location="{{ route('backend.manage-users.index',['activeUsers'=>1]) }}";
    });
    $('#totalInactiveUsers').click(function(){
        window.location="{{ route('backend.manage-users.index',['inactiveUsers'=>0]) }}";
    })
    $('#totalPremiumUsers').click(function(){
        window.location="{{ route('backend.manage-users.index',['premiumUsers'=>1])  }}";
    })
    $('#totalBasicUsers').click(function(){
        window.location="{{ route('backend.manage-users.index',['basicUsers'=>0]) }}";
    })
</script>
@endpush
