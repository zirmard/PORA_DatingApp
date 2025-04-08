<?php

/**
 * @OA\Post(
 *      path="/user/update-subscription",
 *      tags={SUBSCRIPTION_PLAN_LIST_TAG},
 *      summary="This api use update subscription",
 *      operationId="updateSubscription",
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
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"vProductId","vPackageName","vOrderId","ltxSubscriptionToken"},
 *                 @OA\Property(property="vProductId", type="string"),
 *                 @OA\Property(property="vPackageName", type="string"),
 *                 @OA\Property(property="vOrderId", type="string"),
 *                 @OA\Property(property="ltxSubscriptionToken", type="string"),
 *             )
 *         )
 *      ),
 *      @OA\Response(
 *         response=200,
 *         description="success",
 *         @OA\JsonContent(ref="#/components/schemas/UpdateSubscriptionResponse"),
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
 *   schema="UpdateSubscriptionResponse",
 *   type="object",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/CommonFields"),
 *     @OA\Schema(
 *          @OA\Property(
 *              property="responseData",
 *              type="object",
 *              ref="#/components/schemas/UpdateSubscriptionResponseFields"
 *          )
 *      )
 *   }
 * )
 */

/**
 * @OA\Schema(
 *   schema="UpdateSubscriptionResponseFields",
 *   type="object",
 *      @OA\Property(
 *          property="iUserId",
 *          type="integer"
 *      ),
 *      @OA\Property(
 *          property="vProductId",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="vPackageName",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="vOrderId",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="ltxSubscriptionToken",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="dtSubscriptionStartDate",
 *          type="int64"
 *      ),
 *      @OA\Property(
 *          property="tiIsAutoRenewing",
 *          type="boolean"
 *      ),
 *      @OA\Property(
 *          property="dtSubscriptionEndDate",
 *          type="int64"
 *      ),
 *      @OA\Property(
 *          property="tiIsPremiumUser",
 *          type="integer"
 *      ),
 * )
 */
