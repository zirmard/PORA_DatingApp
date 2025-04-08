<?php
/**
 * @OA\Post(
 *      path="/user/profile-details",
 *      tags={USER_TAG},
 *      summary="User Profile Details (Signup Step 2)",
 *      operationId="profileDetails",
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
 *                 required={"vEmailId","tiGender","tiLookingFor","iDob","vZodiacSignName","vOriginCountry","vLivingCountry","vCity","vFaith","tiRelationshipStatus"},
 *                 @OA\Property(property="vEmailId", type="string"),
 *                 @OA\Property(property="vPassword", type="string"),
 *                 @OA\Property(property="vConfirmPassword", type="string"),
 *                 @OA\Property(property="tiGender", type="integer", enum={1,2},description="1 = Man, 2 = Woman"),
 *                 @OA\Property(property="tiLookingFor", type="integer", enum={1,2,3},description="1 - Love, 2 - Friendship, 3 - Both"),
 *                 @OA\Property(property="iDob", type="integer"),
 *                 @OA\Property(property="vZodiacSignName", type="string"),
 *                 @OA\Property(property="vOriginCountry", type="string"),
 *                 @OA\Property(property="vEthnicGroup", type="string"),
 *                 @OA\Property(property="vLivingCountry", type="string"),
 *                 @OA\Property(property="vCity", type="string"),
 *                 @OA\Property(property="vFaith", type="string"),
 *                 @OA\Property(property="tiRelationshipStatus", type="integer", enum={1,2,3},description="1 - Single, 2 - Widowed, 3 - Divorced"),
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
