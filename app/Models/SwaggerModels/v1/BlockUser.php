<?php
/**
 * @OA\Post(
 *      path="/user/block-user",
 *      tags={USER_TAG},
 *      summary="Block the User",
 *      operationId="blockUser",
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
 *                 required={"iBlockedUserId"},
 *                 @OA\Property(property="iBlockedUserId", type="integer"),
 *             )
 *         )
 *      ),
 *      @OA\Response(
 *         response=200,
 *         description="success",
 *         @OA\JsonContent(ref="#/components/schemas/CommonFields"),
 *      ),
 *      @OA\Response(
 *         response=400,
 *         description="Error",
 *         @OA\JsonContent(ref="#/components/schemas/CommonFields"),
 *      )
 * )
 */


 /**
 * @OA\Get(
 *      path="/user/block-user-list",
 *      tags={USER_TAG},
 *      summary="List of Blocked User",
 *      operationId="blockUserList",
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
 *         @OA\JsonContent(ref="#/components/schemas/BlockedUserResponse"),
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
 *   schema="BlockedUserResponse",
 *   type="object",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/CommonFields"),
 *     @OA\Schema(
 *          @OA\Property(
 *              property="responseData",
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/BlockedUserResponseFields")
 *          )
 *      )
 *   }
 * )
 */

 /**
 * @OA\Schema(
 *   schema="BlockedUserResponseFields",
 *   @OA\Property(
 *       property="iUserId",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="vFullName",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="vOccupation",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="vProfileImage",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="iAge",
 *       type="integer"
 *   ),
 *    @OA\Property(
 *       property="tiIsProfileImageVerified",
 *       type="integer"
 *   )
 * )
 */

 /**
 * @OA\Delete(
 *      path="/user/unblock-user/{iBlockedUserId}",
 *      tags={USER_TAG},
 *      summary="Unblock the User",
 *      operationId="unBlockUser",
 *      security={{ "bearerAuth":{} }},
 *      @OA\Parameter(
 *         in="header",
 *         name="Authorization",
 *         required=true,
 *          @OA\Schema(
 *              type="string"
 *          ),
 *      ),
 *      @OA\Parameter(
 *          name="iBlockedUserId",
 *          in="path",
 *          required=true,
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(
 *         response=200,
 *         description="success",
 *         @OA\JsonContent(ref="#/components/schemas/CommonFields"),
 *      ),
 *      @OA\Response(
 *         response=400,
 *         description="Error",
 *         @OA\JsonContent(ref="#/components/schemas/CommonFields"),
 *      )
 * )
 */

