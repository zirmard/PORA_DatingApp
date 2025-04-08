@extends('backend.layouts.main')
@push('css')
<link rel="stylesheet" href="{{ asset('theme/plugins/select2/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('theme/plugins/select2/select2-bootstrap4.min.css') }}">
<style>
    #circle
    {
    border-radius:50% 50% 50% 50%;
    width:150px;
    height:150px;
    }
    </style>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">User Edit</li>
@endsection
<?php

$msg1=$msg2=$msg3=$msg4='';
if($errors->has('vFirstName'))
    $msg1 = $errors->first('vFirstName');
if($errors->has('vLastName'))
    $msg2 = $errors->first('vLastName');
if($errors->has('vEmailId'))
    $msg3 = $errors->first('vEmailId');
if($errors->has('vMobileNumber'))
    $msg4 = $errors->first('vMobileNumber');
?>
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- About Me Box -->
            <div class="col-md-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit : {{ $user->vFirstName.' '.$user->vLastName }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{ route('backend.user_update',['vUserUuid' => $user->vUserUuid]) }}" method="POST">
                            @csrf
                        <table class="table table-striped table-fit">
                            <tr>
                                <td>
                                    <label>First Name : </label>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="vFirstName" value="{{ old('vFirstName',$user->vFirstName) }}" >
                                    @if($errors->has('vFirstName'))
                                        <span class="text-danger">{{ $errors->first('vFirstName') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Last Name : </label>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="vLastName" value="{{ old('vLastName',$user->vLastName) }}" >
                                    @if($errors->has('vLastName'))
                                        <span class="text-danger">{{ $errors->first('vLastName') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Gender: </label>
                                </td>
                                <td>
                                    <span>{{ $user->tiGender == 1 ? 'Male' : (($user->tiGender==2) ? 'Female':'N/A') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td><label>Profile image : </label></td>
                                <td>
                                    @if(!empty($profile_url))
                                        <img src="{{ $profile_url }}" width="100px" class="mt-2" height="100px" alt="">
                                    @else
                                        <span>Not available</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><label>Selfie image : </label></td>
                                <td>
                                    @if(!empty($selfie_url))
                                            <img src="{{ $selfie_url }}" width="100px" class="mt-2" height="100px" alt="">
                                    @else
                                        <span>Not available</span>
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <label>Email id : </label>
                                </td>
                                <td>
                                    <input type="email" class="form-control" name="vEmailId" value="{{ old('vEmailId',$user->vEmailId) }}" id="">
                                    @if($errors->has('vEmailId'))
                                        <span class="text-danger">{{ $errors->first('vEmailId') }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Date of Birth : </label>
                                </td>
                                <td>
                                    <span>{{ date('d-m-Y', $user->iDob) }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Mobile Number : </label>
                                </td>
                                <td>
                                    <div class="row">
                                        <div class="col-3">
                                            <select name="vISDCode" class="form-control" id="searchIsdcode">

                                                @if($user->vISDCode =='')
                                                    <option value="" {{ old('vISDCode') ? 'selected' : '' }}></option>
                                                @endif
                                                @foreach($countries as $key=>$value)
                                                    <option value="{{ '+'.$value['vDialingCode'] }}" {{ '+'.$value['vDialingCode']==old('vISDCode',$user->vISDCode) ? 'selected' : '' }}>{{ '+'.$value['vDialingCode'] }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="col-9">
                                            <input type="number" name="vMobileNumber" class="form-control" value="{{ old('vMobileNumber',$user->vMobileNumber) }}">

                                        </div>
                                </div>
                                            @if($errors->has('vISDCode'))
                                                <span class="text-danger">{{ $errors->first('vISDCode') }}</span>
                                            @endif
                                            @if($errors->has('vMobileNumber'))
                                                <span class="text-danger">{{ $errors->first('vMobileNumber') }}</span>
                                            @endif
                                </td>
                            </tr>
                            <tr>
                                <td><label>Origin country : </label></td>
                                <td><span>{{ $user->vOriginCountry ?? '-' }}</span></td>
                            </tr>
                            <tr>
                                <td><label>Living country : </label></td>
                                <td><span>{{ $user->vLivingCountry ?? '-' }}</span></td>
                            </tr>
                            <tr>
                                <td><label>City : </label></td>
                                <td><span>{{ $user->vCity ?? '-' }}</span></td>
                            </tr>
                            <tr>
                                <td><label>Faith : </label></td>
                                <td><span>{{ $user->vFaith ?? '-' }}</span></td>
                            </tr>
                            <tr>
                                <td><label>Relationship status :
                                    </label></td>
                                <td><span>{{ $user->tiRelationshipStatus == 1 ? 'Single' : ($user->tiRelationshipStatus == 2 ? 'Commited' : '') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label>Premium User : </label>
                                </td>
                                <td>
                                    <span>{{ $user->tiIsPremiumUser == 1 ? 'Yes' : 'No' }}</span>
                                </td>
                            </tr>
                            @if($user->tiIsPremiumUser == 1 && !empty($user->dtSubscriptionEndDate))
                                {{-- <tr>
                                    <td>
                                        <label>Premium Type : </label>
                                    </td>
                                    <td>
                                        <span>{{ (!empty($user->vPlanType)) ? $user->vPlanType : '-' }}</span>
                                    </td>
                                </tr> --}}
                                <tr>
                                    <td>
                                        <label>Premium Start date : </label>
                                    </td>
                                    <td>
                                        <span>{{ (!empty($user->dtSubscriptionStartDate)) ? date('d-m-Y',$user->dtSubscriptionStartDate) : '-' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Premium Expiry date : </label>
                                    </td>
                                    <td>
                                        <span>{{ (!empty($user->dtSubscriptionEndDate)) ? date('d-m-Y',$user->dtSubscriptionEndDate) : '-' }}</span>
                                    </td>
                                </tr>
                                @endif

                            <tr>
                                <td><label>Status</label></td>
                                <td>
                                    <select name="tiIsActive" class="form-control">
                                        @foreach(STATUS as $key => $val)
                                            <option value="{{ $key }}" {{ $key==old('tiIsActive',$user->tiIsActive) ? 'selected' : '' }}>{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </td>

                            </tr>
                            <tr>
                                <td><label>Approved</label></td>
                                <td>
                                    <select name="tiIsAdminApproved" class="form-control" {{ $user->tiIsAdminApproved==1 ? 'disabled':'' }}>
                                        @foreach(ADMIN_APPROVE_STATUS as $key => $val)
                                            <option value="{{ $key }}" {{ $key==old('tiIsAdminApproved',$user->tiIsAdminApproved) ? 'selected' : '' }} >{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <button type="submit" style="border-radius:3px 3px 3px 3px !important" class="btn btn-primary col-12">Update</button>
                                </td>
                            </tr>

                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-6">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Basic information</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-striped">
                                    <tr>
                                        <td style="width: 30%"><label>Verified Video :</label></td>
                                        <td>
                                        @if(!empty($video_url))
                                            <video src="{{ $video_url ?? '' }}" height="200px" width="200px"></video>
                                        @else
                                            <span>Not available</span>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>Looking For : </label>
                                        </td>
                                        <td>
                                            <span>{{ $user->tiLookingFor == 1 ? 'Love' : ($user->tiLookingFor == 2 ? 'Friendship' : ($user->tiLookingFor == 3 ? 'Both' : '')) }}</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td><label>Zodiac sign name : </label></td>
                                        <td><span>{{ $user->vZodiacSignName ? : '-' }}</span></td>
                                    </tr>

                                    <tr>
                                        <td><label>Ethnic group : </label></td>
                                        <td><span>{{ $user->vEthnicGroup ?? '-' }}</span></td>
                                    </tr>

                                    <tr>
                                        <td><label>Love language :
                                            </label>
                                        </td>
                                        <td style="word-break: break-all;">
                                            <?php
                                                    if(!empty($userLoveLanguage->loveLanguage)) {
                                                        echo $userLoveLanguage->loveLanguage;
                                                    }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Interests :</label></td>
                                        <td style="word-break: break-all;"><?php
                                            if(!empty($UserInterests->interest)) {
                                                echo $UserInterests->interest;
                                            }
                                        ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Occupation : </label></td>
                                        <td><span>{{ $user->vOccupation }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Earnings : </label></td>
                                        <td><span>{{ $user->vEarnings }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><label>Do drink : </label></td>
                                        <td><span>{{ $user->tiIsDrink == 1 ? 'Never' : ($user->tiIsDrink == 2 ? 'Occasionally' : ($user->tiIsDrink == 3 ? 'Regular' : '')) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Use drugs : </label></td>
                                        <td><span>{{ $user->tiUseDrugs == 1 ? 'Never' : ($user->tiUseDrugs == 2 ? 'Occasionally' : ($user->tiUseDrugs == 3 ? 'Regular' : '')) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Believe in marriage : </label></td>
                                        <td><span>{{ $user->tiBelieveInMarriage == 1 ? 'Yes' : ($user->tiBelieveInMarriage == 2 ? 'No' : ($user->tiItiBelieveInMarriagesDrink == 3 ? 'Indifferent' : '')) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label> Have kids : </label></td>
                                        <td><span>{{ $user->tiHaveKids == 1 ? 'yes' : ($user->tiHaveKids == 2 ? 'No' : '') }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label>Education qualification : </label></td>
                                        <td><span>{{ $user->vEducationQualification }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><label>About self : </label></td>
                                        <td><span>{{ $user->txAboutYourSelf }}</span></td>
                                    </tr>
                                    <tr>
                                        <td><label>Deal breaker : </label></td>
                                        <td><span>{{ $user->txDealBreaker }}</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Preference Details</h3>
                    </div>

                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-striped">
                            {{ customTableRow('Do you prefere same ethnicity ?',YES_NO,$user->tiSameEthnicity) }}
                            {{ customTableRow('Do you prefere same nationallity ?',YES_NO,$user->tiSameNationality) }}
                            {{ customTableRow('Prefered earning in year :','',$user->vPreferredEarnings) }}
                            {{ customTableRow('Do you like to have kids ?',YES_NO,$user->tiLikeToHaveKids) }}
                            {{ customTableRow('What education would you prefere ?',EDUCATION_ARRAY,$user->tiPreferredEducation) }}
                            {{ customTableRow('Do you prefere previously married ?',CHOICE_ARRAY,$user->tiPreferredPreviouslyMarried) }}
                            {{ customTableRow('Do you mind your partner drinks ?',ADDICTION_ARRAY,$user->tiIsDrinkingPreferred) }}
                            {{ customTableRow('Do you mind your partner uses recreational drugs ?',ADDICTION_ARRAY,$user->tiIsDrugPreferred) }}
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
@endsection

@push('script')
<script src="{{ asset('theme/plugins/select2/select2.full.min.js') }}"></script>
<script>
    $('#searchIsdcode').select2();
</script>
@endpush
