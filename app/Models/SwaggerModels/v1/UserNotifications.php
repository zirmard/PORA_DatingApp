<?php
/**
 * @OA\Get(
 *      path="/user/notifications",
 *      tags={USER_NOTIFICATION_TAG},
 *      summary="This API is used to get Incoming Notifications",
 *      operationId="userNotifications",
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
 *         @OA\JsonContent(ref="#/components/schemas/UserNotificationResponse"),
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
 *   schema="UserNotificationResponse",
 *   type="object",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/CommonFields"),
 *     @OA\Schema(
 *          @OA\Property(
 *              property="responseData",
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/UserNotificationResponseFields")
 *          )
 *      )
 *   }
 * )
 */

/**
 * @OA\Schema(
 *   schema="UserNotificationResponseFields",
 *   @OA\Property(
 *       property="vNotificationTitle",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="vNotificationDesc",
 *       type="string"
 *   ),
 * @OA\Property(
 *       property="iSendUserId",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="iRecievedUserId",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="vFullName",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="iAge",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="vProfileImage",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="tiIsProfileImageVerified",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="tsCreatedAt",
 *       type="string"
 *   )
 * )
 */
