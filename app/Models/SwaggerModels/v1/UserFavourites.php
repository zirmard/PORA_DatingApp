<?php
/**
 * @OA\Post(
 *      path="/user/favourite-profile",
 *      tags={USER_TAG},
 *      summary="This API is used to favourite / un-favourite the user",
 *      operationId="favouriteUser",
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
 *                 required={"tiTag","iFavouriteProfileId"},
 *                 @OA\Property(property="tiTag", type="integer", default="1", enum={0,1}, description="1 - When Add user to favourites, 0 - When Remove user from favourites"),
 *                 @OA\Property(property="iFavouriteProfileId", type="integer"),
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
 *      path="/user/favourite-list",
 *      tags={USER_TAG},
 *      summary="Get User Favourite List",
 *      operationId="favouriteList",
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
 *         @OA\JsonContent(ref="#/components/schemas/GetUserFavouriteResponse"),
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
 *   schema="GetUserFavouriteResponse",
 *   type="object",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/CommonFields"),
 *     @OA\Schema(
 *          @OA\Property(
 *              property="responseData",
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/GetUserFavouriteResponseFields")
 *          )
 *      )
 *   }
 * )
 */

/**
 * @OA\Schema(
 *   schema="GetUserFavouriteResponseFields",
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
 *   )
 * )
 */
