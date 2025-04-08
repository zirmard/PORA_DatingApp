<?php
/**
 * @OA\Post(
 *      path="/user/update-lat-long",
 *      tags={USER_TAG},
 *      summary="Update Lat / Long",
 *      operationId="userUpdateLatLong",
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
 *                 required={"dbLatitude","dbLongitude"},
 *                 @OA\Property(property="dbLatitude", type="string"),
 *                 @OA\Property(property="dbLongitude", type="string"),
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
