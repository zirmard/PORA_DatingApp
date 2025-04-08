<?php
/**
 * @OA\Post(
 *      path="/oauth/signup",
 *      tags={AUTHENTICATION_TAG},
 *      summary="Signup user (step 1)",
 *      operationId="signUp",
 *      @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(
 *                 required={"vFirstName","vLastName","vISDCode","vMobileNumber","vDeviceToken","tiDeviceType"},
 *                 @OA\Property(property="vSocialId", type="string"),
 *                 @OA\Property(property="vFirstName", type="string"),
 *                 @OA\Property(property="vLastName", type="string"),
 *                 @OA\Property(property="vISDCode", type="string"),
 *                 @OA\Property(property="vMobileNumber", type="string"),
 *                 @OA\Property(property="vDeviceToken", type="string"),
 *                 @OA\Property(property="tiDeviceType", type="integer", format="int32", default="1",enum={0,1,2},description="0 = Web, 1 = android, 2 = ios"),
 *                 @OA\Property(property="vDeviceName", type="string")
 *             )
 *         )
 *      ),
 *      @OA\Response(
 *         response=200,
 *         description="Registration Success Response",
 *         @OA\JsonContent(ref="#/components/schemas/RegistrationResponse"),
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
 *   schema="RegistrationResponse",
 *   type="object",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/CommonFields"),
 *     @OA\Schema(
 *          @OA\Property(
 *              property="responseData",
 *              type="object",
 *              ref="#/components/schemas/RegistrationResponseFields"
 *          )
 *      )
 *   }
 * )
 */

/**
 * @OA\Schema(
 *   schema="RegistrationResponseFields",
 *   type="object",
 *      @OA\Property(
 *          property="vFirstName",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="vLastName",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="vISDCode",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="vMobileNumber",
 *          type="string"
 *      )
 * )
 */
