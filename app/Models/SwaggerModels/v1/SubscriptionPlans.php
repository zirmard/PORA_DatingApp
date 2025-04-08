<?php
/**
 * @OA\Get(
 *      path="/user/subscription-plan-list",
 *      tags={SUBSCRIPTION_PLAN_LIST_TAG},
 *      summary="Get the list of subscription plans",
 *      operationId="subscriptionPlans",
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
 *         @OA\JsonContent(ref="#/components/schemas/SubscriptionPlanListResponse"),
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
 *   schema="SubscriptionPlanListResponse",
 *   type="object",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/CommonFields"),
 *     @OA\Schema(
 *          @OA\Property(
 *              property="responseData",
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/SubscriptionPlanListResponseFields")
 *          )
 *      )
 *   }
 * )
 */

/**
 * @OA\Schema(
 *   schema="SubscriptionPlanListResponseFields",
 *   @OA\Property(
 *       property="iSubscriptionPlanId",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="dbPlanPrice",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="vPlanType",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="vSkuId",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="tiIsSelected",
 *       type="integer"
 *   )
 * )
 */
