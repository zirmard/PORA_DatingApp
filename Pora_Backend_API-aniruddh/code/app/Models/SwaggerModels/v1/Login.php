<?php
/**
 * @OA\Post(
 *      path="/oauth/login",
 *      tags={AUTHENTICATION_TAG},
 *      summary="Login User",
 *      operationId="signIn",
 *      @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(
 *                 required={"vEmailId", "vPassword", "vDeviceToken","tiDeviceType"},
 *                 @OA\Property(property="vEmailId", type="string"),
 *                 @OA\Property(property="vPassword", type="string"),
 *                 @OA\Property(property="vDeviceToken", type="string"),
 *                 @OA\Property(property="tiDeviceType", type="integer", format="int32", default="1",enum={0,1,2},description="0 = Web, 1 = android, 2 = ios"),
 *                 @OA\Property(property="vDeviceName", type="string")
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

