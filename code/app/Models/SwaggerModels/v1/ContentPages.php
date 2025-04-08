<?php
/**
 * @OA\Get(
 *      path="/content-pages/{vSlug}",
 *      tags={COMMON_TAG},
 *      summary="Get CMS Page by Slug",
 *      operationId="contentPages",
 *      @OA\Parameter(
 *          name="vSlug",
 *          in="path",
 *          required=true,
 *          @OA\Schema(type="string"),
 *          description="about-us"
 *      ),
 *      @OA\Response(
 *         response=200,
 *         description="success",
 *         @OA\JsonContent(ref="#/components/schemas/ContentPageResponse"),
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
 *   schema="ContentPageResponse",
 *   type="object",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/CommonFields"),
 *     @OA\Schema(
 *          @OA\Property(
 *              property="responseData",
 *              type="object",
 *              ref="#/components/schemas/ContentPageResponseFields"
 *          )
 *      )
 *   }
 * )
 */


 /**
 * @OA\Schema(
 *   schema="ContentPageResponseFields",
 *   type="object",
 *   @OA\Property(
 *       property="vPageName",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="txContent",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="iUpdatedAt",
 *       type="integer",
 *       format="int32"
 *   )
 * )
 */
