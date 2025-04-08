<?php
/**
 * @OA\Get(
 *      path="/fun-interests",
 *      tags={FUN_INTEREST_LIST},
 *      summary="List of Fun Interests",
 *      operationId="funInterestList",
 *      @OA\Response(
 *         response=200,
 *         description="success",
 *         @OA\JsonContent(ref="#/components/schemas/FunInterestResponse"),
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
 *   schema="FunInterestResponse",
 *   type="object",
 *   allOf={
 *     @OA\Schema(ref="#/components/schemas/CommonFields"),
 *     @OA\Schema(
 *          @OA\Property(
 *              property="responseData",
 *              type="array",
 *              @OA\Items(ref="#/components/schemas/FunInterestResponseFields")
 *          )
 *      )
 *   }
 * )
 */

 /**
 * @OA\Schema(
 *   schema="FunInterestResponseFields",
 *   @OA\Property(
 *       property="iInterestId",
 *       type="integer"
 *   ),
 *   @OA\Property(
 *       property="vInterestLogo",
 *       type="string"
 *   ),
 *   @OA\Property(
 *       property="vInterestName",
 *       type="string"
 *   )
 * )
 */

