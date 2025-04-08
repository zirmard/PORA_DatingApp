<?php

/**
 * @OA\Post(
 *      path="/user/like-profile",
 *      tags={USER_TAG},
 *      summary="This API is used to like / un-like the user",
 *      operationId="likeUser",
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
 *                 required={"tiIsLike","iLikeUserId"},
 *                 @OA\Property(property="tiIsLike", type="integer", enum={0,1}, default="1", description="0 - When Remove user from like, 1 - When Add user to like"),
 *                 @OA\Property(property="iLikeUserId", type="integer"),
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
 *      path="/user/like-list",
 *      tags={USER_TAG},
 *      summary="Get User Like List",
 *      operationId="likeList",
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
 *         @OA\JsonContent(ref="#/components/schemas/GetUserLikeResponse"),
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
 *   schema="GetUserLikeResponse",
 *   type="object",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/CommonFields"),
 *     @OA\Schema(
 *          @OA\Property(
 *              property="responseData",
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/GetUserLikeResponseFields")
 *          )
 *      )
 *   }
 * )
 */

/**
 * @OA\Schema(
 *   schema="GetUserLikeResponseFields",
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
 *      property="iAge",
 *      type="integer"
 *   ),
 *   @OA\Property(
 *      property="tiIsProfileImageVerified",
 *      type="integer"
 *   ),
 *   @OA\Property(
 *      property="tsCreatedAt",
 *      type="string"
 *   )
 * )
 */


/**
 *   @OA\Get(
 *      path="/user/match-list",
 *      tags={USER_TAG},
 *      summary="Get User Match List (This is api will be used to show some prospects on chat screen)",
 *      operationId="matchList",
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
 *         @OA\JsonContent(ref="#/components/schemas/GetUserMatchResponse"),
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
 *   schema="GetUserMatchResponse",
 *   type="object",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/CommonFields"),
 *     @OA\Schema(
 *          @OA\Property(
 *              property="responseData",
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/GetUserMatchResponseFields")
 *          )
 *      )
 *   }
 * )
 */

/**
 * @OA\Schema(
 *   schema="GetUserMatchResponseFields",
 *   @OA\Property(
 *       property="iUserId",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="vFullName",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="vProfileImage",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="tiIsProfileImageVerified",
 *       type="integer"
 *   ),
 *  @OA\Property(
 *      property="vQuickBloxUserId",
 *      type="string"
 *   ),
 *   @OA\Property(
 *      property="vQbLogin",
 *      type="string"
 *   )
 * )
 */
