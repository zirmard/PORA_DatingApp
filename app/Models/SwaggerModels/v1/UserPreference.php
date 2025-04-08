<?php
/**
 * @OA\Post(
 *      path="/user/create-preference",
 *      tags={PREFERENCE_TAG},
 *      summary="Create User Preferences (Signup Step 4)",
 *      operationId="userPreferences",
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
 *                 @OA\Property(property="iInterestId", type="string",description="pass interest ids as a comma seprated string like 1,2,3"),
 *                 @OA\Property(property="tiSameEthnicity", type="integer", enum={1,2,3},description="1 - Yes, 2 - No, 3 - In different"),
 *                 @OA\Property(property="tiSameNationality", type="integer", enum={1,2,3},description="1 - Yes, 2 - No, 3 - In different"),
 *                 @OA\Property(property="vOccupation", type="string",description="Your Occupation"),
 *                 @OA\Property(property="vEarnings", type="string",description="Enter like $6000-$8000"),
 *                 @OA\Property(property="vPreferredEarnings", type="string",description="Enter like $6000-$8000"),
 *                 @OA\Property(property="tiIsDrink", type="integer", enum={1,2,3},description="1 - Never, 2 - Occasionally, 3 - Regular"),
 *                 @OA\Property(property="tiIsDrinkingPreferred", type="integer", enum={1,2,3,4},description="1 - Never, 2 - Occasionally, 3 - Regular, 4 - I am indifferent"),
 *                 @OA\Property(property="tiUseDrugs", type="integer", enum={1,2,3},description="1 - Never, 2 - Occasionally, 3 - Regular"),
 *                 @OA\Property(property="tiIsDrugPreferred", type="integer", enum={1,2,3,4},description="1 - Never, 2 - Occasionally, 3 - Regular, 4 - I am indifferent"),
 *                 @OA\Property(property="iLoveLanguageId", type="string",description="pass love language ids as a comma seprated string like 1,2,3"),
 *                 @OA\Property(property="tiBelieveInMarriage", type="integer", enum={1,2,3},description="1 - Yes, 2 - No, 3 - In different"),
 *                 @OA\Property(property="tiPreferredPreviouslyMarried", type="integer", enum={1,2,3},description="1 - Yes, 2 - No, 3 - In different"),
 *                 @OA\Property(property="tiHaveKids", type="integer", enum={1,2},description="1 - Yes, 2 - No"),
 *                 @OA\Property(property="tiLikeToHaveKids", type="integer", enum={1,2,3},description="1 - Yes, 2 - No, 3 - In different"),
 *                 @OA\Property(property="vEducationQualification", type="string",description="Your Educational Qualification"),
 *                 @OA\Property(property="tiPreferredEducation", type="integer", enum={1,2,3},description="1 - Yes, 2 - No, 3 - In different"),
 *                 @OA\Property(property="vPreferredEducation", type="string",description="Fill this when tiPreferredEducation is 1"),
 *                 @OA\Property(property="tiPreferredAge", type="integer", enum={1,2,3},description="1 - Older than me, 2 - Younger than me, 3 - In different"),
 *                 @OA\Property(property="tiPreferredReligiousBeliefs", type="integer", enum={1,2,3},description="1 - Yes, 2 - No, 3 - In different"),
 *                 @OA\Property(property="txAboutYourSelf", type="string",description="about your self"),
 *                 @OA\Property(property="txDealBreaker", type="string",description="deal breaker")
 *             )
 *         )
 *      ),
 *      @OA\Response(
 *         response=200,
 *         description="success",
 *         @OA\JsonContent(ref="#/components/schemas/UserResponse"),
 *      ),
 *      @OA\Response(
 *         response=400,
 *         description="Error",
 *         @OA\JsonContent(ref="#/components/schemas/CommonFields"),
 *      )
 * )
 */


/**
 * @OA\Get(
 *      path="/user-preference/get-discover",
 *      tags={PREFERENCE_TAG},
 *      summary="Home Page Users Listing API",
 *      operationId="getDiscover",
 *      security={{ "bearerAuth":{} }},
 *      @OA\Parameter(
 *         in="header",
 *         name="Authorization",
 *         required=true,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ),
 *      @OA\Response(
 *         response=200,
 *         description="success",
 *         @OA\JsonContent(ref="#/components/schemas/GetDiscoverResponse"),
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
 *   schema="GetDiscoverResponse",
 *   type="object",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/CommonFields"),
 *     @OA\Schema(
 *          @OA\Property(
 *              property="responseData",
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/GetDiscoverResponseFields")
 *          )
 *      )
 *   }
 * )
 */

/**
 * @OA\Schema(
 *   schema="GetDiscoverResponseFields",
 *   @OA\Property(
 *       property="iUserId",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="vFullName",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="vOccupation",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="txAboutYourSelf",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="vProfileImage",
 *       type="string"
 *   ),
 *   @OA\Property(
 *      property="iAge",
 *      type="integer"
 *   ),
 *   @OA\Property(
 *      property="tiIsProfileImageVerified",
 *      type="integer"
 *   )
 * )
 */
