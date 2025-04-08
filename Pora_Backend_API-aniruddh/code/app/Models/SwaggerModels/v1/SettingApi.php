<?php
/**
 * @OA\POST(
 *      path="/setting-api",
 *      tags={USER_TAG},
 *      summary="This API is used to Setting API",
 *      operationId="settingApi",
 *      @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(
 *                 required={},
 *                 @OA\Property(property="token", type="string"),
 *             )
 *         )
 *      ),
 *      @OA\Response(
 *         response=200,
 *         description="success",
 *         @OA\JsonContent(ref="#/components/schemas/SettingApiResponse"),
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
 *   schema="SettingApiResponse",
 *   type="object",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/CommonFields"),
 *     @OA\Schema(
 *          @OA\Property(
 *              property="responseData",
 *              type="object",
 *              ref="#/components/schemas/SettingApiResponseFields"
 *          )
 *      )
 *   }
 * )
 */

/**
 * @OA\Schema(
 *   schema="SettingApiResponseFields",
 *   @OA\Property(
 *       property="tiIsAdminApproved",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="dtSubscriptionEndDate",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="ltxSubscriptionToken",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="tiIsPremiumUser",
 *       type="integer"
 *   ),
 * )
 */
