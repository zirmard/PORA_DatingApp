<?php
/**
 * @OA\Post(
 *      path="/user/get-other-user-profile",
 *      tags={USER_TAG},
 *      summary="Get Other User Profile Details",
 *      operationId="getOtherUserProfile",
 *      security={{ "bearerAuth":{} }},
 *      @OA\Parameter(
 *         in="header",
 *         name="Authorization",
 *         required=true,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ),
 *      @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(
 *                 required={"iOtherUserId","dbLatitude","dbLongitude"},
 *                 @OA\Property(property="iOtherUserId", type="integer"),
 *                 @OA\Property(property="dbLatitude", type="string"),
 *                 @OA\Property(property="dbLongitude", type="string")
 *             )
 *         )
 *      ),
 *      @OA\Response(
 *         response=200,
 *         description="success",
 *         @OA\JsonContent(ref="#/components/schemas/GetOtherUserProfileResponse"),
 *      ),
 *      @OA\Response(
 *         response=400,
 *         description="Error",
 *         @OA\JsonContent(ref="#/components/schemas/CommonFields"),
 *      )
 * )
 */
/**
 * @OA\Schema(
 *   schema="GetOtherUserProfileResponse",
 *   type="object",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/CommonFields"),
 *     @OA\Schema(
 *          @OA\Property(
 *              property="responseData",
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/GetOtherUserProfileResponseFields")
 *          )
 *      )
 *   }
 * )
 */

/**
 * @OA\Schema(
 *   schema="GetOtherUserProfileResponseFields",
 *   @OA\Property(
 *       property="vAccessToken",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="iUserId",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="vFullname",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="iAge",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="vOccupation",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="vNationality",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="vCity",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="vEthnicGroup",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="iMatchedInterestsCount",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="txAboutYourSelf",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="tiIsProfileImageVerified",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="vDistance",
 *       type="string"
 *   ),
 *   @OA\Property(
 *      property="tiGender",
 *      type="integer"
 *   ),
 *   @OA\Property(
 *      property="tiIsUserLike",
 *      type="integer"
 *   ),
 *   @OA\Property(
 *       property="tiIsUserFavourite",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="isLikedByLoggedInUser",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="userFunInterests",
 *       type="array",
 *       @OA\Items(ref="#/components/schemas/UserFunInterestsFields")
 *   ),
 *   @OA\Property(
 *       property="userImages",
 *       type="array",
 *       @OA\Items(ref="#/components/schemas/UserImagesFields")
 *   ),
 *   @OA\Property(
 *       property="userLoveLanguages",
 *       type="array",
 *       @OA\Items(ref="#/components/schemas/UserLoveLanguagesFields")
 *   ),
 *   @OA\Property(
 *       property="vFaith",
 *       type="string"
 *   ),
 *
 * )
 */

 /**
 * @OA\Schema(
 *   schema="UserFunInterestsFields",
 *   @OA\Property(
 *       property="iInterestId",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="vInterestName",
 *       type="string"
 *   )
 * )
 */

 /**
 * @OA\Schema(
 *   schema="UserImagesFields",
 *   @OA\Property(
 *       property="iImageId",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="vImageName",
 *       type="string"
 *   )
 * )
 */


 /**
 * @OA\Schema(
 *   schema="UserLoveLanguagesFields",
 *   @OA\Property(
 *       property="iLoveLanguageId",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="vLoveLanguage",
 *       type="string"
 *   ),
 *    @OA\Property(
 *       property="vLoveLanguageLogo",
 *       type="string"
 *   )
 * )
 */
