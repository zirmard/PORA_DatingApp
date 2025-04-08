<?php
/**
 * @OA\Post(
 *      path="/user/check-email-availability",
 *      tags={USER_TAG},
 *      summary="Check Email availability",
 *      operationId="checkEmailAvailability",
 *      @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/x-www-form-urlencoded",
 *             @OA\Schema(
 *                 required={"vEmailId"},
 *                 @OA\Property(property="vEmailId", type="string")
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
