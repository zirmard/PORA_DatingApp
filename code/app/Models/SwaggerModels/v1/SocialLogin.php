<?php
/**
 * @OA\Post(
 *      path="/oauth/social-signin",
 *      tags={AUTHENTICATION_TAG},
 *      summary="Social signin",
 *      operationId="socialSignin",
 *      @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(
 *                 required={"vSocialId","tiSocialType","tiDeviceType","vDeviceToken"},
 *                 @OA\Property(property="vSocialId", type="string"),
 *                 @OA\Property(property="tiSocialType", type="integer", format="int32", default="1",enum={1,2},description="1 = Facebook, 2 = Google"),
 *                 @OA\Property(property="vDeviceToken", type="string"),
 *                 @OA\Property(property="tiDeviceType", type="integer", format="int32", default="1",enum={0,1,2},description="0 = Web, 1 = android, 2 = ios"),
 *                 @OA\Property(property="vDeviceName", type="string"),
 *                 @OA\Property(property="vTimezone", type="string")
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