<?php
/**
 * @OA\Get(
 *      path="/love-languages",
 *      tags={LOVE_LANGUAGE_LIST},
 *      summary="List of Love Languages",
 *      operationId="loveLanguageList",
 *      @OA\Response(
 *         response=200,
 *         description="success",
 *         @OA\JsonContent(ref="#/components/schemas/LoveLanguageResponse"),
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
 *   schema="LoveLanguageResponse",
 *   type="object",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/CommonFields"),
 *     @OA\Schema(
 *          @OA\Property(
 *              property="responseData",
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/LoveLanguageResponseFields")
 *          )
 *      )
 *   }
 * )
 */

 /**
 * @OA\Schema(
 *   schema="LoveLanguageResponseFields",
 *   @OA\Property(
 *       property="iLoveLanguageId",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="vLoveLanguage",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="vLoveLanguageLogo",
 *       type="string"
 *   )
 * )
 */

