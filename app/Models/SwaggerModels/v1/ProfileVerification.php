<?php
/**
 * @OA\Post(
 *      path="/user/profile-verification",
 *      tags={USER_TAG},
 *      summary="Upload Image & Video for profile verification (Face-x API) (Signup Step 3)",
 *      operationId="userProfileVerification",
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
 *                 required={"vProfileImage","vSelfieImage"},
 *                 @OA\Property(property="vProfileImage", type="string"),
 *                 @OA\Property(property="vVideo", type="string"),
 *                 @OA\Property(property="vSelfieImage", type="string")
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
