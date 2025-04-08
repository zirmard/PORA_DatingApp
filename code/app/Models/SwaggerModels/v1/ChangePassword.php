<?php
/**
 * @OA\Post(
 *      path="/user/change-password",
 *      tags={USER_TAG},
 *      summary="Change password",
 *      operationId="changePassword",
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
 *                 required={"vCurrentPassword","vNewPassword","vConfirmPassword"},
 *                 @OA\Property(property="vCurrentPassword", type="string"),
 *                 @OA\Property(property="vNewPassword", type="string"),
 *                 @OA\Property(property="vConfirmPassword", type="string")
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
