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
    $genderArray = array('','Male','Female');
    $lookingForArray = array('','Love','Friendship','Both');
    $choiceArray = array('','Yes','No','i am indifferent');
    $yes_no = array('','Yes','No');
    $statusArray = array('Inactive','Active');
    $approveArray = array('Unapproved','Approved');
    $educationArray =array('','high School Diploma','Bachelors Degree','Masters Degree','PHD');
    $relationshipArray =array('','Single','Commited');
    $addictionArray =array('','Never','Occasionally','Regular');
    $earningArray = array('Less than $10,000','$10,000-$30,000','$31,000-$50,000','$51,000-$70,000','$71,000-$99,000','$100,000-$10,00,000');
    $ZodiacSignArray = array('aries','taurus','gemini','cancer','leo','virgo','libra','scorpius','sagittarius','capricornus','aquarius','pisces','i dont know');
    $ethnicGroupArray =array('Algeria','Angola','Benin','Botswana','Burkina Faso','Burundi','Cabo Verde','Cameroon','Central African Republic (CAR)','Chad','Comoros','Congo Democratic Republic of the','Congo Republic of the','Cote d Ivoire','Djibouti','Egypt','Equatorial Guinea','Eritrea','Eswatini','Ethiopia','Gabon','Gambia','Ghana','Guinea','Guinea Bissau','Kenya','Lesotho','Liberia','Libya','Madagascar','Malawi','Mali','Mauritania','Mauritius','Morocco','Mozambique','Namibia','Niger','Nigeria','Rwanda','Sao Tome and Principe','Senegal','Seychelles','Sierra Leone','Somalia','South Africa','South Sudan','Sudan','Tanzania','Togo','Tunisia','Uganda','Zambia','Zimbabwe');
