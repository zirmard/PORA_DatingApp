<?php
/**
 * @OA\Post(
 *      path="/user/edit-profile",
 *      tags={USER_TAG},
 *      summary="Edit user profile",
 *      operationId="editProfile",
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
 *                 @OA\Property(property="tiLookingFor", type="integer", enum={1,2,3},description="1 - Love, 2 - Friendship, 3 - Both"),
 *                 @OA\Property(property="vOriginCountry", type="string"),
 *                 @OA\Property(property="vEthnicGroup", type="string"),
 *                 @OA\Property(property="vLivingCountry", type="string"),
 *                 @OA\Property(property="vCity", type="string"),
 *                 @OA\Property(property="vFaith", type="string"),
 *                 @OA\Property(property="tiRelationshipStatus", type="integer", enum={1,2,3},description="1 - Single, 2 - Widowed, 3 - Divorced"),
 *                 @OA\Property(property="tiSameEthnicity", type="integer", enum={1,2,3},description="1 - Yes, 2 - No, 3 - In different"),
 *                 @OA\Property(property="vOccupation", type="string",description="Your Occupation"),
 *                 @OA\Property(property="vEarnings", type="string",description="Your Earnings",description="Enter like $6000-$8000"),
 *                 @OA\Property(property="vPreferredEarnings", type="string",description="Enter like $6000-$8000"),
 *                 @OA\Property(property="tiIsDrink", type="integer", enum={1,2,3},description="1 - Never, 2 - Occasionally, 3 - Regular"),
 *                 @OA\Property(property="tiIsDrinkingPreferred", type="integer", enum={1,2,3},description="1 - Never, 2 - Occasionally, 3 - Regular"),
 *                 @OA\Property(property="tiUseDrugs", type="integer", enum={1,2,3},description="1 - Never, 2 - Occasionally, 3 - Regular"),
 *                 @OA\Property(property="tiIsDrugPreferred", type="integer", enum={1,2,3},description="1 - Never, 2 - Occasionally, 3 - Regular"),
 *                 @OA\Property(property="tiBelieveInMarriage", type="integer", enum={1,2,3},description="1 - Yes, 2 - No, 3 - In different"),
 *                 @OA\Property(property="tiHaveKids", type="integer", enum={1,2},description="1 - Yes, 2 - No"),
 *                 @OA\Property(property="tiLikeToHaveKids", type="integer", enum={1,2,3},description="1 - Yes, 2 - No, 3 - In different"),
 *                 @OA\Property(property="tiPreferredEducation", type="integer", enum={1,2,3},description="1 - Yes, 2 - No, 3 - In different"),
 *                 @OA\Property(property="vPreferredEducation", type="string",description="Fill this when tiPreferredEducation is 1"),
 *                 @OA\Property(property="tiPreferredAge", type="integer", enum={1,2,3},description="1 - Older than me, 2 - Younger than me, 3 - In different"),
 *                 @OA\Property(property="tiSameNationality", type="integer", enum={1,2,3},description="1 - Yes, 2 - No, 3 - In different"),
 *                 @OA\Property(property="tiPreferredReligiousBeliefs", type="integer", enum={1,2,3},description="1 - Yes, 2 - No, 3 - In different"),
 *                 @OA\Property(property="tiPreferredPreviouslyMarried", type="integer", enum={1,2,3},description="1 - Yes, 2 - No, 3 - In different"),
 *                 @OA\Property(property="txAboutYourSelf", type="string",description="about your self"),
 *                 @OA\Property(property="txDealBreaker", type="string",description="deal breaker"),
 *                 @OA\Property(property="vEducationQualification", type="string"),
 *                 @OA\Property(property="iInterestId", type="string",description="pass interest ids as a comma seprated string like 1,2,3"),
 *                 @OA\Property(property="iLoveLanguageId", type="string",description="pass love language ids as a comma seprated string like 1,2,3"),
 *                 @OA\Property(property="vEmailId", type="string"),
 *                 @OA\Property(property="tiGender", type="integer", enum={1,2},description="1 = Man, 2 = Woman"),
 *                 @OA\Property(property="vVideo", type="string"),
 *                 @OA\Property(property="vImageName", type="string",description="pass image names as a comma seprated string like img1.png,img2.png"),
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
