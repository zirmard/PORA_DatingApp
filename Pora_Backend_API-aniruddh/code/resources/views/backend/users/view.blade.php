@extends('backend.layouts.main')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">User view</li>
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- About Me Box -->
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">User Details</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-striped table-fit">
                                <tr>
                                    <td>
                                        <label>User Name : </label>
                                    </td>
                                    <td>
                                        <span>{{ $user->vFirstName }} {{ $user->vLastName }}</span>
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
                                        <span>{{ $user->vEmailId ?? '-' }}</span>
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
                                        <span>{{ ($user->vISDCode.' '.$user->vMobileNumber) ?? '-' }}</span>
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
                                    <td><label style="{{ ($user->tiIsActive==1) ? 'color:green' : 'color:red' }}">{{ (!empty($user->tiIsActive)) ? (($user->tiIsActive == 1) ? 'Active' :'') : (($user->tiIsActive == 0) ? 'Inactive' : '') }}</label></td>
                                </tr>
                                <tr>
                                    <td><label>Approved</label></td>
                                    <td><label style="{{ ($user->tiIsAdminApproved==1) ? 'color:green' : 'color:red' }}">{{ (!empty($user->tiIsAdminApproved)) ? (($user->tiIsAdminApproved == 1) ? 'Approved' : '') : (($user->tiIsAdminApproved == 0) ? 'Unapproved' : '') }}</label></td>
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
    <!-- /.content -->
@endsection