?>
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit : {{ $user->vFirstName }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form action="{{ route('backend.user_update',['vUserUuid' => $user->vUserUuid]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Profile Image : </label>
                                            {{-- <input type="file" class="form-control-file" name="vProfileImage"> --}}
                                            @if(!empty($profile_url))
                                                <img src="{{ $profile_url }}" id="circle" alt="">
                                            @else
                                                <span>Not available</span>
                                            @endif
                                            {{-- @if($errors->has('vProfileImage'))
                                                <span class="text-danger">{{ $errors->first('vProfileImage') }}</span>
                                            @endif --}}
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label>Selfie Image : </label>
                                            {{-- <input type="file" name="vSelfieImage" class="form-control-file"> --}}
                                            @if(!empty($selfie_url))
                                                <img src="{{ $selfie_url }}" id="circle" class="mt-2" alt="">
                                            @else
                                                <span>Not available</span>
                                            @endif
                                            {{-- @if($errors->has('vSelfieImage'))
                                                <span class="text-danger">{{ $errors->first('vSelfieImage') }}</span>
                                            @endif --}}
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="">Varified Video :</label>
                                            @if(!empty($video_url))
                                                <video src="{{ $video_url }}" width="100px" height="100px"></video>
                                            @else
                                                <span>Not available</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php
                                        $msg1=$msg2=$msg3=$msg4=$msg5=$msg6=$msg7=$msg8=$msg9=$msg10=$msg11=$msg12=$msg13=$msg14=$dob='';
                                        if($errors->has('vFirstName'))
                                            $msg1 = $errors->first('vFirstName');
                                        if($errors->has('vLastName'))
                                            $msg2 = $errors->first('vLastName');
                                        if($errors->has('vEmailId'))
                                            $msg3 = $errors->first('vEmailId');
                                        if($errors->has('vMobileNumber'))
                                            $msg4 = $errors->first('vMobileNumber');
                                        // if($errors->has('vOriginCountry'))
                                        //     $msg5 = $errors->first('vOriginCountry');
                                        // if($errors->has('vLivingCountry'))
                                        //     $msg6 = $errors->first('vLivingCountry');
                                        // if($errors->has('vCity'))
                                        //     $msg7 = $errors->first('vCity');
                                        // if($errors->has('vEthnicGroup'))
                                        //     $msg8 = $errors->first('vEthnicGroup');
                                        // if($errors->has('vFaith'))
                                        //     $msg9 = $errors->first('vFaith');
                                        // if($errors->has('vOccupation'))
                                        //     $msg10 = $errors->first('vOccupation');
                                        // if($errors->has('iDob'))
                                        //     $dob = $errors->first('iDob');
                                    ?>
                                    {{ customType(6,'InputFirstName','First name ','text','vFirstName',old('vFirstName',$user->vFirstName),'Enter first name',$msg1); }}

                                    {{ customType(6,'InputLastName','Last name ','text','vLastName',old('vLastName',$user->vLastName),'Enter last name',$msg2); }}
                                </div>
                                <div class="row">
                                    {{ customType(4,'InputEmail','Email id','email','vEmailId',old('vEmailId',$user->vEmailId),'Enter email address',$msg3) }}

                                    {{ customType(4,'InputMobile','Mobile Number','text','vMobileNumber',old('vMobileNumber',$user->vMobileNumber),'Enter Mobile number',$msg4) }}
                                    {{ customType(4,'InputDob','Date of Birth','date','iDob',old('iDob',date('Y-m-d',$user->iDob)),'',$dob) }}
                                </div>
                                <div class="row">
                                    {{-- // customSelect($col,$forId,$labelname,$selectname, $data,$selectedValue) --}}
                                    {{ customSelect(4,'gender','Select gender','tiGender',$genderArray,old('tiGender',$user->tiGender)) }}
                                    {{ customSelect(4,'lookingfor','Looking For','tiLookingFor',$lookingForArray,old('tiLookingFor',$user->tiLookingFor)) }}
                                    {{ customSelect(4,'relationshipStatus','Relationship Status','tiRelationshipStatus',$relationshipArray,old('tiRelationshipStatus',$user->tiRelationshipStatus)) }}
                                </div>
                                <div class="row">
                                    {{-- // customType($col,$forId,$labelname,$type,$typename,$value,$placehoder) --}}

                                    {{  customType(4,'originCountry','Origin country','text','vOriginCountry',old('vOriginCountry',$user->vOriginCountry),'Enter origin country',$msg5) }}

                                    {{ customType(4,'livingCountry','Living country','text','vLivingCountry',old('vLivingCountry',$user->vLivingCountry),'Enter living country',$msg6) }}

                                    {{  customType(4,'originCity','City','text','vCity',old('vCity',$user->vCity),'Enter city name',$msg7)}}
                                </div>
                                <div class="row">
                                    {{-- {{ customType(4,'ethnicGroupName','Ethnic group name','text','vEthnicGroup',old('vEthnicGroup',$user->vEthnicGroup),'Enter ethnic group',$msg8) }} --}}
                                    {{ customSelect(4,'ethnicGroupName','Ethnic group name','vEthnicGroup',$ethnicGroupArray,old('vEthnicGroup',$user->vEthnicGroup)) }}

                                    {{ customSelect(4,'ZodiacSignName','Zodiac sign name','vZodiacSignName',$ZodiacSignArray,old('vZodiacSignName',$user->vZodiacSignName)) }}

                                    {{ customType(4,'faith','Faith','text','vFaith',old('vFaith',$user->vFaith),'Enter Faith',$msg9) }}

                                </div>
                                <div class="row">
                                    {{ customType(6,'occupationName','Occupation ','text','vOccupation',old('vOccupation',$user->vOccupation),'Enter occupation',$msg10) }}

                                    <div class="col-6">
                                        <div class="form-group mt-4" >
                                            <label for="premium">Premium User : </label>
                                            <div class="form-check-inline">
                                                <label class="form-check-label">
                                                  <input type="radio" class="form-check-input" name="tiIsPremiumUser" <?= ($user->tiIsPremiumUser) ? 'chedked' : '' ?> disabled>Yes
                                                </label>
                                            </div>
                                            <div class="form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input" name="tiIsPremiumUser" <?= ($user->tiIsPremiumUser) ? 'checked' : '' ?> disabled >No
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="loveLanguage">Love Language : </label>
                                            <select name="vLoveLanguages[]" multiple id="love_language" style="width:100%;">
                                                @foreach ($allLoveLanguages as $key => $lang)
                                                    <option value="{{ $key+1 }}" {{ (in_array($lang['vLoveLanguage'],$userLoveLanguagesArray)) ? 'selected' : '' }}>
                                                    {{ $lang['vLoveLanguage'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="loveLanguage">Interest : </label>
                                            <select name="vInterestNames[]" multiple id="user_interests" style="width:100%;">
                                                @foreach ($allInterests as $key => $interest)
                                                    <option value="{{ $key+1 }}" {{ (in_array($interest['vInterestName'],$UserInterestsArray)) ? 'selected' : '' }}>
                                                    {{ $interest['vInterestName'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="aboutYourSelf">About self :</label>
                                                <textarea name="txAboutYourSelf" class="form-control" rows="3">{{ old('txAboutYourSelf',$user->txAboutYourSelf) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="aboutYourSelf">Deal Breaker :</label>
                                                <textarea name="txDealBreaker" class="form-control" rows="3">{{ old('txDealBreaker',$user->txDealBreaker) }}</textarea>
                                            </div>
                                        </div>
                                </div>

                                <div class="row">
                                    {{ customSelect(6,'sameEthnicity','Status','tiIsActive',$statusArray,old('tiIsActive',$user->tiIsActive)) }}
                                    {{ customSelect(6,'SameNationality','Approved','tiIsAdminApproved',$approveArray,old('tiIsAdminApproved',$user->tiIsAdminApproved)) }}
                                </div>
                                <div class="row">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <h3 >Preferences </h3>
                                        <hr/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <table class="table table-striped">
                                            {{ customTableRow('Do you prefere same ethnicity ?',$yes_no,$user->tiSameEthnicity) }}
                                            {{ customTableRow('Do you prefere same nationallity ?',$yes_no,$user->tiSameNationality) }}
                                            {{ customTableRow('Earning in year :','',$user->vEarnings) }}
                                            {{ customTableRow('Prefered earning in year :','',$user->vPreferredEarnings) }}
                                            {{ customTableRow('Do you belive in marriage ?',$yes_no,$user->tiBelieveInMarriage) }}
                                            {{ customTableRow('Do you have kids  ?',$yes_no,$user->tihavekids) }}
                                            {{ customTableRow('Do you like to have kids ?',$yes_no,$user->tiLikeToHaveKids) }}
                                            {{ customTableRow('What\'s your highest educational qualification ?','',$user->vEducationQualification) }}
                                            {{ customTableRow('What education would you prefere ?',$educationArray,$user->tiPreferredEducation) }}
                                            {{ customTableRow('Do you prefere previously married ?',$choiceArray,$user->tiPreferredPreviouslyMarried) }}
                                            {{ customTableRow('How often do you drink ?',$addictionArray,$user->tiIsDrink) }}
                                            {{ customTableRow('Do you mind your partner drinks ?',$addictionArray,$user->tiIsDrinkingPreferred) }}
                                            {{ customTableRow('Do you use recreational drugs ?',$addictionArray,$user->tiUseDrugs) }}
                                            {{ customTableRow('Do you mind your partner uses recreational drugs ?',$addictionArray,$user->tiIsDrugPreferred) }}
                                        </table>
                                    </div>
                                </div>

                                {{-- <div class="row">
                                    {{ customSelect(6,'sameEthnicity','Do you Prefere same ethnicity ','tiSameEthnicity',$choiceArray,old('tiSameEthnicity',$user->tiSameEthnicity)) }}
                                    {{ customSelect(6,'SameNationality','Do you prefere same nationallity','tiSameNationality',$choiceArray,old('tiSameNationality',$user->tiSameNationality)) }}
                                </div>
                                <div class="row">
                                    {{ customSelect(6,'erning','Earning in year','vEarnings',$earningArray,old('vEarnings',$user->vEarnings)) }}
                                    {{ customSelect(6,'earning','Looking prefered earning in year','vPreferredEarnings',$earningArray,old('vPreferredEarnings',$user->vPreferredEarnings)) }}
                                </div>

                                <div class="row">
                                    {{ customSelect(6,'beleiveInMarrige','Do you belive in marriage ','tiBelieveInMarriage',$choiceArray,old('tiBelieveInMarriage',$user->tiBelieveInMarriage)) }}
                                    {{ customSelect(6,'tiPreferredPreviouslyMarried','Do you prefere previously married ','tiPreferredPreviouslyMarried',$choiceArray,old('tiPreferredPreviouslyMarried',$user->tiPreferredPreviouslyMarried)) }}
                                </div>

                                <div class="row">

                                    {{ customSelect(6,'HaveKids1','Do you have kids ','tiHaveKids',$yes_no,old('tiHaveKids',$user->tiHaveKids)) }}
                                    {{ customSelect(6,'HaveKids2','Do you like to have kids ','tiLikeToHaveKids',$choiceArray,old('tiLikeToHaveKids',$user->tiLikeToHaveKids)) }}
                                </div>
                                <div class="row">
                                    {{ customSelect(6,'education1','What\'s your highest educational qualification','vEducationQualification',$educationArray,old('vEducationQualification',$user->vEducationQualification)) }}
                                    {{ customSelect(6,'education2','what education would you like','tiPreferredEducation',$choiceArray,old('tiPreferredEducation',$user->tiPreferredEducation)) }}
                                </div>
                                <div class="row">
                                    {{-- // customSelect($col,$forId,$labelname,$selectname, $data,$selectedValue)
                                    {{ customSelect(6,'drink1','How often do you drink ','tiIsDrink',$addictionArray,old('tiIsDrink',$user->tiIsDrink)) }}
                                    {{ customSelect(6,'drink2','Do you mind your partner drinks ','tiIsDrinkingPreferred',$addictionArray,old('tiIsDrinkingPreferred',$user->tiIsDrinkingPreferred)) }}
                                </div>
                                <div class="row">
                                    {{ customSelect(6,'user drugs1','Do you use recreational drugs ','tiUseDrugs',$addictionArray,old('tiUseDrugs',$user->tiUseDrugs)) }}
                                    {{ customSelect(6,'user drugs2','Do you mind your partner uses recreational drugs ','tiIsDrugPreferred',$addictionArray,old('tiUseDrugs',$user->tiIsDrugPreferred)) }}

                                </div> --}}
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">

                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (right) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection

@push('script')
<script src="{{ asset('theme/plugins/select2/select2.full.min.js') }}"></script>

<script>

$(document).ready(function(){
    $('#love_language').select2({
        placeholder:'Select Love language',
        width: 'resolve'
    })
    $('#user_interests').select2({
        placeholder:'Select your interests',
        width: 'resolve'
    })
});
</script>

@endpush
